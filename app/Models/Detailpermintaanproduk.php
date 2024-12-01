<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Detailpermintaanproduk extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'permintaanproduk_id',
        'produk_id',
        'toko_id',
        'jumlah',
        'status',
        'tanggal_permintaan',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getId()
    {
        return $getId = DB::table('inputs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function subsub()
    {
        return $this->belongsTo(Subsub::class);
    }
    
    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }
    

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function permintaanproduk()
    {
        return $this->belongsTo(PermintaanProduk::class, 'permintaanproduk_id');
    }

}
