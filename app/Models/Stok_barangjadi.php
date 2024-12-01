<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Stok_barangjadi extends Model
{
    use HasFactory;

    
    protected $fillable = [
     
        'kode_input',
        'tanggal_input',
        'stok',
        'klasifikasi_id',
        'produk_id',
        'status',
     
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

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function detailpermintaanproduks()
    {
        return $this->hasMany(Detailpermintaanproduk::class, 'permintaanproduk_id');
    }
    public function klasifikasi()
    {
        return $this->hasMany(Klasifikasi::class, 'produk_id');
    }

    public function detail_stokbarangjadi()
{
    return $this->hasMany(Detail_stokbarangjadi::class, 'stokbarangjadi_id');
}

public function produks()
{
    return $this->hasMany(Produk::class, 'stok_barangjadi_id'); // Gantilah 'stok_barangjadi_id' dengan nama foreign key yang benar
}
}
