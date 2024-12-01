<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_barangjadipesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class StokBarangExportBMsemua implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Ambil parameter filter dari request
        $status = $this->request->status;
        $tanggal_pengiriman = $this->request->tanggal_pengiriman;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $toko_id = 1;  // Tetapkan toko_id langsung menjadi 1
        $klasifikasi_id = $this->request->klasifikasi_id; // Ambil filter klasifikasi_id dari request
        $produk_id = $this->request->produk_id; // Ambil filter produk_id dari request

        // Query untuk pengiriman_barangjadi
        $query1 = Pengiriman_barangjadi::join('produks', 'pengiriman_barangjadis.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadis.*', 'produks.kode_lama', 'produks.nama_produk', 'produks.harga', DB::raw('"barang_jadi" as sumber'))
            ->with('produk.klasifikasi');

        // Query untuk pengiriman_barangjadipesanan
        $query2 = Pengiriman_barangjadipesanan::join('produks', 'pengiriman_barangjadipesanans.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadipesanans.*', 'produks.kode_lama', 'produks.nama_produk', 'produks.harga', DB::raw('"barang_jadi_pesanan" as sumber'))
            ->with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query1->where('pengiriman_barangjadis.status', $status);
            $query2->where('pengiriman_barangjadipesanans.status', $status);
        }

        // Filter berdasarkan toko_id, pastikan selalu 1
        $query1->where('pengiriman_barangjadis.toko_id', $toko_id);
        $query2->where('pengiriman_barangjadipesanans.toko_id', $toko_id);

        // Jika produk dipilih, abaikan klasifikasi
        if ($produk_id) {
            $query1->where('pengiriman_barangjadis.produk_id', $produk_id);
            $query2->where('pengiriman_barangjadipesanans.produk_id', $produk_id);
        } else {
            // Filter klasifikasi hanya jika produk tidak dipilih
            if ($klasifikasi_id) {
                $query1->where('produks.klasifikasi_id', $klasifikasi_id);
                $query2->where('produks.klasifikasi_id', $klasifikasi_id);
            }
        }

        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query1->whereBetween('pengiriman_barangjadis.tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
            $query2->whereBetween('pengiriman_barangjadipesanans.tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query1->where('pengiriman_barangjadis.tanggal_pengiriman', '>=', $tanggal_pengiriman);
            $query2->where('pengiriman_barangjadipesanans.tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query1->where('pengiriman_barangjadis.tanggal_pengiriman', '<=', $tanggal_akhir);
            $query2->where('pengiriman_barangjadipesanans.tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query1->whereDate('pengiriman_barangjadis.tanggal_pengiriman', Carbon::today());
            $query2->whereDate('pengiriman_barangjadipesanans.tanggal_pengiriman', Carbon::today());
        }

        // Gabungkan hasil dari kedua query
        $stokBarangJadi = $query1->union($query2)->orderBy('kode_lama', 'asc')->get();

        // Mengelompokkan produk yang sama dan menjumlahkan jumlah serta total
        $groupedData = $stokBarangJadi->groupBy('produk_id')->map(function ($items) {
            return [
                'produk' => $items->first()->produk,
                'jumlah' => $items->sum('jumlah'),
                'total' => $items->sum(function ($item) {
                    return $item->jumlah * $item->produk->harga;
                }),
            ];
        });

        // Menghitung total keseluruhan
        $totalJumlah = $groupedData->sum('jumlah');
        $totalHarga = $groupedData->sum('total');

        // Menambahkan total ke dalam data
        $groupedData->push([
            'kode_lama' => 'Total',
            'nama_produk' => '',
            'harga' => '',
            'jumlah' => $totalJumlah,
            'total' => $totalHarga,
        ]);

        return collect($groupedData)->map(function ($data) {
            return [
                'kode_lama' => $data['produk']->kode_lama ?? '',
                'nama_produk' => $data['produk']->nama_produk ?? '',
                'harga' => $data['produk']->harga ?? '',
                'jumlah' => $data['jumlah'] ?? 0,
                'total' => $data['total'] ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        // Header untuk Excel
        return [
            ['LAPORAN BARANG MASUK'],
            ['Periode:', $this->request->tanggal_pengiriman . ' s/d ' . $this->request->tanggal_akhir],
            [],  // Baris kosong
            ['Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Total'],
        ];
    }

    public function map($row): array
    {
        return [
            $row['kode_lama'],
            $row['nama_produk'],
            $row['harga'],
            $row['jumlah'],
            $row['total'],
        ];
    }

    public function title(): string
    {
        return 'Laporan Barang Masuk';
    }
}
