<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tokoslawi extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
    'produk_id', 
    'hargajual_id', 
    'harga_awal', 
    'diskon_awal', 
    'member_harga_slw', 
    'non_harga_slw', 
    'member_diskon_slw', 
    'non_diskon_slw', 
    'tanggal_perubahan', 
   

  
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
        return $getId = DB::table('tokoslawis')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function tokos()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

        
}