<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AksesController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $aksess = User::where(['cek_hapus' => 'tidak'])->get();
            return view('admin/akses.index', compact('aksess'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function create()
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $departemens = Departemen::all();
            $karyawans = Karyawan::where(['status' => 'null'])->get();
            return view('admin/user.create', compact('departemens', 'karyawans'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }

    public function karyawan($id)
    {
        $user = Karyawan::where('id', $id)->first();

        return json_decode($user);
    }

    public function edit($id)
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $akses = User::where('id', $id)->first();
            return view('admin/akses.update', compact('akses'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }


    public function access($id)
    {
        if (auth()->check() && auth()->user()->menu['akses']) {

            $menus = array(
                'akses',
                'karyawan',
                'user',
                'departemen',
                'barang',
                'klasifikasi',
                'pelanggan',
                'input',
                'produk',
                'toko',
                'klasifikasi',
                'metode pembayaran',
                'data deposit',
                'data stokbarangjadi',
                'data stokretur',
                'pemesanan produk',
                'penjualan produk',
                'harga jual',
                'permintaan produk',
                'stok barangjadi',
                'pengiriman barangjadi',
                'retur barangjadi',
                'pemindahan barangjadi',
                'pemusnahan barangjadi',
                'estimasi produksi',
                'inquery pemesananproduk',
                'inquery penjualanproduk',
                'inquery perubahanharga',
                'inquery permintaanproduk',
                'inquery stokbarangjadi',
                'inquery pengirimanbarangjadi',
                'inquery returbarangjadi',
                'inquery pemusnahanbarangjadi',
                'inquery pemindahanbarangjadi',
                'inquery estimasiproduksi',
                'inquery deposit',
                'inquery hasilpenjualan',
                'laporan pemesananproduk',
                'laporan penjualanproduk',
                'laporan perubahanharga',
                'laporan permintaanproduk',
                'laporan deposit',
                'laporan stokbarangjadi',
                'laporan pengirimanbarangjadi',
                'laporan returbarangjadi',
                'laporan pemusnahanbarangjadi',
                'laporan pemindahanbarangjadi',
                'laporan estimasiproduksi',
                'laporan stoktoko',
                'laporan hasilpenjualan',   
                //banjaran
                'stok tokobanjaran', 
                'pemesanan banjaran', 
                'penjualan banjaran', 
                'pelunasan banjaran', 
            );

            $akses = User::where('id', $id)->first();
            $level = $akses->level; // Misalnya level disimpan dalam kolom 'level'

            return view('admin.akses.access', compact('akses', 'menus', 'level'));
        } else {
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        }
    }


    public function access_user(Request $request)
    {
        $menus = array(
               'akses',
                'karyawan',
                'user',
                'departemen',
                'barang',
                'klasifikasi',
                'pelanggan',
                'input',
                'produk',
                'toko',
                'klasifikasi',
                'metode pembayaran',
                'data deposit',
                'data stokbarangjadi',
                'data stokretur',
                'pemesanan produk',
                'penjualan produk',
                'harga jual',
                'permintaan produk',
                'stok barangjadi',
                'pengiriman barangjadi',
                'retur barangjadi',
                'pemindahan barangjadi',
                'pemusnahan barangjadi',
                'estimasi produksi',
                'inquery pemesananproduk',
                'inquery penjualanproduk',
                'inquery perubahanharga',
                'inquery permintaanproduk',
                'inquery stokbarangjadi',
                'inquery pengirimanbarangjadi',
                'inquery returbarangjadi',
                'inquery pemusnahanbarangjadi',
                'inquery pemindahanbarangjadi',
                'inquery estimasiproduksi',
                'inquery deposit',
                'inquery hasilpenjualan',
                'laporan pemesananproduk',
                'laporan penjualanproduk',
                'laporan perubahanharga',
                'laporan permintaanproduk',
                'laporan deposit',
                'laporan stokbarangjadi',
                'laporan pengirimanbarangjadi',
                'laporan returbarangjadi',
                'laporan pemusnahanbarangjadi',
                'laporan pemindahanbarangjadi',
                'laporan estimasiproduksi',
                'laporan stoktoko',
                'laporan hasilpenjualan',


                //banjaran
                'stok tokobanjaran', 
                'pemesanan banjaran', 
                'penjualan banjaran', 
                'pelunasan banjaran',
        );


        $data = array();
        // Inisialisasi semua nilai menu menjadi false
        foreach ($menus as $menu) {
            $data[$menu] = false;
        }

        // Jika ada data yang dipilih, maka atur nilai menu menjadi true
        if ($request->has('menu') && is_array($request->menu)) {
            foreach ($request->menu as $selectedMenu) {
                if (in_array($selectedMenu, $menus)) {
                    $data[$selectedMenu] = true;
                }
            }
        }

        User::where('id', $request->id)->update([
            'menu' => json_encode($data),
            'tanggal_awal' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect('admin/akses')->with('success', 'Berhasil menambah Akses');
    }


    public function destroy($id)
    {

        $user = User::find($id);
        Karyawan::where('id', $user->karyawan_id)->update([
            'status' => 'null'
        ]);
        $user->delete();

        return redirect('admin/user')->with('success', 'Berhasil menghapus user');
    }
}