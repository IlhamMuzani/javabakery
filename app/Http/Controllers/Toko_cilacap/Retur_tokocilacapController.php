<?php

namespace App\Http\Controllers\Toko_cilacap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokobanjaran;
use App\Models\Retur_tokoslawi;
use App\Models\Klasifikasi;
use App\Models\Retur_barangjadi;
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Retur_barnagjadi;
use App\Models\Retur_tokocilacap;
use App\Models\Stok_tokocilacap;
use App\Models\Stok_tokotegal;
use Maatwebsite\Excel\Facades\Excel;

class Retur_tokocilacapController extends Controller
{



    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;

        $query = Retur_tokocilacap::with('produk.klasifikasi');

        if ($status) {
            $query->where('status', $status);
        }

        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $query->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_input', Carbon::today());
        }

        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

        return view('toko_cilacap.retur_tokocilacap.index', compact('stokBarangJadi'));
    }

    // public function create()
    // {
    //     $produks = Produk::all();
    //     $tokos = Toko::all();
    //     $klasifikasis = Klasifikasi::all(); // Pastikan ini ada

    //     return view('toko_cilacap.retur_tokocilacap.create', compact('produks', 'tokos', 'klasifikasis'));
    // }

    public function create()
    {
        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();

        // Ambil stok dari tabel stok_tokobanjaran berdasarkan produk_id
        $stokProduk = DB::table('stok_tokocilacaps')
            ->select('produk_id', DB::raw('SUM(jumlah) as jumlah_stok'))
            ->groupBy('produk_id')
            ->pluck('jumlah_stok', 'produk_id');

        return view('toko_cilacap.retur_tokocilacap.create', compact('produks', 'tokos', 'klasifikasis', 'stokProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'keterangan' => 'required|array',
        ]);

        // Jika tanggal_input tidak diisi, gunakan tanggal hari ini
        $tanggalPengiriman = $request->input('tanggal_input', now()->toDateString());
        $tanggalPengirimanDenganJam = Carbon::parse($tanggalPengiriman)->setTime(now()->hour, now()->minute);

        // Gunakan function kode dengan tanggal pengiriman
        $kode = $this->kode($tanggalPengirimanDenganJam);

        $produk_ids = $request->input('produk_id');
        $jumlahs = $request->input('jumlah');
        $keterangans = $request->input('keterangan');

        // Array untuk menampung pesan error
        $errors = [];

        foreach ($produk_ids as $index => $produk_id) {
            $jumlah_yang_dibutuhkan = $jumlahs[$index];

            $produk = Produk::find($produk_id);
            if (!$produk) {
                $errors[] = 'Produk tidak ditemukan.';
                continue;
            }

            $nama_produk = $produk->nama_produk;

            // Ambil semua stok yang tersedia untuk produk ini
            $stok_items = Stok_tokocilacap::where('produk_id', $produk_id)
                ->where('jumlah', '>', 0)
                ->orderBy('jumlah', 'asc')
                ->get();

            if ($stok_items->isEmpty()) {
                $errors[] = 'Stok untuk produk ' . $nama_produk . ' tidak ditemukan.';
                continue;
            }

            // Periksa total stok yang tersedia
            $total_stok_tersedia = $stok_items->sum('jumlah');
            if ($total_stok_tersedia < $jumlah_yang_dibutuhkan) {
                $errors[] = 'Stok untuk produk ' . $nama_produk . ' tidak mencukupi. Total stok tersedia: ' . $total_stok_tersedia;
                continue;
            }

            // Lakukan pengurangan stok
            $sisa_kebutuhan = $jumlah_yang_dibutuhkan;

            foreach ($stok_items as $stok) {
                if ($sisa_kebutuhan <= 0) {
                    break; // Jika kebutuhan sudah terpenuhi, hentikan pengurangan stok
                }

                if ($stok->jumlah >= $sisa_kebutuhan) {
                    // Jika stok cukup untuk memenuhi seluruh sisa kebutuhan
                    $stok->jumlah -= $sisa_kebutuhan;
                    $stok->save();
                    $sisa_kebutuhan = 0; // Kebutuhan terpenuhi
                } else {
                    // Jika stok tidak cukup, kurangi stok yang ada dan lanjutkan ke item berikutnya
                    $sisa_kebutuhan -= $stok->jumlah;
                    $stok->jumlah = 0;
                    $stok->save();
                }
            }

            // Menyimpan retur dengan status 'posting'
            Retur_tokocilacap::create([
                'kode_retur' => $kode,
                'produk_id' => $produk_id,
                'toko_id' => '6',
                'status' => 'unpost',
                'jumlah' => $jumlah_yang_dibutuhkan,
                'keterangan' => $keterangans[$index],
                'tanggal_input' => $tanggalPengirimanDenganJam,
            ]);

            Retur_barangjadi::create([
                'kode_retur' => $kode,
                'produk_id' => $produk_id,
                'toko_id' => '6',
                'nama_produk' => $nama_produk . ' RETUR',
                'status' => 'unpost',
                'jumlah' => $jumlah_yang_dibutuhkan,
                'keterangan' => $keterangans[$index],
                'tanggal_retur' => $tanggalPengirimanDenganJam,
            ]);
        }

        // Jika ada error, kembalikan semua pesan error
        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }

        return redirect()->route('retur_tokocilacap.index')->with('success', 'Data retur barang berhasil disimpan dan stok berhasil dikurangi.');
    }

    public function kode($tanggalPengiriman)
    {
        $prefix = 'FRG';
        $year = Carbon::parse($tanggalPengiriman)->format('y'); // Dua digit terakhir dari tahun
        $date = Carbon::parse($tanggalPengiriman)->format('dm'); // Format bulan dan hari: MMDD

        // Mengambil kode retur terakhir yang dibuat pada hari yang sama
        $lastBarang = Retur_tokocilacap::whereDate('tanggal_input', Carbon::parse($tanggalPengiriman))
            ->orderBy('kode_retur', 'desc')
            ->first();

        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_retur;
            $lastNum = (int) substr($lastCode, strlen($prefix  . $date . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }

        $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
        $newCode = $prefix  . $date . $year . $formattedNum;
        return $newCode;
    }


    public function unpost_retur($id)
    {
        // Ambil data stok barang berdasarkan ID
        $stok = Retur_tokocilacap::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$stok) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil kode_input dari stok yang diambil
        $kodeInput = $stok->kode_retur;

        // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
        Retur_tokocilacap::where('kode_retur', $kodeInput)->update([
            'status' => 'unpost'
        ]);
        return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
    }


    public function posting_retur($id)
    {
        // Ambil data Retur_tokoslawi berdasarkan ID
        $pengiriman = Retur_tokocilacap::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$pengiriman) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        // Ambil kode_retur dari pengiriman yang diambil
        $kodePengiriman = $pengiriman->kode_retur;

        // Update status untuk semua Retur_tokoslawi dengan kode_retur yang sama
        Retur_tokocilacap::where('kode_retur', $kodePengiriman)->update([
            'status' => 'posting'
        ]);

        // Update status untuk semua stok_tokoslawi terkait dengan Retur_tokoslawi_id
        Stok_tokocilacap::where('pengiriman_barangjadi_id', $id)->update([
            'status' => 'posting'
        ]);

        return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama.']);
    }

    public function show($id)
    {
        // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Retur_tokocilacap::where('id', $id)->value('kode_retur');

        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Retur_tokocilacap::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();

        return view('toko_cilacap.inquery_returcilacap.show', compact('pengirimanBarangJadi', 'firstItem'));
    }



    public function print($id)
    {
        $kodeRetur = Retur_tokocilacap::where('id', $id)->value('kode_retur');

        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$kodeRetur) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Retur_tokocilacap::with([
            'produk.subklasifikasi.klasifikasi', // Tambahkan relasi klasifikasi
            'toko'
        ])->where('kode_retur', $kodeRetur)->get();

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();

        $pdf = FacadePdf::loadView('toko_cilacap.inquery_returcilacap.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }
}