<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Http\Request;
use App\Models\Retur_barangjadi;
use Carbon\Carbon;

class StokBarangExportBR implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell
{
    protected $request;
    protected $counter;
    protected $totalJumlah = 0;
    protected $grandTotal = 0;
    protected $finalResults = [];
    protected $totalRow = false;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut
    }

    public function collection()
    {
        $status = $this->request->status;
        $tanggal_retur = $this->request->tanggal_retur;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $klasifikasi_id = $this->request->klasifikasi_id;

        // Tetapkan toko_id menjadi 1
        $toko_id = 1;

        // Query dasar untuk mengambil data Retur_barangjadi
        $query = Retur_barangjadi::with('produk.klasifikasi')
            ->orderBy('tanggal_retur', 'desc')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->where('toko_id', $toko_id) // Pastikan hanya data dengan toko_id = 1 yang diambil
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_retur && $tanggal_akhir, function ($query) use ($tanggal_retur, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_retur)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_retur', [$start, $end]);
            })
            ->when($tanggal_retur && !$tanggal_akhir, function ($query) use ($tanggal_retur) {
                $start = Carbon::parse($tanggal_retur)->startOfDay();
                return $query->where('tanggal_retur', '>=', $start);
            })
            ->when(!$tanggal_retur && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_retur', '<=', $end);
            })
            ->when(!$tanggal_retur && !$tanggal_akhir, function ($query) {
                return $query->whereDate('tanggal_retur', Carbon::today());
            });

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        foreach ($inquery as $retur) {
            $produk = $retur->produk;

            if ($produk) {
                $key = $produk->id;

                if (!isset($this->finalResults[$key])) {
                    $totalHarga = $retur->jumlah * $produk->harga;

                    $this->finalResults[$key] = [
                        'tanggal_retur' => $retur->tanggal_retur,
                        'kode_lama' => $produk->kode_lama,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'jumlah' => $retur->jumlah,
                        'total' => $totalHarga,
                    ];
                } else {
                    $this->finalResults[$key]['jumlah'] += $retur->jumlah;
                    $this->finalResults[$key]['total'] += $retur->jumlah * $produk->harga;
                }

                // Update total jumlah and harga
                $this->totalJumlah += $retur->jumlah;
                $this->grandTotal += $retur->jumlah * $produk->harga;
            }
        }

        // Konversi array finalResults ke collection untuk diekspor
        $resultsCollection = collect($this->finalResults);

        // Menambahkan baris total di akhir koleksi
        $resultsCollection->push([
            'tanggal_retur' => 'Total',
            'kode_lama' => '',
            'nama_produk' => '',
            'harga' => '',
            'jumlah' => $this->totalJumlah,
            'total' => $this->grandTotal,
        ]);

        return $resultsCollection;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN BARANG RETUR'], // Judul di baris pertama
            [], // Kosongkan baris kedua
            ['No', 'Tanggal Retur', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Total'] // Header tabel
        ];
    }

    public function map($row): array
    {
        return [
            $this->counter++,
            $row['tanggal_retur'],
            $row['kode_lama'],
            $row['nama_produk'],
            $row['harga'],
            $row['jumlah'],
            $row['total'],
        ];
    }

    public function title(): string
    {
        return 'Laporan Barang Retur'; // Nama sheet
    }

    public function startCell(): string
    {
        return 'A3'; // Data dimulai dari baris ketiga untuk mengakomodasi judul
    }
}
