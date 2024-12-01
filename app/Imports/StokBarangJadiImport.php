<?php

use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StokBarangJadiImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $kode = app('App\Http\Controllers\StokBarangJadiController')->kode();

        foreach ($rows as $row) {
            $produkId = $row[0];
            $stok = $row[1];
            $klasifikasiId = $row[2];

            $stokBarangJadi = Stok_barangjadi::create([
                'produk_id' => $produkId,
                'klasifikasi_id' => $klasifikasiId,
                'stok' => $stok,
                'status' => 'unpost',
                'kode_input' => $kode,
                'tanggal_input' => Carbon::now('Asia/Jakarta'),
            ]);

            $existingDetail = Detail_stokbarangjadi::where('produk_id', $produkId)
                ->where('klasifikasi_id', $klasifikasiId)
                ->where('status', 'unpost')
                ->first();

            if ($existingDetail) {
                $existingDetail->stok += $stok;
                $existingDetail->save();
            } else {
                Detail_stokbarangjadi::create([
                    'stokbarangjadi_id' => $stokBarangJadi->id,
                    'produk_id' => $produkId,
                    'klasifikasi_id' => $klasifikasiId,
                    'stok' => $stok,
                    'status' => 'unpost',
                    'kode_input' => $kode,
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
            }
        }
    }
}

