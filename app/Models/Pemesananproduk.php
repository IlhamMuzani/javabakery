<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pemesananproduk extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'kode_pemesanan',
        'nama_pelanggan',
        'kode_pelanggan',
        'alamat',
        'telp',
        'kategori',
        'sub_total',
        'tanggal',
        'qrcode_pemesanan',
        'tanggal_pemesanan',
        'cabang',
        'catatan',
        'nama_penerima',
        'telp_penerima',
        'alamat_penerima',
        'tanggal_kirim',
        'waktu_kirim',
        'toko_id',
        'status',
        'kasir',
        'tanggaL_akhir',
        'metode_id',
        'sub_totalasli',
        'total_fee',
        'keterangan',
        'nominal_diskon',
  
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
        return $this->hasMany(Detailpemesananproduk::class, 'pemesananproduk_id');
    }
    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }
    public function dppemesanan()
    {
        return $this->hasOne(Dppemesanan::class, 'pemesananproduk_id');
    }
    
    public function dppemesanans()
    {
        return $this->hasOne(DpPemesanan::class, 'pemesananproduk_id', 'id');
    }
    
    public function metodepembayaran()
{
    return $this->belongsTo(Metodepembayaran::class, 'metode_id');
}


}
