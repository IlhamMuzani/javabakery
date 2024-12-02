<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Penjualanproduk extends Model
{
    use HasFactory;


    protected $fillable = [
        'kode_penjualan',
        'kode_pelanggan',
        'kode_pelangganlama',
        'nama_pelanggan',
        'alamat',
        'kasir',
        'metode_id',
        'total_fee',
        'keterangan',
        'telp',
        'kategori',
        'sub_total',
        'sub_totalasli',
        'bayar',
        'dp_pemesanan',
        'nominal_diskon',
        'kembali',
        'tanggal',
        'qrcode_penjualan',
        'tanggal_penjualan',
        'cabang',
        'catatan',
        'metode_bayar',
        'nama_penerima',
        'telp_penerima',
        'alamat_penerima',
        'tanggal_kirim',
        'toko_id',
        'dppemesanan_id',
        'status',
        'tanggaL_akhir',

    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getId()
    {
        return $getId = DB::table('inputs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function subsub()
    {
        return $this->belongsTo(Subsub::class);
    }

    public function details()
    {
        return $this->hasMany(Detailbarangjadi::class, 'input_id', 'id');
    }
    public function detailpenjualanproduk()
    {
        return $this->hasMany(Detailpenjualanproduk::class, 'penjualanproduk_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(Metodepembayaran::class, 'metode_id');
    }

    public function pelunasan()
    {
        return $this->hasOne(Pelunasan::class);
    }

    public function dppemesanan()
    {
        return $this->belongsTo(Dppemesanan::class, 'dppemesanan_id');
    }
}
