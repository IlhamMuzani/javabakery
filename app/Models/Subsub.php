<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsub extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
      'nama', 'subklasifikasi_id'
      
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function getId()
    {
        return $getId = DB::table('subsubs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function Klasifikasis()
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function Subklasifikasi()
    {
        return $this->belongsTo(Subklasifikasi::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
    public function input()
    {
        return $this->hasMany(Input::class);
    }
}