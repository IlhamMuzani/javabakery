<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tokotegal extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
    'produk_id', 
    'harga_awal',
    'diskon_awal',
    'member_harga_tgl', 
    'non_harga_tgl', 
    'member_diskon_tgl', 
    'non_diskon_tgl', 
   

  
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
        return $getId = DB::table('tokotegals')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function hargajual()
    {
        return $this->belongsTo(Hargajual::class, 'hargajual_id', 'id');
    }
        
}