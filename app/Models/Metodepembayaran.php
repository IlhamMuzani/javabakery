<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Metodepembayaran extends Model
{
    protected $table = 'metodepembayarans'; // Nama tabel

    use HasFactory;
    protected $fillable = [
        'id',
        'nama_metode',
        'kode_metode',
        'qrcode_metode',
        'fee',
        'keterangan',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getId()
    {
        return $getId = DB::table('karyawans')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }


    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function penjualanProduk()
{
    return $this->hasMany(Penjualanproduk::class, 'metode_id');
}


public function pelunasan()
{
    return $this->hasMany(Pelunasan::class, 'metode_id');
}
}