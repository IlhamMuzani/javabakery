<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detailtoko extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
   'tokoslawi_id',
   'tokobenjaran_id',
   'tokotegal_id',
   'tokopemalang_id',
   'tokobumiayu_id',
   'tokocilacap_id',
   'member_harga',
   'non_member_harga',
   'member_diskon',
   'non_member_diskon',
   'harga_diskon_member',
   'harga_diskon_non',
   'harga_awal',
   'diskon_awal',
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
        return $getId = DB::table('detailtoko')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

        
}