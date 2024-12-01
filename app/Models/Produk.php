<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
        'kode_produk',
        'kode_lama',
        'klasifikasi_id',
        'subklasifikasi_id',
        'nama_produk',
        'satuan',
        'harga',
        'diskon',
        'gambar',
        'qrcode_produk',
  
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable('*');
    // }
    
    public static function getId()
    {
        return $getId = DB::table('produks')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function hargajual()
    {
        return $this->hasMany(Hargajual::class);
    }

    public function tokoslawi()
    {
        return $this->hasMany(Tokoslawi::class);
    }
    public function tokobanjarans()
    {
        return $this->hasOne(Tokobanjaran::class, 'produk_id', 'id');
    }
    
    public function tokobanjaran()
    {
        return $this->hasMany(Tokobanjaran::class);
    }

    public function stok_tokobanjaran()
    {
        return $this->hasOne(Stok_tokobanjaran::class, 'produk_id');
    }
    public function stok_tokotegal()
    {
        return $this->hasOne(Stok_tokotegal::class, 'produk_id');
    }
    public function stok_tokoslawi()
    {
        return $this->hasOne(Stok_tokoslawi::class, 'produk_id');
    }
    public function stok_tokopemalang()
    {
        return $this->hasOne(Stok_tokopemalang::class, 'produk_id');
    }
    public function stokpesanan_tokobanjaran()
    {
        return $this->hasOne(Stokpesanan_tokobanjaran::class, 'produk_id');
    }
    public function stokpesanan_tokotegal()
    {
        return $this->hasOne(Stokpesanan_tokotegal::class, 'produk_id');
    }
    public function tokobumiayu()
    {
        return $this->hasMany(Tokobumiayu::class);
    }

    public function stok_tokobumiayu()
    {
        return $this->hasOne(Stok_tokobumiayu::class, 'produk_id');
    }
    public function stok_tokocilacap()
    {
        return $this->hasOne(Stok_tokocilacap::class, 'produk_id');
    }
    public function stokpesanan_tokobumiayu()
    {
        return $this->hasOne(Stokpesanan_tokobumiayu::class, 'produk_id');
    }
    public function stokpesanan_tokopemalang()
    {
        return $this->hasOne(Stokpesanan_tokopemalang::class, 'produk_id');
    }
    public function stokpesanan_tokoslawi()
    {
        return $this->hasOne(Stokpesanan_tokoslawi::class, 'produk_id');
    }
    public function stokpesanan_tokocilacap()
    {
        return $this->hasOne(Stokpesanan_tokocilacap::class, 'produk_id');
    }
    public function tokotegal()
    {
        return $this->hasMany(Tokotegal::class);
    }
    public function tokopemalang()
    {
        return $this->hasMany(Tokopemalang::class);
    }

    public function tokocilacap()
    {
        return $this->hasMany(Tokocilacap::class);
    }
    public function detailtoko()
    {
        return $this->hasMany(Detailtoko::class);
    }
    public function detailtokoslawi()
    {
        return $this->hasMany(Detailtokoslawi::class,  'produk_id', 'id');
    }
    public function detailbarangjadi()
    {
        return $this->hasMany(Detailbarangjadi::class,  'produk_id', 'id');
    }
    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }
    public function permintaanproduk()
    {
        return $this->hasMany(Permintaanproduk::class);
    }
    public function subklasifikasi()
    {
        return $this->belongsTo(Subklasifikasi::class, 'subklasifikasi_id');
    }
    public function stok_barangjadi()
    {
        return $this->hasMany(Stok_barangjadi::class);
    }

    public function stok_barangjadii()
    {
        return $this->hasMany(Stok_barangjadi::class, 'produk_id');
    }

    public function detailStokBarangJadi()
    {
        return $this->hasMany(Detail_stokbarangjadi::class, 'produk_id');
    }

    public function returBarangJadi()
{
    return $this->hasMany(Retur_barangjadi::class, 'produk_id');
}

}