<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Models\Pelanggan;
use App\Models\Pemesananproduk;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();



    Route::get('/subcategories/{category}', [SubcategoryController::class, 'getSubcategoriesByCategory']);
    

});

Route::get('/getCustomerData', function (Request $request) {
    // Mengambil nilai parameter 'barcode' dari query string
    $barcode = $request->query('barcode');
    
    // Mencari customer di tabel 'pelanggans' berdasarkan barcode
    $customer = Pelanggan::where('barcode', $barcode)->first();
    
    if ($customer) {
        // Mengembalikan respons JSON dengan data customer
        return response()->json([
            'success' => true,
            'customer' => [
                'nama' => $customer->nama_pelanggan,
                'telp' => $customer->telp,
                'alamat' => $customer->alamat,
            ]
        ]);
    } else {
        // Mengembalikan respons JSON dengan pesan kesalahan
        return response()->json([
            'success' => false,
            'message' => 'Customer not found'
        ]);
    }
});