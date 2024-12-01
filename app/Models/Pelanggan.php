<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
        'kode_pelanggan',
        'kode_pelangganlama',
        'nama_pelanggan',
        'qrcode_pelanggan',
        'alamat',
        'telp',
        'gender',
        'email',
        'pekerjaan',
        'kategori',
        'gambar_ktp',
        'tanggal_lahir',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable('*');
    // }
    
    // public function penjualan()
    // {
    //     return $this->hasMany(Penjualan::class);
    // }

    public static function getId()
    {
        return $getId = DB::table('pelanggans')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
