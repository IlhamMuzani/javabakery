<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tokobanjaran extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
    'produk_id', 
    'hargajual_id', 
    'harga_awal', 
    'diskon_awal', 
    'member_harga_bnjr', 
    'non_harga_bnjr', 
    'member_diskon_bnjr', 
    'non_diskon_bnjr', 
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
        return $getId = DB::table('tokobanjarans')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function tokos()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function detailtokobanjaran()
{
    return $this->hasMany(Detailtokobanjaran::class);
}

        
}