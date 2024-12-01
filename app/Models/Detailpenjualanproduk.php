<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Detailpenjualanproduk extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'penjualanproduk_id',
        'produk_id',
        'kode_produk',
        'kode_lama',
        'nama_produk',
        'jumlah',
        'diskon',
        'harga',
        'total',
        'totalasli',
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

    public function input()
    {
        return $this->belongsTo(Input::class, 'input_id', 'id');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function penjualanproduk()
    {
        return $this->belongsTo(Penjualanproduk::class, 'penjualanproduk_id');
    }
    
}
