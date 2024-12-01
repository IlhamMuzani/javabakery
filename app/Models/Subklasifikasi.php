<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subklasifikasi extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
      'kategori_id', 'klasifikasi_id', 'nama'
      
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
        return $getId = DB::table('subklaisifiksis')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function Klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function Subsub()
    {
        return $this->hasMany(Subsub::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}