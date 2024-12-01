<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProdukExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $produkWithStok;
    protected $namaKlasifikasi; // Tambahkan untuk menyimpan nama klasifikasi

    public function __construct($produkWithStok, $namaKlasifikasi = 'Semua Klasifikasi')
    {
        $this->produkWithStok = $produkWithStok;
        $this->namaKlasifikasi = $namaKlasifikasi;
    }

    public function collection()
    {
        return $this->produkWithStok->map(function ($produk, $index) {
            return [
                'no' => $index + 1, 
                'kode_produk' => $produk->kode_lama ?? '-', 
                'nama_produk' => $produk->nama_produk ?? '-',
                'stok' => $produk->jumlah ?? 0,
                'harga_jual' => $produk->harga ?? 0, 
                'subtotal' => $produk->subTotal ?? 0, 
            ];
        })->push($this->totalRow()); 
    }



    public function headings(): array
    {
        // Mendapatkan tanggal dan waktu sekarang
        $currentDateTime = now()->format('d-m-Y H:i:s');
    
        return [
            ['PT JAVABAKERY FACTORY'],                    // Baris pertama untuk judul
            ['Divisi: ' . $this->namaKlasifikasi],  // Baris kedua untuk nama klasifikasi
            ['Tanggal: ' . $currentDateTime],            // Baris ketiga untuk tanggal dan waktu
            [], // Baris kosong untuk spasi
            ['No', 'Kode Produk', 'Nama Produk', 'Stok', 'Harga Jual', 'Sub Total'], // Header tabel
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Menggabungkan sel untuk setiap baris judul
        $sheet->mergeCells('A1:F1'); // Merge untuk baris judul
        $sheet->mergeCells('A2:F2'); // Merge untuk baris klasifikasi
        $sheet->mergeCells('A3:F3'); // Merge untuk baris tanggal

        // Set nilai untuk judul, klasifikasi, dan tanggal
        $sheet->setCellValue('A1', 'PT JAVABAKERY FACTORY'); // Judul
        $sheet->setCellValue('A2', 'Divisi: ' . $this->namaKlasifikasi); // Klasifikasi
        $sheet->setCellValue('A3', 'Tanggal: ' . now()->format('d-m-Y H:i:s')); // Tanggal

        // Mengatur gaya font dan alignment
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Judul besar dan tebal
        $sheet->getStyle('A2')->getFont()->setBold(true); // Klasifikasi tebal
        $sheet->getStyle('A3')->getFont()->setItalic(true); // Tanggal miring
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Rata tengah

        // Mengatur gaya header tabel
        $sheet->getStyle('A5:F5')->getFont()->setBold(true); // Header tabel bold
        $sheet->getStyle('A5:F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Header tabel rata tengah

        // Format angka dengan titik pemisah ribuan
        $sheet->getStyle('D6:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E6:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('F6:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');

        // Mengatur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);  // Kolom No
        $sheet->getColumnDimension('B')->setWidth(15); // Kolom Kode Produk
        $sheet->getColumnDimension('C')->setWidth(30); // Kolom Nama Produk
        $sheet->getColumnDimension('D')->setWidth(10); // Kolom Stok
        $sheet->getColumnDimension('E')->setWidth(15); // Kolom Harga Jual
        $sheet->getColumnDimension('F')->setWidth(15); // Kolom Sub Total

        // Menambahkan gaya bold untuk baris total (tergantung baris total pada sheet)
        $lastRow = $sheet->getHighestRow();  // Menentukan baris terakhir
        $sheet->getStyle("D{$lastRow}:F{$lastRow}")->getFont()->setBold(true); // Bold untuk kolom stok dan subtotal pada baris total
    }

    protected function totalRow()
    {
        // Menghitung total stok dan subtotal
        $totalStok = $this->produkWithStok->sum('jumlah') ?? 0;
        $totalSubTotal = $this->produkWithStok->sum('subTotal') ?? 0;

        return [
            'no' => '', // Kosongkan kolom No
            'kode_produk' => 'Total', // Label untuk total
            'nama_produk' => '', // Kosongkan
            'stok' => $totalStok, // Total stok
            'harga_jual' => '', // Kosongkan
            'subtotal' => $totalSubTotal, // Total subtotal
        ];
    }

    public function title(): string
    {
        return 'Laporan Stok Produk';
    }
}
