<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Detailhasilproduksi extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'hasilproduksi_id',
        'produk_id',
        'kode_lama',
        'nama_produk',
        'jumlah',
        'toko_id',
        'status',
        'kategori',
        'realisasi',
  
  
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getId()
    {
        return $getId = DB::table('inputs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function subsub()
    {
        return $this->belongsTo(Subsub::class);
    }

    public function input()
    {
        return $this->belongsTo(Input::class, 'input_id', 'id');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function pemesananproduk()
    {
        return $this->belongsTo(Pemesananproduk::class, 'pemesananproduk_id');
    }
    

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function hasilproduksi()
    {
        return $this->belongsTo(Hasilproduksi::class, 'hasilproduksi_id'); 
    }
}
