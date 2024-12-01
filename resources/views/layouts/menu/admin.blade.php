<style>
    .badge-small {
    font-size: 10px; /* Ukuran font badge kecil */
    margin-left: 10px; /* Jarak antara teks dan badge */
    padding: 2px 5px; /* Padding untuk badge */
    vertical-align: middle; /* Menyelaraskan badge dengan teks */
}

</style>
<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>
            Dashboard
        </p>
    </a>
</li>
<li class="nav-header">Search</li>

<div class="form-inline">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div>
<li class="nav-header">Menu</li>
<li
    class="nav-item {{ request()->is('admin/karyawan*') ||
    request()->is('admin/user*') ||
    request()->is('admin/akses*') ||
    request()->is('admin/departemen*')||
    request()->is('admin/pelanggan*')||
    // request()->is('admin/barang*')||
    request()->is('admin/produk*')||
    request()->is('admin/toko*')||
    // request()->is('admin/harga*')||
    request()->is('admin/klasifikasi*')||
    request()->is('admin/metode_pembayaran*')||
    request()->is('admin/input*')||
    request()->is('admin/data_deposit*')||
    request()->is('admin/data_stokbarangjadi*')||
    request()->is('admin/grafik_penjualan*')||
    request()->is('admin/stok_hasilproduksi*')||
    request()->is('admin/data_stokretur*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/karyawan*') ||
        request()->is('admin/user*') ||
        request()->is('admin/akses*') ||
        request()->is('admin/departemen*')||
        request()->is('admin/pelanggan*')||
        // request()->is('admin/barang*')||
        request()->is('admin/produk*')||
        request()->is('admin/toko*')||
        // request()->is('admin/harga*')||
        request()->is('admin/klasifikasi*')||
        request()->is('admin/metode_pembayaran*')||
        request()->is('admin/input*')||
        request()->is('admin/data_deposit*')||
        request()->is('admin/data_stokbarangjadi*')||
        request()->is('admin/grafik_penjualan*')||
        request()->is('admin/stok_hasilproduksi*')||
        request()->is('admin/data_stokretur*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">MASTER</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @if (auth()->check() && auth()->user()->menu['karyawan'])
        <li class="nav-item">
            <a href="{{ url('admin/karyawan') }}"
                class="nav-link {{ request()->is('admin/karyawan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Karyawan</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['user'])
        <li class="nav-item">
            <a href="{{ url('admin/user') }}" class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data User</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['akses'])
        <li class="nav-item">
            <a href="{{ url('admin/akses') }}" class="nav-link {{ request()->is('admin/akses*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Hak Akses</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['departemen'])
        <li class="nav-item">
            <a href="{{ url('admin/departemen') }}"
                class="nav-link {{ request()->is('admin/departemen*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Departemen</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['pelanggan'])
        <li class="nav-item">
            <a href="{{ url('admin/pelanggan') }}"
                class="nav-link {{ request()->is('admin/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['produk'])
        <li class="nav-item">
            <a href="{{ url('admin/produk') }}"
                class="nav-link {{ request()->is('admin/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['toko'])
        <li class="nav-item">
            <a href="{{ url('admin/toko') }}"
                class="nav-link {{ request()->is('admin/toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Toko</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['klasifikasi'])
        <li class="nav-item">
            <a href="{{ url('admin/klasifikasi') }}"
                class="nav-link {{ request()->is('admin/klasifikasi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Klasifikasi</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['metode pembayaran'])
        <li class="nav-item">
            <a href="{{ url('admin/metode_pembayaran') }}"
                class="nav-link {{ request()->is('admin/metode_pembayaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Jenis Pembayaran</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['data deposit'])
        <li class="nav-item">
            <a href="{{ url('admin/data_deposit') }}"
                class="nav-link {{ request()->is('admin/data_deposit*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Deposit</p>
            </a>
        </li>
        @endif

        @if (auth()->check() && auth()->user()->menu['data stokbarangjadi'])
        <li class="nav-item">
            <a href="{{ url('admin/data_stokbarangjadi') }}"
                class="nav-link {{ request()->is('admin/data_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Barang Jadi</p>
            </a>
        </li>
        @endif

        {{-- @if (auth()->check() && auth()->user()->menu['data stokretur']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/data_stokretur') }}"
                class="nav-link {{ request()->is('admin/data_stokretur*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Retur</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['data stokretur']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/stok_hasilproduksi') }}"
                class="nav-link {{ request()->is('admin/stok_hasilproduksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Hasil Produksi</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['data stokretur']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/grafik_penjualan') }}"
                class="nav-link {{ request()->is('admin/grafik_penjualan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Grafik Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/pemesanan_produk*') ||
    request()->is('admin/penjualan_produk*')||
    request()->is('admin/penjualan_toko*')||
    request()->is('admin/hargajual*')|| 
    request()->is('admin/stok_barangjadi*')||  
    request()->is('admin/permintaan_produk*')|| 
    request()->is('admin/pengiriman_barangjadi*')|| 
    request()->is('admin/retur_barangjadi*')|| 
    request()->is('admin/pemindahan_barangjadi*')|| 
    request()->is('admin/pemusnahan_barangjadi*')|| 
    request()->is('admin/setoran_pelunasan*')|| 
    request()->is('admin/surat_hasilproduksi*')|| 
    request()->is('admin/surat_perintahproduksi*')|| 
    request()->is('admin/estimasi_produksi*') 
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/pemesanan_produk*') ||
        request()->is('admin/penjualan_produk*')||
        request()->is('admin/penjualan_toko*')||
        request()->is('admin/hargajual*') || 
        request()->is('admin/stok_barangjadi*')||  
        request()->is('admin/permintaan_produk*')||  
        request()->is('admin/pengiriman_barangjadi*')||  
        request()->is('admin/retur_barangjadi*')||  
        request()->is('admin/pemindahan_barangjadi*')||  
        request()->is('admin/pemusnahan_barangjadi*')||  
        request()->is('admin/setoran_pelunasan*')||  
        request()->is('admin/surat_hasilproduksi*')||  
        request()->is('admin/surat_perintahproduksi*')||  
        request()->is('admin/estimasi_produksi')  
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['pemesanan produk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/pemesanan_produk') }}"
                class="nav-link {{ request()->is('admin/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan produk']) --}}
        {{-- <li class="nav-item">
            <a href="{{ url('admin/penjualan_produk') }}"
                class="nav-link {{ request()->is('admin/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li> --}}
        {{-- @endif --}}

        <li class="nav-item">
            <a href="{{ url('admin/penjualan_toko') }}"
                class="nav-link {{ request()->is('admin/penjualan_toko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Toko</p>
            </a>
        </li>

        {{-- @if (auth()->check() && auth()->user()->menu['harga jual']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/hargajual') }}"
                class="nav-link {{ request()->is('admin/hargajual*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Perubahan Harga Jual</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['permintaan produk']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/permintaan_produk') }}"
                class="nav-link {{ request()->is('admin/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok barangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/stok_barangjadi') }}"
                class="nav-link {{ request()->is('admin/stok_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}
    
          {{-- @if (auth()->check() && auth()->user()->menu['pengiriman barangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/pengiriman_barangjadi') }}"
                class="nav-link {{ request()->is('admin/pengiriman_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pengiriman Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['retur barangjadi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/retur_barangjadi') }}"
                class="nav-link {{ request()->is('admin/retur_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Retur Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pemindahan barangjadi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/pemindahan_barangjadi') }}"
                class="nav-link {{ request()->is('admin/pemindahan_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pemusnahan barangjadi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/pemusnahan_barangjadi') }}"
                class="nav-link {{ request()->is('admin/pemusnahan_barangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemusnahan Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['estimasi produksi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/estimasi_produksi') }}"
                class="nav-link {{ request()->is('admin/estimasi_produksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Estimasi Produksi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['estimasi produksi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/surat_perintahproduksi') }}"
                class="nav-link {{ request()->is('admin/surat_perintahproduksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Surat Perintah Produksi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['estimasi produksi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/surat_hasilproduksi') }}"
                class="nav-link {{ request()->is('admin/surat_hasilproduksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Surat Hasil Produksi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['estimasi produksi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/setoran_pelunasan') }}"
                class="nav-link {{ request()->is('admin/setoran_pelunasan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>


<li
    class="nav-item {{ request()->is('admin/inquery_pemesananproduk*') ||
    request()->is('admin/inquery_penjualanproduk*')|| 
    request()->is('admin/inquery_perubahanharga*')|| 
    request()->is('admin/inquery_permintaanproduk*')|| 
    request()->is('admin/inquery_stokbarangjadi*')|| 
    request()->is('admin/inquery_pengirimanbarangjadi*')|| 
    request()->is('admin/inquery_pemindahanbarang*')|| 
    request()->is('admin/inquery_pemusnahanbarangjadi*')|| 
    request()->is('admin/inquery_deposit*')|| 
    request()->is('admin/inquery_hasilpenjualan*')|| 
    request()->is('admin/inquery_setoranpelunasan*')|| 
    request()->is('admin/inquery_hasilproduksi*')|| 
    request()->is('admin/inquery_penjualantoko*')|| 
    request()->is('admin/inquery_estimasiproduksi')  
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') ||
        request()->is('admin/inquery_penjualanproduk*')||
        request()->is('admin/inquery_perubahanharga*')||
        request()->is('admin/inquery_permintaanproduk*')||
        request()->is('admin/inquery_stokbarangjadi*')||
        request()->is('admin/inquery_pengirimanbarangjadi*')||
        request()->is('admin/inquery_pemindahanbarang*')||
        request()->is('admin/inquery_pemusnahanbarangjadi*')||
        request()->is('admin/inquery_deposit*')||
        request()->is('admin/inquery_hasilpenjualan*')||
        request()->is('admin/inquery_setoranpelunasan*')||
        request()->is('admin/inquery_hasilproduksi*')||
        request()->is('admin/inquery_penjualantoko*')||
        request()->is('admin/inquery_estimasiproduksi*') 
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">FINANCE</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        {{-- @if (auth()->check() && auth()->user()->menu['inquery pemesananproduk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery penjualanproduk'])
        <li class="nav-item">
            <a href="{{ url('admin/inquery_penjualanproduk') }}"
                class="nav-link {{ request()->is('admin/inquery_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery penjualanproduk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_penjualantoko') }}"
                class="nav-link {{ request()->is('admin/inquery_penjualantoko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Toko</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery perubahanharga']) --}}
        {{-- <li class="nav-item">
            <a href="{{ url('admin/inquery_perubahanharga') }}"
                class="nav-link {{ request()->is('admin/inquery_perubahanharga*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Perubahan Harga</p>
            </a>
        </li> --}}
        {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['inquery permintaanproduk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_permintaanproduk') }}" 
                class="nav-link {{ request()->is('admin/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; display: inline-block;">
                    Inquery Permintaan Produk
                </p>
                {{-- @if($unpostCountPermintaanProduk > 0)
                    <span class="badge badge-warning" style="margin-left: 5px;">{{ $unpostCountPermintaanProduk }}</span>
                @endif --}}
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery stokbarangjadi']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/inquery_stokbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_stokbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Stok Barang Jadi</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['inquery pengirimanbarangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_pengirimanbarangjadi') }}" 
                class="nav-link {{ request()->is('admin/inquery_pengirimanbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px; display: inline-block;">
                    Inquery Pengiriman Barang
                </p>
                {{-- @if($unpostCountPengirimanBarangJadi > 0)
                    <span class="badge badge-warning" style="margin-left: 5px;">{{ $unpostCountPengirimanBarangJadi }}</span>
                @endif --}}
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery returbarangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_returbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_returbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Barang</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery pemusnahanbarangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_pemusnahanbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_pemusnahanbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pemusnahan Barang</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery pemindahanbarangjadi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_pemindahanbarangjadi') }}"
                class="nav-link {{ request()->is('admin/inquery_pemindahanbarangjadi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Barang</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery estimasiproduksi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_estimasiproduksi') }}"
                class="nav-link {{ request()->is('admin/inquery_estimasiproduksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Estimasi Produksi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery estimasiproduksi']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_hasilproduksi') }}"
                class="nav-link {{ request()->is('admin/inquery_hasilproduksi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Hasil Produksi</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery deposit']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_deposit') }}"
                class="nav-link {{ request()->is('admin/inquery_deposit*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery hasilpenjualan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_hasilpenjualan') }}"
                class="nav-link {{ request()->is('admin/inquery_hasilpenjualan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Hasil Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['inquery hasilpenjualan']) --}}
          <li class="nav-item">
            <a href="{{ url('admin/inquery_setoranpelunasan') }}"
                class="nav-link {{ request()->is('admin/inquery_setoranpelunasan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 11px;"></i>
                <p style="font-size: 14px;">Inquery Pelunasan Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('admin/laporan_pemesananproduk*') ||
    request()->is('admin/laporan_penjualanproduk*')  ||
    request()->is('admin/laporan_perubahanharga*')||
    request()->is('admin/laporan_permintaanproduk*')||
    request()->is('admin/laporan_stokbarangjadi*')||
    request()->is('admin/laporan_pengirimanbarangjadi*')||
    request()->is('admin/laporan_returbarangjadi*')||
    request()->is('admin/laporan_pemusnahanbarangjadi*')||
    request()->is('admin/laporan_pemindahanbarangjadi*')||
    request()->is('admin/laporan_deposit*')||
    request()->is('admin/laporan_stoktoko*')||
    request()->is('admin/laporan_penjualantoko*')||
    request()->is('admin/laporan_hasilpenjualan*')||
    request()->is('admin/laporan_pelunasanpenjualan*')||
    request()->is('admin/laporan_estimasiproduksi*')
    // request()->is('admin/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') ||
        request()->is('admin/laporan_penjualanproduk*')|| 
        request()->is('admin/laporan_perubahanharga*') ||
        request()->is('admin/laporan_permintaanproduk*')|| 
        request()->is('admin/laporan_stokbarangjadi*')|| 
        request()->is('admin/laporan_pengirimanarangjadi*')|| 
        request()->is('admin/laporan_returbarangjadi*')|| 
        request()->is('admin/laporan_pemusnahanbarangjadi*')|| 
        request()->is('admin/laporan_pemindahanbarangjadi*')|| 
        request()->is('admin/laporan_deposit*')|| 
        request()->is('admin/laporan_stoktoko*')|| 
        request()->is('admin/laporan_penjualantoko*')|| 
        request()->is('admin/laporan_hasilpenjualan*')|| 
        request()->is('admin/laporan_pelunasanpenjualan*')|| 
        request()->is('admin/laporan_estimasiproduksi*') 
        // request()->is('admin/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">LAPORAN</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['laporan pemesananproduk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/laporan_pemesananproduk') }}"
                class="nav-link {{ request()->is('admin/laporan_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan penjualanproduk']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('admin/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['laporan penjualanproduk']) --}}
         <li class="nav-item">
            <a href="{{ url('admin/laporan_penjualantoko') }}"
                class="nav-link {{ request()->is('admin/laporan_penjualantoko*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Toko</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan perubahanharga']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_perubahanharga') }}"
                    class="nav-link {{ request()->is('admin/laporan_perubahanharga*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Perubahan Harga</p>
                </a>
            </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['laporan permintaanproduk']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('admin/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan deposit']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_deposit') }}"
                    class="nav-link {{ request()->is('admin/laporan_deposit*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Deposit</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan stokbarangjadi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_stokbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_stokbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Stok Barang Jadi</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan pengirimanbarangjadi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_pengirimanbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_pengirimanbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Barang</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan returbarangjadi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_returbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_returbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Barang</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan pemusnahanbarangjadi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_pemusnahanbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_pemusnahanbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemusnahan Barang</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan pemindahanbarangjadi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_pemindahanbarangjadi') }}"
                    class="nav-link {{ request()->is('admin/laporan_pemindahanbarangjadi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Barang</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan estimasiproduksi']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_estimasiproduksi') }}"
                    class="nav-link {{ request()->is('admin/laporan_estimasiproduksi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Estimasi Produksi</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan stoktoko']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_stoktoko') }}"
                    class="nav-link {{ request()->is('admin/laporan_stoktoko*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Stok Toko</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan hasilpenjualan']) --}}
              <li class="nav-item">
                <a href="{{ url('admin/laporan_hasilpenjualan') }}"
                    class="nav-link {{ request()->is('admin/laporan_hasilpenjualan*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Histori Barang</p>
                </a>
            </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['laporan hasilpenjualan']) --}}
        <li class="nav-item">
            <a href="{{ url('admin/laporan_setoranpelunasan') }}"
                class="nav-link {{ request()->is('admin/laporan_setoranpelunasan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pelunasan Penjualan</p>
            </a>
        </li>
    {{-- @endif --}}
    </ul>
</li>


<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('admin/profile') }}" class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
