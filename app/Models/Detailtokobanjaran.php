<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detailtokobanjaran extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
   'tokobanjaran_id',
   'produk_id',
   'member_harga',
   'non_member_harga',
   'member_diskon',
   'non_member_diskon',
   'harga_diskon_member',
   'harga_diskon_non',
   'harga_awal',
   'diskon_awal',
   'tanggal_perubahan',
   'member_hargaawal',
   'non_member_hargaawal',
   'member_diskonawal',
   'non_member_diskonawal',

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
        return $getId = DB::table('detailtokoslawi')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function tokobanjaran()
    {
        return $this->belongsTo(Tokobanjaran::class, 'tokobanjaran_id');
    }

        
}