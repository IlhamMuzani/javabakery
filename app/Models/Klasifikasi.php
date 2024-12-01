<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Klasifikasi extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
        'nama',
        'kode_klasifikasi',
        'qrcode_klasifikasi'
      
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
        return $getId = DB::table('klasifikasis')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function subklasifikasi()
    {
        return $this->hasMany(Subklasifikasi::class, 'klasifikasi_id');
    }
    
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
    
    public function produks()
    {
        return $this->hasMany(Produk::class, 'klasifikasi_id');
    }

}