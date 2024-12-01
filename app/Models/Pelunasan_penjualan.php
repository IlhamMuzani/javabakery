<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pelunasan_penjualan extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'tanggal_penjualan',
        'faktur_pelunasanpenjualan',
        'penjualan_kotor1',
        'diskon_penjualan1',
        'penjualan_bersih1',
        'deposit_keluar1',
        'deposit_masuk1',
        'total_penjualan1',
        'mesin_edc1',
        'qris1',
        'gobiz1',
        'transfer1',
        'total_setoran1',
        'tanggal_setoran',
        'tanggal_setoran2',
        'nominal_setoran',
        'nominal_setoran2',
        'plusminus',
        'toko_id',
        'status',
        'no_fakturpenjualantoko',
        'penjualan_selisih',
        'diskon_selisih',
        'penjualanbersih_selisih',
        'depositkeluar_selisih',
        'depositmasuk_selisih',
        'totalpenjualan_selisih',
        'mesinedc_selisih',
        'qris_selisih',
        'gobiz_selisih',
        'transfer_selisih',
        'totalsetoran_selisih',
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
