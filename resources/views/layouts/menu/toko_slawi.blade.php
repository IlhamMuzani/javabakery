<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_slawi') }}" class="nav-link {{ request()->is('toko_slawi') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_slawi/karyawan*') ||
    request()->is('toko_slawi/pelanggan*')||
    // request()->is('toko_slawi/barang*')||
    request()->is('toko_slawi/produk*')||
    request()->is('toko_slawi/toko*')||
    // request()->is('toko_slawi/harga*')||
    request()->is('toko_slawi/klasifikasi*')||
    request()->is('toko_slawi/metode_pembayaran*')||
    request()->is('toko_slawi/input*')||
    request()->is('toko_slawi/stokpesanan_tokoslawi*')||
    request()->is('toko_slawi/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/karyawan*') ||
        request()->is('toko_slawi/pelanggan*')||
        // request()->is('toko_slawi/barang*')||
        request()->is('toko_slawi/produk*')||
        request()->is('toko_slawi/toko*')||
        // request()->is('toko_slawi/harga*')||
        request()->is('toko_slawi/klasifikasi*')||
        request()->is('toko_slawi/metode_pembayaran*')||
        request()->is('toko_slawi/input*')||
        request()->is('toko_slawi/stokpesanan_tokoslawi*')||
        request()->is('toko_slawi/data_stokbarangjadi*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">MASTER</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['pelanggan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pelanggan') }}"
                class="nav-link {{ request()->is('toko_slawi/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/produk') }}"
                class="nav-link {{ request()->is('toko_slawi/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/stokpesanan_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stokpesanan_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/pemesanan_produk*') ||
    request()->is('toko_slawi/penjualan_produk*') ||
    request()->is('toko_slawi/hargajual*') || 
    request()->is('toko_slawi/stok_barangjadi*')||  
    request()->is('toko_slawi/permintaan_produk*')|| 
    request()->is('toko_slawi/pengiriman_barangjadi*')|| 
    request()->is('toko_slawi/return_barangjadi*')|| 
    request()->is('toko_slawi/pelunasan_pemesananSlw*')|| 
    request()->is('toko_slawi/setoran_tokoslawi*')|| 
    request()->is('toko_slawi/pemindahan_tokoslawi*') 
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/pemesanan_produk*') ||
        request()->is('toko_slawi/penjualan_produk*') ||
        request()->is('toko_slawi/hargajual*') || 
        request()->is('toko_slawi/stok_barangjadi*')||  
        request()->is('toko_slawi/permintaan_produk*')||  
        request()->is('toko_slawi/pengiriman_barangjadi*')||  
        request()->is('toko_slawi/retur_barangjadi*')||  
        request()->is('toko_slawi/pelunasan_pemesananSlw*')||  
        request()->is('toko_slawi/setoran_tokoslawi*')||  
        request()->is('toko_slawi/pemindahan_tokoslawi*')  
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">TRANSAKSI</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['pemesanan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pelunasan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pelunasan_pemesananSlw') }}"
                class="nav-link {{ request()->is('toko_slawi/pelunasan_pemesananSlw*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_slawi/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_slawi/stok_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/stok_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_slawi/retur_tokoslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/retur_tokoslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Slawi</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pemindahan_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/pemindahan_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/setoran_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/setoran_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Setoran Tunai Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/inquery_pemesananproduk*') ||
    request()->is('toko_slawi/inquery_penjualanprodukslawi*')|| 
    request()->is('toko_slawi/inquery_pelunasanslawi*')|| 
    request()->is('toko_slawi/inquery_perubahanharga*')|| 
    request()->is('toko_slawi/inquery_permintaanproduk*')|| 
    request()->is('toko_slawi/inquery_stokbarangjadi*')|| 
    request()->is('toko_slawi/pengiriman_tokoslawi*')|| 
    request()->is('toko_slawi/inquery_pemindahanslawi*')|| 
    request()->is('toko_slawi/inquery_depositslawi*')|| 
    request()->is('toko_slawi/inquery_setorantunaislawi*')|| 
    request()->is('toko_slawi/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/inquery_pemesananproduk*') ||
        request()->is('toko_slawi/inquery_penjualanprodukslawi*')||
        request()->is('toko_slawi/inquery_pelunasanslawi*')||
        request()->is('toko_slawi/inquery_perubahanharga*')||
        request()->is('toko_slawi/inquery_permintaanproduk*')||
        request()->is('toko_slawi/inquery_stokbarangjadi*')||
        request()->is('toko_slawi/pengiriman_tokoslawi*')||
        request()->is('toko_slawi/inquery_pemindahanslawi*')||
        request()->is('toko_slawi/inquery_depositslawi*')||
        request()->is('toko_slawi/inquery_setorantunaislawi*')||
        request()->is('toko_slawi/inquerypengirimanbarangjadi*')
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">FINANCE</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_penjualanprodukslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_penjualanprodukslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pelunasanslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pelunasanslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 12px;">Inquery Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/pengiriman_tokoslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/pengiriman_tokoslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Slawi</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_returslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_returslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Slawi</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_pemindahanslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_pemindahanslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_depositslawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_depositslawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}
        
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_slawi/inquery_setorantunaislawi') }}"
                class="nav-link {{ request()->is('toko_slawi/inquery_setorantunaislawi*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Setotan Tunai</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_slawi/laporan_pemesananprodukslw*') ||
    request()->is('toko_slawi/laporan_penjualanproduk*')  ||
    request()->is('toko_slawi/laporan_perubahanharga*')||
    request()->is('toko_slawi/laporan_permintaanproduk*')||
    request()->is('toko_slawi/laporan_pengirimanproduk*')||
    request()->is('toko_slawi/laporan_returslawi*')||
    request()->is('toko_slawi/laporan_pemindahanslawi*')||
    request()->is('toko_slawi/laporan_stokbarangjadi*')||
    request()->is('toko_slawi/laporan_stoktokoslawi*')||
    request()->is('toko_slawi/laporan_setorantokoslawi*')||
    request()->is('toko_slawi/laporan_setorantunaislawi*')||
    request()->is('toko_slawi/laporan_depositslawi*')||
    request()->is('toko_slawi/laporan_historislawi*')||
    request()->is('toko_slawi/laporan_pengirimanbarangjadi*')
    // request()->is('toko_slawi/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_slawi/laporan_pemesananprodukslw*') ||
        request()->is('toko_slawi/laporan_penjualanproduk*')|| 
        request()->is('toko_slawi/laporan_perubahanharga*') ||
        request()->is('toko_slawi/laporan_permintaanproduk*')|| 
        request()->is('toko_slawi/laporan_pengirimanproduk*')|| 
        request()->is('toko_slawi/laporan_returslawi*')|| 
        request()->is('toko_slawi/laporan_pemindahanslawi*')|| 
        request()->is('toko_slawi/laporan_stokbarangjadi*')|| 
        request()->is('toko_slawi/laporan_stoktokoslawi*')|| 
        request()->is('toko_slawi/laporan_setorantokoslawi*')|| 
        request()->is('toko_slawi/laporan_setorantunaislawi*')|| 
        request()->is('toko_slawi/laporan_depositslawi*')|| 
        request()->is('toko_slawi/laporan_historislawi*')|| 
        request()->is('toko_slawi/laporan_spengirimanarangjadi*') 
        // request()->is('toko_slawi/input*')
      
            ? 'active'
            : '' }}">

        <i class="nav-icon fas fa-grip-horizontal"></i>
        <p>
            <strong style="color: rgb(255, 255, 255);">LAPORAN</strong>
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/laporan_pemesananprodukslw') }}"
                class="nav-link {{ request()->is('toko_slawi/laporan_pemesananprodukslw*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_slawi/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_slawi/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_pengirimantokoslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_pengirimantokoslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_returslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_returslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_pemindahanslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_pemindahanslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_stoktokoslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_stoktokoslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_setorantokoslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_setorantokoslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Kasir</p>
                </a>
            </li>
            {{-- @endif --}}

                        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
                        <li class="nav-item">
                            <a href="{{ url('toko_slawi/laporan_setorantunaislawi') }}"
                                class="nav-link {{ request()->is('toko_slawi/laporan_setorantunaislawi*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                                <p style="font-size: 13px;">Laporan Setoran Tunai</p>
                            </a>
                        </li>
                        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_depositslawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_depositslawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_slawi/laporan_historislawi') }}"
                    class="nav-link {{ request()->is('toko_slawi/laporan_historislawi*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Histori Slawi</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_slawi/profile') }}" class="nav-link {{ request()->is('toko_slawi/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
