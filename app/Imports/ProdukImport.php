<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Stok_tokobanjaran;
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;

class ProdukImport implements ToModel, WithHeadingRow
{
    use Importable;


    public function model(array $row)
    {
        // Pastikan harga tidak null
        $harga = isset($row['harga']) ? $row['harga'] : 0;
    
        $kode_produk = $this->generateKodeProduk();
    
        // Simpan data ke tabel produk
        $produk = Produk::create([
            'kode_lama' => $row['kode_lama'],
            'nama_produk' => $row['nama_produk'],
            'klasifikasi_id' => $row['klasifikasi_id'],
            'subklasifikasi_id' => $row['subklasifikasi_id'],
            'satuan' => $row['satuan'],
            'harga' => $harga,
            'gambar' => isset($row['gambar']) ? $row['gambar'] : null,
            'diskon' => 0,
            'kode_produk' => $kode_produk,
            'qrcode_produk' => 'https://javabakery.id/produk/' . $kode_produk,
            // 'tanggal' => now(),
        ]);
    
        // Simpan data ke tabel toko
        TokoSlawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $harga,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_diskon_slw' => 10,
            'non_harga_slw' => $harga,
            'non_diskon_slw' => 0,
        ]);
    
        Tokobanjaran::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_bnjr' => $harga,
            'member_diskon_bnjr' => 10,
            'non_harga_bnjr' => $harga,
            'non_diskon_bnjr' => 0,
        ]);
    
        Tokotegal::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_tgl' => $harga,
            'member_diskon_tgl' => 10,
            'non_harga_tgl' => $harga,
            'non_diskon_tgl' => 0,
        ]);
    
        Tokopemalang::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_pml' => $harga,
            'member_diskon_pml' => 10,
            'non_harga_pml' => $harga,
            'non_diskon_pml' => 0,
        ]);
    
        Tokobumiayu::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_bmy' => $harga,
            'member_diskon_bmy' => 10,
            'non_harga_bmy' => $harga,
            'non_diskon_bmy' => 0,
        ]);
    
        Tokocilacap::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_clc' => $harga,
            'member_diskon_clc' => 10,
            'non_harga_clc' => $harga,
            'non_diskon_clc' => 0,
        ]);
    
        // Simpan data ke tabel stok_tokobanjaran dengan jumlah 0
        Stok_tokobanjaran::create([
            'produk_id' => $produk->id,
            'jumlah' => 0,
        ]);
    
          // Simpan data ke tabel stokpesanan_tokobanjaran dengan nilai yang sama seperti stok_tokobanjaran
        Stokpesanan_tokobanjaran::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);
        return $produk;
    }
    
    private function generateKodeProduk()
    {
        // Ambil produk terakhir berdasarkan urutan kode_produk
        $lastBarang = Produk::orderBy('kode_produk', 'desc')->first();
    
        if (!$lastBarang) {
            $num = 1; // Jika tidak ada produk sebelumnya, mulai dari 1
        } else {
            $lastCode = $lastBarang->kode_produk;
            $num = (int) substr($lastCode, strlen('PR')) + 1; // Ambil angka setelah prefix
        }
    
        // Format nomor produk dengan 6 digit
        $formattedNum = sprintf("%06d", $num);
        $prefix = 'PR'; // Prefix kode produk
        $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor
    
        return $newCode;
    }
    
    

}
