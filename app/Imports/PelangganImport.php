<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Shared\Date; // Tambahkan ini



class PelangganImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $kode = $this->kode();

        return new Pelanggan([
            'nama_pelanggan'  => $row['nama_pelanggan'],  // Wajib diisi
            'kode_pelangganlama'  => $row['kode_pelangganlama'],  // Wajib diisi
            'alamat'          => $row['alamat'] ?? null,  // Nullable
            'telp'            => $row['telp'] ?? null,            // Wajib diisi
            'tanggal_awal'    => isset($row['tanggal_awal']) ? Date::excelToDateTimeObject($row['tanggal_awal'])->format('Y-m-d') : null,   // Nullable
            'tanggal_akhir'   => isset($row['tanggal_akhir']) ? Date::excelToDateTimeObject($row['tanggal_akhir'])->format('Y-m-d') : null,  // Wajib diisi
            'kode_pelanggan'  => $kode,
            'qrcode_pelanggan'=> 'https://javabakery.id/pelanggan/' . $kode,
        ]);
    }

    public function kode()
    {
        $pelanggan = Pelanggan::all();
        if ($pelanggan->isEmpty()) {
            $num = "000001";
        } else {
            $id = Pelanggan::orderBy('id', 'desc')->first();
            $idlm = $id->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'JB';
        return $data . $num;
    }
}


// class PelangganImport implements ToModel, WithHeadingRow, WithChunkReading {
    
//     use Importable;

//     public function model(array $row)
//     {
//         // Periksa apakah baris kosong (misalnya, kolom 'kode_lama' tidak ada)
//         if (empty($row['kode_lama']) || empty($row['nama_pelanggan']) || empty($row['alamat'])) {
//             return null; // Abaikan baris ini
//         }

//         // Generate kode pelanggan seperti di script 1
//         $kode = $this->generateKodePelanggan();

//         // Convert Excel serial date to Carbon date
//         $tanggalAkhir = $this->convertExcelDateToCarbon($row['tanggal_akhir']);

//         // Bersihkan kolom telp untuk hanya menyimpan angka
//         $telp = $this->cleanPhoneNumber($row['telp']);

//         // Simpan data ke tabel pelanggan
//         $pelanggan = Pelanggan::create([
//             'kode_lama' => $row['kode_lama'],
//             'nama_pelanggan' => $row['nama_pelanggan'],
//             'alamat' => $row['alamat'],
//             'telp' => $telp,
//             'tanggal_akhir' => $tanggalAkhir,
//             'kode_pelanggan' => $kode,
//             'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode,
//             'status' => 'null',
//             'tanggal' => Carbon::now('Asia/Jakarta'),
//         ]);

//         return $pelanggan;
//     }

//     private function generateKodePelanggan()
//     {
//         // Ambil pelanggan terakhir berdasarkan urutan id
//         $lastPelanggan = Pelanggan::orderBy('id', 'desc')->first();

//         if (!$lastPelanggan) {
//             $num = 1; // Jika tidak ada pelanggan sebelumnya, mulai dari 1
//         } else {
//             $lastCode = $lastPelanggan->id;
//             $num = $lastCode + 1; // Increment ID
//         }

//         // Format nomor pelanggan dengan 6 digit
//         $formattedNum = sprintf("%06d", $num);
//         $prefix = 'JB'; // Prefix kode pelanggan
//         $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor

//         return $newCode;
//     }

//     private function convertExcelDateToCarbon($excelDate)
//     {
//         if (is_numeric($excelDate)) {
//             // Excel serial date format to Carbon date
//             return Carbon::createFromFormat('Y-m-d', Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2)->format('Y-m-d'));
//         }

//         return null; // Handle cases where the date is not numeric
//     }

//     private function cleanPhoneNumber($phoneNumber)
//     {
//         // Hapus semua karakter non-numerik
//         return preg_replace('/\D/', '', $phoneNumber);
//     }

//     public function chunkSize(): int
//     {
//         return 100; // Jumlah baris per chunk
//     }
// }





