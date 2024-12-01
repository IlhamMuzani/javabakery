<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Dppemesanan extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'kode_dppemesanan',
        'pemesananproduk_id',
        'dp_pemesanan',
        'kekurangan_pemesanan',
        'tanggal_dp',
        
  
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
    public function detailpemesananproduk()
    {
        return $this->hasMany(Detailpemesananproduk::class, 'pemesananproduk_id', 'pemesananproduk_id','dppemesanan_id');
    }
    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }
    public function pemesananproduk()
    {
        return $this->belongsTo(Pemesananproduk::class, 'pemesananproduk_id');
    }
    public function dppemesanans()
{
    return $this->hasMany(Dppemesanan::class, 'pelunasan_id'); // Sesuaikan nama kolom foreign key jika perlu
}

public function pelunasan()
{
    return $this->hasMany(Pelunasan::class);
}

// Dppemesanan.php (model)
public function penjualanproduk()
{
    return $this->hasMany(Penjualanproduk::class, 'dppemesanan_id');
}

}
