<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Setoran_penjualan extends Model
{
    use HasFactory;


    protected $fillable = [
        'tanggal_penjualan',
        'penjualan_kotor',
        'diskon_penjualan',
        'penjualan_bersih',
        'deposit_keluar',
        'deposit_masuk',
        'total_penjualan',
        'mesin_edc',
        'qris',
        'gobiz',
        'transfer',
        'total_setoran',
        'tanggal_setoran',
        'tanggal_setoran2',
        'nominal_setoran',
        'nominal_setoran2',
        'plusminus',
        'toko_id',
        'status',
        'no_fakturpenjualantoko',
        'tanggal_awal',
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


    public function pelunasan_penjualan()
    {
        return $this->hasMany(Pelunasan_penjualan::class);
    }
}