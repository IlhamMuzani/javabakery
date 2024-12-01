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

class StokBarangExportBMpesanan implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell, ShouldAutoSize
{
    protected $request;
    protected $counter;
    protected $totalJumlah = 0;
    protected $totalHarga = 0;
    protected $finalResults = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut
    }

    public function collection()
    {
        // Filter dan query sama dengan yang digunakan di PDF
        $status = $this->request->status;
        $tanggal_pengiriman = $this->request->tanggal_pengiriman;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $toko_id = 1;  // Set toko_id = 1 secara langsung
        $klasifikasi_id = $this->request->klasifikasi_id;
    
        $query = Pengiriman_barangjadipesanan::with('produk.klasifikasi')
            ->orderBy('tanggal_pengiriman', 'desc')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);  // Filter berdasarkan toko_id = 1
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_pengiriman && $tanggal_akhir, function ($query) use ($tanggal_pengiriman, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_pengiriman)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_pengiriman', [$start, $end]);
            })
            ->when($tanggal_pengiriman && !$tanggal_akhir, function ($query) use ($tanggal_pengiriman) {
                $start = Carbon::parse($tanggal_pengiriman)->startOfDay();
                return $query->where('tanggal_pengiriman', '>=', $start);
            })
            ->when(!$tanggal_pengiriman && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_pengiriman', '<=', $end);
            });
    
        $inquery = $query->get();
    
        foreach ($inquery as $pengiriman) {
            $produk = $pengiriman->produk;
            if ($produk) {
                $key = $produk->id;
                if (!isset($this->finalResults[$key])) {
                    $totalHarga = $pengiriman->jumlah * $produk->harga;
                    $diskon = $pengiriman->diskon ? ($totalHarga * 0.10) : 0;  // Diskon 10% jika ada
    
                    $this->finalResults[$key] = [
                        'tanggal_pengiriman' => $pengiriman->tanggal_pengiriman,
                        'kode_lama' => $produk->kode_lama,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'jumlah' => $pengiriman->jumlah,
                        'diskon' => $diskon,
                        'total' => $totalHarga - $diskon,
                    ];
                } else {
                    $this->finalResults[$key]['jumlah'] += $pengiriman->jumlah;
                    $totalHarga = $pengiriman->jumlah * $produk->harga;
                    $this->finalResults[$key]['total'] += $totalHarga;
    
                    if ($pengiriman->diskon) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $this->finalResults[$key]['diskon'] += $pengiriman->jumlah * $diskonPerItem;
                        $this->finalResults[$key]['total'] -= $pengiriman->jumlah * $diskonPerItem;
                    }
                }
    
                // Update total jumlah and harga
                $this->totalJumlah += $pengiriman->jumlah;
                $this->totalHarga += $totalHarga - $diskon;
            }
        }
    
        $resultsCollection = collect($this->finalResults);
    
        // Tambahkan total ke collection
        $resultsCollection->push([
            'tanggal_pengiriman' => 'Total',
            'kode_lama' => '',
            'nama_produk' => '',
            'harga' => '',
            'jumlah' => $this->totalJumlah,
            'diskon' => '',
            'total' => $this->totalHarga,
        ]);
    
        return $resultsCollection;
    }
    

    public function headings(): array
    {
        // Header yang sama dengan di PDF
        return [
            ['LAPORAN BARANG MASUK'],
            ['Periode:', $this->request->tanggal_pengiriman . ' s/d ' . $this->request->tanggal_akhir],
            // ['Cabang:', 'Semua Toko'],
            [], // Baris kosong
            ['No', 'Tanggal Pengiriman', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Total'],
        ];
    }

    public function map($row): array
    {
        return [
            $this->counter++,
            $row['tanggal_pengiriman'],
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

    public function startCell(): string
    {
        return 'A5'; // Data dimulai dari baris ke-5 untuk mengakomodasi judul
    }
}
