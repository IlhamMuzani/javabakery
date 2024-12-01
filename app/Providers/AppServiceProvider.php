<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\InqueryPemesananProduk;
use App\Models\InqueryPermintaanProduk;
use App\Models\InqueryPengirimanBarangJadi;
use App\Models\Pemesananproduk;
use App\Models\Pengiriman_barangjadi;
use App\Models\Permintaanproduk;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $unpostCountPemesananProduk = Pemesananproduk::where('status', 'unpost')->count();
            $unpostCountPermintaanProduk = Permintaanproduk::where('status', 'unpost')->count();
            $unpostCountPengirimanBarangJadi = Pengiriman_barangjadi::where('status', 'unpost')->count();
            // Tambahkan perhitungan untuk inquery lain sesuai kebutuhan
    
            // Bagikan variabel ke semua view
            $view->with([
                'unpostCountPemesananProduk' => $unpostCountPemesananProduk,
                'unpostCountPermintaanProduk' => $unpostCountPermintaanProduk,
                'unpostCountPengirimanBarangJadi' => $unpostCountPengirimanBarangJadi,
                // Tambahkan variabel lain sesuai kebutuhan
            ]);
        });
    }
}
