<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Detailtoko;
use App\Models\Detailtokoslawi;
use App\Models\Detailtokobanjaran;
use App\Models\Detailtokobumiayu;
use App\Models\Detailtokocilacap;
use App\Models\Detailtokopemalang;
use App\Models\Detailtokotegal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;



class HargajualController extends Controller
{
    public function index()
    {
        $tokoslawi = Tokoslawi::latest()->first();
        $tokobanjaran = Tokobanjaran::latest()->first();
        $tokotegal = Tokotegal::latest()->first();
        $tokopemalang = Tokopemalang::latest()->first();
        $tokobumiayu = Tokobumiayu::latest()->first();
        $tokocilacap = Tokocilacap::latest()->first();

        // Tambahkan orderBy('kode_lama', 'asc') untuk mengurutkan berdasarkan kode_lama secara ascending
        $produk = Produk::with(['tokoslawi', 'tokobanjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu', 'tokocilacap'])
                        ->orderBy('kode_lama', 'asc')
                        ->get();

        return view('admin.hargajual.index', compact('produk', 'tokoslawi', 'tokobanjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu', 'tokocilacap'));
    }

    public function indextegal()
    {
        // Pastikan model dan relasi sudah sesuai dengan kebutuhan.
        $tokoslawi = Tokoslawi::latest()->first();
        $tokobanjaran = Tokobanjaran::latest()->first();
        $tokotegal = Tokotegal::latest()->first();
        $tokopemalang = Tokopemalang::latest()->first();
        $tokobumiayu = Tokobumiayu::latest()->first();
        $tokocilacap = Tokocilacap::latest()->first();
    
        // Tambahkan pengurutan berdasarkan `kode_lama`.
        $produk = Produk::with(['tokoslawi', 'tokobanjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu', 'tokocilacap'])
                        ->orderBy('kode_lama', 'asc')
                        ->get();
    
        return view('admin.hargajual.indextegal', compact('produk', 'tokoslawi', 'tokobanjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu', 'tokocilacap'));
    }
    

    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
    }


    public function all(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = Detailtokoslawi::with('produk');

        if ($start_date && $end_date) {
            $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        $detailtokoslawi = $query->orderBy('updated_at', 'desc')
                                ->get();

        return view('admin.hargajual.all', compact('detailtokoslawi'));
    }

    

    public function edit($id)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        //
    }



    
    public function updateHarga(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|integer|exists:produks,id',
            'member_harga_bnjr' => 'nullable|numeric',
            'member_diskon_bnjr' => 'nullable|numeric',
            'non_harga_bnjr' => 'nullable|numeric',
            'non_diskon_bnjr' => 'nullable|numeric',
        ]);

        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($request->id);

        // Proses untuk Toko Banjaran
        $tokobanjaran = $produk->tokobanjaran->first();

        // Inisialisasi variabel harga dan diskon awal sebelum update
        $memberHargaAwal = $tokobanjaran ? $tokobanjaran->member_harga_bnjr : null;
        $memberDiskonAwal = $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : null;
        $nonMemberHargaAwal = $tokobanjaran ? $tokobanjaran->non_harga_bnjr : null;
        $nonMemberDiskonAwal = $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : null;

        if ($tokobanjaran) {
            // Update harga dan diskon untuk Toko Banjaran
            $tokobanjaran->member_harga_bnjr = $request->member_harga_bnjr ?? $tokobanjaran->member_harga_bnjr;
            $tokobanjaran->member_diskon_bnjr = $request->member_diskon_bnjr ?? $tokobanjaran->member_diskon_bnjr;
            $tokobanjaran->non_harga_bnjr = $request->non_harga_bnjr ?? $tokobanjaran->non_harga_bnjr;
            $tokobanjaran->non_diskon_bnjr = $request->non_diskon_bnjr ?? $tokobanjaran->non_diskon_bnjr;
            $tokobanjaran->save();
        } else {
            // Buat record baru jika belum ada
            $tokobanjaran = Tokobanjaran::create([
                'produk_id' => $produk->id,
                'member_harga_bnjr' => $request->member_harga_bnjr,
                'member_diskon_bnjr' => $request->member_diskon_bnjr,
                'non_harga_bnjr' => $request->non_harga_bnjr,
                'non_diskon_bnjr' => $request->non_diskon_bnjr,
            ]);
        }

        // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
        DetailTokobanjaran::create([
            'produk_id' => $produk->id,
            'tokobanjaran_id' => $tokobanjaran->id,
            'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
            'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
            'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
            'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
            'member_harga' => $request->member_harga_bnjr,
            'non_member_harga' => $request->non_harga_bnjr,
            'member_diskon' => $request->member_diskon_bnjr,
            'non_member_diskon' => $request->non_diskon_bnjr,
            'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
        ]);

        return response()->json(['success' => true]);
}

public function updateHargaTgl(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_tgl' => 'nullable|numeric',
        'member_diskon_tgl' => 'nullable|numeric',
        'non_harga_tgl' => 'nullable|numeric',
        'non_diskon_tgl' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Proses untuk Toko Banjaran
    $tokotegal = $produk->tokotegal->first();

    // Inisialisasi variabel harga dan diskon awal sebelum update
    $memberHargaAwal = $tokotegal ? $tokotegal->member_harga_tgl : null;
    $memberDiskonAwal = $tokotegal ? $tokotegal->member_diskon_tgl : null;
    $nonMemberHargaAwal = $tokotegal ? $tokotegal->non_harga_tgl : null;
    $nonMemberDiskonAwal = $tokotegal ? $tokotegal->non_diskon_tgl : null;

    if ($tokotegal) {
        // Update harga dan diskon untuk Toko Banjaran
        $tokotegal->member_harga_tgl = $request->member_harga_tgl ?? $tokotegal->member_harga_tgl;
        $tokotegal->member_diskon_tgl = $request->member_diskon_tgl ?? $tokotegal->member_diskon_tgl;
        $tokotegal->non_harga_tgl = $request->non_harga_tgl ?? $tokotegal->non_harga_tgl;
        $tokotegal->non_diskon_tgl = $request->non_diskon_tgl ?? $tokotegal->non_diskon_tgl;
        $tokotegal->save();
    } else {
        // Buat record baru jika belum ada
        $tokotegal = Tokotegal::create([
            'produk_id' => $produk->id,
            'member_harga_tgl' => $request->member_harga_tgl,
            'member_diskon_tgl' => $request->member_diskon_tgl,
            'non_harga_tgl' => $request->non_harga_tgl,
            'non_diskon_tgl' => $request->non_diskon_tgl,
        ]);
    }

    // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
    Detailtokotegal::create([
        'produk_id' => $produk->id,
        'tokotegal_id' => $tokotegal->id,
        'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
        'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
        'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
        'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
        'member_harga' => $request->member_harga_tgl,
        'non_member_harga' => $request->non_harga_tgl,
        'member_diskon' => $request->member_diskon_tgl,
        'non_member_diskon' => $request->non_diskon_tgl,
        'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    ]);

    return response()->json(['success' => true]);
}

public function updateHargaPml(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_pml' => 'nullable|numeric',
        'member_diskon_pml' => 'nullable|numeric',
        'non_harga_pml' => 'nullable|numeric',
        'non_diskon_pml' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Proses untuk Toko Banjaran
    $tokopemalang = $produk->tokopemalang->first();

    // Inisialisasi variabel harga dan diskon awal sebelum update
    $memberHargaAwal = $tokopemalang ? $tokopemalang->member_harga_pml : null;
    $memberDiskonAwal = $tokopemalang ? $tokopemalang->member_diskon_pml : null;
    $nonMemberHargaAwal = $tokopemalang ? $tokopemalang->non_harga_pml : null;
    $nonMemberDiskonAwal = $tokopemalang ? $tokopemalang->non_diskon_pml : null;

    if ($tokopemalang) {
        // Update harga dan diskon untuk Toko Banjaran
        $tokopemalang->member_harga_pml = $request->member_harga_pml ?? $tokopemalang->member_harga_pml;
        $tokopemalang->member_diskon_pml = $request->member_diskon_pml ?? $tokopemalang->member_diskon_pml;
        $tokopemalang->non_harga_pml = $request->non_harga_pml ?? $tokopemalang->non_harga_pml;
        $tokopemalang->non_diskon_pml = $request->non_diskon_pml ?? $tokopemalang->non_diskon_pml;
        $tokopemalang->save();
    } else {
        // Buat record baru jika belum ada
        $tokopemalang = Tokopemalang::create([
            'produk_id' => $produk->id,
            'member_harga_pml' => $request->member_harga_pml,
            'member_diskon_pml' => $request->member_diskon_pml,
            'non_harga_pml' => $request->non_harga_pml,
            'non_diskon_pml' => $request->non_diskon_pml,
        ]);
    }

    // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
    Detailtokopemalang::create([
        'produk_id' => $produk->id,
        'tokopemalang_id' => $tokopemalang->id,
        'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
        'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
        'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
        'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
        'member_harga' => $request->member_harga_pml,
        'non_member_harga' => $request->non_harga_pml,
        'member_diskon' => $request->member_diskon_pml,
        'non_member_diskon' => $request->non_diskon_pml,
        'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    ]);

    return response()->json(['success' => true]);
}

public function updateHargaSlw(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_slw' => 'nullable|numeric',
        'member_diskon_slw' => 'nullable|numeric',
        'non_harga_slw' => 'nullable|numeric',
        'non_diskon_slw' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Proses untuk Toko Banjaran
    $tokoslawi = $produk->tokoslawi->first();

    // Inisialisasi variabel harga dan diskon awal sebelum update
    $memberHargaAwal = $tokoslawi ? $tokoslawi->member_harga_slw : null;
    $memberDiskonAwal = $tokoslawi ? $tokoslawi->member_diskon_slw : null;
    $nonMemberHargaAwal = $tokoslawi ? $tokoslawi->non_harga_slw : null;
    $nonMemberDiskonAwal = $tokoslawi ? $tokoslawi->non_diskon_slw : null;

    if ($tokoslawi) {
        // Update harga dan diskon untuk Toko Banjaran
        $tokoslawi->member_harga_slw = $request->member_harga_slw ?? $tokoslawi->member_harga_slw;
        $tokoslawi->member_diskon_slw = $request->member_diskon_slw ?? $tokoslawi->member_diskon_slw;
        $tokoslawi->non_harga_slw = $request->non_harga_slw ?? $tokoslawi->non_harga_slw;
        $tokoslawi->non_diskon_slw = $request->non_diskon_slw ?? $tokoslawi->non_diskon_slw;
        $tokoslawi->save();
    } else {
        // Buat record baru jika belum ada
        $tokoslawi = Tokoslawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $request->member_harga_slw,
            'member_diskon_slw' => $request->member_diskon_slw,
            'non_harga_slw' => $request->non_harga_slw,
            'non_diskon_slw' => $request->non_diskon_slw,
        ]);
    }

    // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
    Detailtokoslawi::create([
        'produk_id' => $produk->id,
        'tokoslawi_id' => $tokoslawi->id,
        'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
        'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
        'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
        'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
        'member_harga' => $request->member_harga_slw,
        'non_member_harga' => $request->non_harga_slw,
        'member_diskon' => $request->member_diskon_slw,
        'non_member_diskon' => $request->non_diskon_slw,
        'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    ]);

    return response()->json(['success' => true]);
}

public function updateHargaBmy(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_bmy' => 'nullable|numeric',
        'member_diskon_bmy' => 'nullable|numeric',
        'non_harga_bmy' => 'nullable|numeric',
        'non_diskon_bmy' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Proses untuk Toko Banjaran
    $tokobumiayu = $produk->tokobumiayu->first();

    // Inisialisasi variabel harga dan diskon awal sebelum update
    $memberHargaAwal = $tokobumiayu ? $tokobumiayu->member_harga_bmy : null;
    $memberDiskonAwal = $tokobumiayu ? $tokobumiayu->member_diskon_bmy : null;
    $nonMemberHargaAwal = $tokobumiayu ? $tokobumiayu->non_harga_bmy : null;
    $nonMemberDiskonAwal = $tokobumiayu ? $tokobumiayu->non_diskon_bmy : null;

    if ($tokobumiayu) {
        // Update harga dan diskon untuk Toko Banjaran
        $tokobumiayu->member_harga_bmy = $request->member_harga_bmy ?? $tokobumiayu->member_harga_bmy;
        $tokobumiayu->member_diskon_bmy = $request->member_diskon_bmy ?? $tokobumiayu->member_diskon_bmy;
        $tokobumiayu->non_harga_bmy = $request->non_harga_bmy ?? $tokobumiayu->non_harga_bmy;
        $tokobumiayu->non_diskon_bmy = $request->non_diskon_bmy ?? $tokobumiayu->non_diskon_bmy;
        $tokobumiayu->save();
    } else {
        // Buat record baru jika belum ada
        $tokobumiayu = Tokobumiayu::create([
            'produk_id' => $produk->id,
            'member_harga_bmy' => $request->member_harga_bmy,
            'member_diskon_bmy' => $request->member_diskon_bmy,
            'non_harga_bmy' => $request->non_harga_bmy,
            'non_diskon_bmy' => $request->non_diskon_bmy,
        ]);
    }

    // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
    Detailtokobumiayu::create([
        'produk_id' => $produk->id,
        'tokobumiayu_id' => $tokobumiayu->id,
        'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
        'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
        'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
        'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
        'member_harga' => $request->member_harga_bmy,
        'non_member_harga' => $request->non_harga_bmy,
        'member_diskon' => $request->member_diskon_bmy,
        'non_member_diskon' => $request->non_diskon_bmy,
        'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    ]);

    return response()->json(['success' => true]);
}

public function updateHargaClc(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_clc' => 'nullable|numeric',
        'member_diskon_clc' => 'nullable|numeric',
        'non_harga_clc' => 'nullable|numeric',
        'non_diskon_clc' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Proses untuk Toko Banjaran
    $tokocilacap = $produk->tokocilacap->first();

    // Inisialisasi variabel harga dan diskon awal sebelum update
    $memberHargaAwal = $tokocilacap ? $tokocilacap->member_harga_clc : null;
    $memberDiskonAwal = $tokocilacap ? $tokocilacap->member_diskon_clc : null;
    $nonMemberHargaAwal = $tokocilacap ? $tokocilacap->non_harga_clc : null;
    $nonMemberDiskonAwal = $tokocilacap ? $tokocilacap->non_diskon_clc : null;

    if ($tokocilacap) {
        // Update harga dan diskon untuk Toko Banjaran
        $tokocilacap->member_harga_clc = $request->member_harga_clc ?? $tokocilacap->member_harga_clc;
        $tokocilacap->member_diskon_clc = $request->member_diskon_clc ?? $tokocilacap->member_diskon_clc;
        $tokocilacap->non_harga_clc = $request->non_harga_clc ?? $tokocilacap->non_harga_clc;
        $tokocilacap->non_diskon_clc = $request->non_diskon_clc ?? $tokocilacap->non_diskon_clc;
        $tokocilacap->save();
    } else {
        // Buat record baru jika belum ada
        $tokocilacap = Tokocilacap::create([
            'produk_id' => $produk->id,
            'member_harga_clc' => $request->member_harga_clc,
            'member_diskon_clc' => $request->member_diskon_clc,
            'non_harga_clc' => $request->non_harga_clc,
            'non_diskon_clc' => $request->non_diskon_clc,
        ]);
    }

    // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
    Detailtokocilacap::create([
        'produk_id' => $produk->id,
        'tokocilacap_id' => $tokocilacap->id,
        'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
        'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
        'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
        'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
        'member_harga' => $request->member_harga_clc,
        'non_member_harga' => $request->non_harga_clc,
        'member_diskon' => $request->member_diskon_clc,
        'non_member_diskon' => $request->non_diskon_clc,
        'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    ]);

    return response()->json(['success' => true]);
}
   


    public function show()
    {
        // Mengambil data perubahan harga dan diskon dari tabel detailtokobanjaran
        $perubahanProduks = DetailTokobanjaran::with(['produk', 'tokobanjaran'])
            ->where(function($query) {
                $query->whereColumn('member_harga', '!=', 'member_hargaawal')
                    ->orWhereColumn('member_diskon', '!=', 'member_diskonawal')
                    ->orWhereColumn('non_member_harga', '!=', 'non_member_hargaawal')
                    ->orWhereColumn('non_member_diskon', '!=', 'non_member_diskonawal');
            })
            ->orderBy('tanggal_perubahan', 'desc')
            ->get();
        
        // Pastikan data dikirimkan ke view dengan nama 'perubahanProduks'
        return view('admin.hargajual.show', compact('perubahanProduks'));
    }
    
    public function print()
    {
        $perubahanProduks = DetailTokobanjaran::with(['produk', 'tokobanjaran'])
            ->orderBy('tanggal_perubahan', 'desc')
            ->get();

        $pdf = FacadePdf::loadView('admin.hargajual.print', compact('perubahanProduks'));
        return $pdf->stream('laporan_perubahan_harga.pdf');
    }
    



    public function cetakPdf(Request $request)
    {
        $toko = $request->input('toko', 'tokoslawi');
        $today = Carbon::today();

        // Query hanya data yang diubah hari ini
        $produk = Produk::whereHas($toko, function ($query) use ($today) {
            $query->whereDate('updated_at', $today);
        })->with([$toko])->get();

        // Periksa apakah view tersedia
        if (!view()->exists('admin.hargajual.cetak-pdf')) {
            return response()->json(['error' => 'View not found'], 404);
        }

        $pdf = FacadePdf::loadView('admin.hargajual.cetak-pdf', compact('produk', 'toko'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Harga Jual.pdf');
    }


    
    public function destroy($id)
    {
        //
    }
}
