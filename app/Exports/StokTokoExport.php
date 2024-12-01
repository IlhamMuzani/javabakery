<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Http\Request;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokotegal;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokopemalang;
use App\Models\Stok_tokobumiayu;
use App\Models\Stok_tokocilacap;
use Carbon\Carbon;

class StokTokoExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomStartCell
{
    protected $request;
    protected $counter;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut
    }

    public function collection()
    {
        $toko_id = $this->request->get('toko_id');
        $klasifikasi_id = $this->request->get('klasifikasi_id');
        $subklasifikasi_id = $this->request->get('subklasifikasi_id');

        // Ambil data produk dari stok sesuai dengan toko_id dan filter lainnya
        $stok = collect();

        switch ($toko_id) {
            case '1':
                $stok = Stok_tokobanjaran::with('produk');
                break;
            case '2':
                $stok = Stok_tokotegal::with('produk');
                break;
            case '3':
                $stok = Stok_tokoslawi::with('produk');
                break;
            case '4':
                $stok = Stok_tokopemalang::with('produk');
                break;
            case '5':
                $stok = Stok_tokobumiayu::with('produk');
                break;
            case '6':
                $stok = Stok_tokocilacap::with('produk');
                break;
            default:
                $stok = collect();
                break;
        }

        // Apply other filters if they are present
        if ($klasifikasi_id) {
            $stok = $stok->whereHas('produk', function ($query) use ($klasifikasi_id) {
                $query->where('klasifikasi_id', $klasifikasi_id);
            });
        }

        if ($subklasifikasi_id) {
            $stok = $stok->whereHas('produk', function ($query) use ($subklasifikasi_id) {
                $query->where('subklasifikasi_id', $subklasifikasi_id);
            });
        }

        $stok = $stok->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = $stok->groupBy('produk_id')->map(function ($group) {
            $firstItem = $group->first();
            $totalJumlah = $group->sum('jumlah');
            $firstItem->jumlah = $totalJumlah;
            return $firstItem;
        })->values()->map(function ($item) {
            $item->subTotal = $item->jumlah * $item->produk->harga;
            return $item;
        });

        // Hitung total stok dan subTotal
        $totalStok = $finalResults->sum('jumlah');
        $totalSubTotal = $finalResults->sum('subTotal');

        // Format hasil untuk diekspor
        $formattedResults = $finalResults->map(function ($item) {
            return [
                $this->counter++,
                $item->produk->kode_lama,
                $item->produk->nama_produk,
                $item->produk->harga,
                $item->jumlah,
                $item->subTotal
            ];
        });

        // Tambahkan baris total
        $formattedResults->push([
            'Total',
            '',
            '',
            '',
            $totalStok,
            $totalSubTotal
        ]);

        return $formattedResults;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN STOK TOKO'], // Judul di baris pertama
            [], // Kosongkan baris kedua
            ['No', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Sub Total'] // Header tabel
        ];
    }

    public function map($row): array
    {
        return $row;
    }

    public function title(): string
    {
        return 'Laporan Stok Toko'; // Nama sheet
    }

    public function startCell(): string
    {
        return 'A3'; // Data dimulai dari baris ketiga untuk mengakomodasi judul
    }

  
}