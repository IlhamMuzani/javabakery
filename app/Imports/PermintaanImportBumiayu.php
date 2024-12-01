<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Detailpermintaanproduk;
use App\Models\Permintaanproduk;
use Carbon\Carbon;


class PermintaanImportBumiayu implements ToModel, WithHeadingRow
{
    use Importable;
    private $lastPermintaanProdukId;
    private $kodePermintaan;

    public function model(array $row)
    {
        if (!$this->kodePermintaan) {
            $this->kodePermintaan = $this->generateKodePermintaan();

            // Buat entri baru di tabel permintaanProduks
            $permintaanProduk = PermintaanProduk::create([
                'kode_permintaan' => $this->kodePermintaan,
                'status' => 'unpost',
                'qrcode_permintaan' => 'https://javabakery.id/permintaan_produk/' . $this->kodePermintaan,
            ]);

            // Simpan ID permintaan produk yang terakhir
            $this->lastPermintaanProdukId = $permintaanProduk->id;
        }

        // Temukan produk berdasarkan kode_lama
        $produk = Produk::where('kode_lama', $row['kode_lama'])->first();
        if ($produk) {
            $produkId = $produk->id;
        } else {
            // Jika produk tidak ditemukan, skip baris atau lakukan penanganan kesalahan
            return null;
        }

        // Pastikan nilai 'jumlah' adalah angka dan bukan referensi sel atau formula
        $jumlah = is_numeric($row['jumlah']) ? $row['jumlah'] : 0; // Default ke 0 jika tidak valid

        // Jika jumlah bernilai 0, jangan simpan ke database
        if ($jumlah == 0) {
            return null; // Melewati penyimpanan data jika jumlah adalah 0
        }

        // Simpan detail permintaan untuk setiap produk
        Detailpermintaanproduk::create([
            'permintaanproduk_id' => $this->lastPermintaanProdukId,
            'produk_id' => $produkId,
            'toko_id' => '5',
            'jumlah' => $jumlah, // Simpan jumlah yang sudah diverifikasi
            'status' => 'unpost',
            'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),
        ]);

        // Kembalikan entri permintaan produk (opsional)
        return null;
    }

    public function getLastPermintaanProdukId()
    {
        return $this->lastPermintaanProdukId;
    }

    private function generateKodePermintaan()
{
    $prefix = 'JLF';
    $year = date('y'); // Dua digit terakhir dari tahun
    $monthDay = date('dm'); // Format bulan dan hari: MMDD
    
    // Cari permintaan terakhir berdasarkan awalan kode_permintaan dan tanggal hari ini
    $lastBarang = Permintaanproduk::where('kode_permintaan', 'LIKE', $prefix . $monthDay . $year . '%')
                                  ->orderBy('kode_permintaan', 'desc')
                                  ->first();

    if (!$lastBarang) {
        // Jika belum ada permintaan, mulai dari urutan 001
        $num = 1;
    } else {
        // Ambil angka urutan setelah kode permintaan (misal: JLC061124001 -> ambil 001)
        $lastCode = $lastBarang->kode_permintaan;
        $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Ambil urutan terakhir
        $num = $lastNum + 1; // Tambah 1 untuk urutan berikutnya
    }

    // Format nomor urut dengan 3 digit (contoh: 001, 002, 003, dst)
    $formattedNum = sprintf("%03d", $num);

    // Gabungkan prefix, tanggal, tahun, dan nomor urut
    $newCode = $prefix . $monthDay . $year . $formattedNum;

    return $newCode;
}



}
