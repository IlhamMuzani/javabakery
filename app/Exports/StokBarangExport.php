<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Http\Request;
use App\Models\Penjualanproduk;
use Carbon\Carbon;

// class StokBarangExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell
// {
//     protected $request;
//     protected $counter;
//     protected $totalJumlah = 0;
//     protected $totalDiskon = 0;
//     protected $totalOverall = 0;
//     protected $finalResults = [];

//     public function __construct(Request $request)
//     {
//         $this->request = $request;
//         $this->counter = 1; // Inisialisasi nomor urut
//     }

//     public function collection()
//     {
//         $status = $this->request->status;
//         $tanggal_penjualan = $this->request->tanggal_penjualan;
//         $tanggal_akhir = $this->request->tanggal_akhir;
//         $toko_id = $this->request->toko_id;
//         $klasifikasi_id = $this->request->klasifikasi_id;
//         $produk_id = $this->request->produk; // Tambahkan filter produk

//         $query = Penjualanproduk::with('detailPenjualanProduk.produk')
//             ->when($status, function ($query, $status) {
//                 return $query->where('status', $status);
//             })
//             ->when($toko_id, function ($query, $toko_id) {
//                 return $query->where('toko_id', $toko_id);
//             })
//             ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
//                 return $query->whereHas('detailPenjualanProduk.produk', function ($q) use ($klasifikasi_id) {
//                     return $q->where('klasifikasi_id', $klasifikasi_id);
//                 });
//             })
//             ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
//                 $start = Carbon::parse($tanggal_penjualan)->startOfDay();
//                 $end = Carbon::parse($tanggal_akhir)->endOfDay();
//                 return $query->whereBetween('tanggal_penjualan', [$start, $end]);
//             })
//             ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
//                 $start = Carbon::parse($tanggal_penjualan)->startOfDay();
//                 return $query->where('tanggal_penjualan', '>=', $start);
//             })
//             ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
//                 $end = Carbon::parse($tanggal_akhir)->endOfDay();
//                 return $query->where('tanggal_penjualan', '<=', $end);
//             })
//             ->orderBy('tanggal_penjualan', 'desc');

//         $inquery = $query->get();

//         // Gabungkan hasil berdasarkan produk_id
//         foreach ($inquery as $penjualan) {
//             foreach ($penjualan->detailPenjualanProduk as $detail) {
//                 $produk = $detail->produk;

//                 if ($produk && (!$produk_id || $produk->id == $produk_id)) {
//                     $key = $produk->id;

//                     if (!isset($this->finalResults[$key])) {
//                         $this->finalResults[$key] = [
//                             'tanggal_penjualan' => $penjualan->tanggal_penjualan,
//                             'kode_lama' => $produk->kode_lama,
//                             'nama_produk' => $produk->nama_produk,
//                             'harga' => $produk->harga,
//                             'jumlah' => 0,
//                             'diskon' => 0,
//                             'total' => 0,
//                         ];
//                     }

//                     $this->finalResults[$key]['jumlah'] += $detail->jumlah;
//                     $this->finalResults[$key]['total'] += $detail->total;

//                     if ($detail->diskon > 0) {
//                         $diskonPerItem = $produk->harga * 0.10;
//                         $this->finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
//                     }
//                 }
//             }
//         }

//         // Hitung total jumlah, diskon, dan total
//         foreach ($this->finalResults as $result) {
//             $this->totalJumlah += $result['jumlah'];
//             $this->totalDiskon += $result['diskon'];
//             $this->totalOverall += $result['total'];
//         }

//         // Tambahkan baris total di akhir koleksi
//         $this->finalResults[] = [
//             'tanggal_penjualan' => 'Total',
//             'kode_lama' => '',
//             'nama_produk' => '',
//             'harga' => '',
//             'jumlah' => $this->totalJumlah,
//             'diskon' => $this->totalDiskon,
//             'total' => $this->totalOverall,
//         ];

//         return collect($this->finalResults);
//     }

//     public function headings(): array
//     {
//         return [
//             ['LAPORAN BARANG KELUAR'], // Judul di baris pertama
//             [], // Kosongkan baris kedua
//             ['No', 'Tanggal Penjualan', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Diskon', 'Total'] // Header tabel
//         ];
//     }

//     public function map($row): array
//     {
//         return [
//             $this->counter++,
//             $row['tanggal_penjualan'],
//             $row['kode_lama'],
//             $row['nama_produk'],
//             $row['harga'],
//             $row['jumlah'],
//             $row['diskon'],
//             $row['total'],
//         ];
//     }

//     public function title(): string
//     {
//         return 'Laporan Stok Barang'; // Nama sheet
//     }

//     public function startCell(): string
//     {
//         return 'A3'; // Data dimulai dari baris ketiga untuk mengakomodasi judul
//     }
// }




class StokBarangExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell
{
    protected $request;
    protected $counter;
    protected $totalJumlah = 0;
    protected $totalDiskon = 0;
    protected $totalOverall = 0;
    protected $finalResults = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut
    }

    public function collection()
    {
        $status = $this->request->status;
        $tanggal_penjualan = $this->request->tanggal_penjualan;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $toko_id = $this->request->toko_id;
        $klasifikasi_id = $this->request->klasifikasi_id;
        $produk_id = $this->request->produk; // Tambahkan filter produk

        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('detailPenjualanProduk.produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$start, $end]);
            })
            ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $start);
            })
            ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $end);
            })
            ->orderBy('tanggal_penjualan', 'desc');

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk && (!$produk_id || $produk->id == $produk_id)) {
                    // Tambahkan filter klasifikasi di sini
                    if ($klasifikasi_id && $produk->klasifikasi_id != $klasifikasi_id) {
                        continue; // Lewati produk yang tidak sesuai dengan klasifikasi
                    }

                    $key = $produk->id;

                    if (!isset($this->finalResults[$key])) {
                        $this->finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0, // Tambahkan penjualan kotor
                            'penjualan_bersih' => 0, // Tambahkan penjualan bersih
                        ];
                    }

                    // Jumlahkan jumlah dan total
                    $this->finalResults[$key]['jumlah'] += $detail->jumlah;
                    $this->finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga; // Hitung penjualan kotor
                    $this->finalResults[$key]['total'] += $detail->total;

                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                        $this->finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }

                    // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                    $this->finalResults[$key]['penjualan_bersih'] = $this->finalResults[$key]['penjualan_kotor'] - $this->finalResults[$key]['diskon'];
                }
            }
        }

        // Hitung total jumlah, diskon, dan total
        foreach ($this->finalResults as $result) {
            $this->totalJumlah += $result['jumlah'];
            $this->totalDiskon += $result['diskon'];
            $this->totalOverall += $result['total'];
        }

        // Tambahkan baris total di akhir koleksi
        $this->finalResults[] = [
            'tanggal_penjualan' => 'Total',
            'kode_lama' => '',
            'nama_produk' => '',
            'harga' => '',
            'jumlah' => $this->totalJumlah,
            'diskon' => $this->totalDiskon,
            'total' => $this->totalOverall,
            'penjualan_kotor' => '', // Kosongkan untuk total
            'penjualan_bersih' => '', // Kosongkan untuk total
        ];

        return collect($this->finalResults);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN BARANG KELUAR'], // Judul di baris pertama
            [], // Kosongkan baris kedua
            ['No', 'Tanggal Penjualan', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Diskon', 'Penjualan Kotor', 'Penjualan Bersih'] // Header tabel
        ];
    }

    public function map($row): array
    {
        return [
            $this->counter++,
            $row['tanggal_penjualan'],
            $row['kode_lama'],
            $row['nama_produk'],
            $row['harga'],
            $row['jumlah'],
            $row['diskon'],
            // $row['total'],
            $row['penjualan_kotor'], // Tambahkan penjualan kotor ke mapping
            $row['penjualan_bersih'], // Tambahkan penjualan bersih ke mapping
        ];
    }

    public function title(): string
    {
        return 'Laporan Stok Barang'; // Nama sheet
    }

    public function startCell(): string
    {
        return 'A3'; // Data dimulai dari baris ketiga untuk mengakomodasi judul
    }
}
