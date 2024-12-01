<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_bumiayu') }}" class="nav-link {{ request()->is('toko_bumiayu') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_bumiayu/karyawan*') ||
    request()->is('toko_bumiayu/pelanggan*')||
    // request()->is('toko_bumiayu/barang*')||
    request()->is('toko_bumiayu/produk*')||
    request()->is('toko_bumiayu/toko*')||
    // request()->is('toko_bumiayu/harga*')||
    request()->is('toko_bumiayu/klasifikasi*')||
    request()->is('toko_bumiayu/metode_pembayaran*')||
    request()->is('toko_bumiayu/input*')||
    request()->is('toko_bumiayu/stokpesanan_tokobumiayu*')||
    request()->is('toko_bumiayu/stok_tokobumiayu*')||
    request()->is('toko_bumiayu/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/karyawan*') ||
        request()->is('toko_bumiayu/pelanggan*')||
        // request()->is('toko_bumiayu/barang*')||
        request()->is('toko_bumiayu/produk*')||
        request()->is('toko_bumiayu/toko*')||
        // request()->is('toko_bumiayu/harga*')||
        request()->is('toko_bumiayu/klasifikasi*')||
        request()->is('toko_bumiayu/metode_pembayaran*')||
        request()->is('toko_bumiayu/input*')||
        request()->is('toko_bumiayu/stokpesanan_tokobumiayu*')||
        request()->is('toko_bumiayu/stok_tokobumiayu*')||
        request()->is('toko_bumiayu/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_bumiayu/pelanggan') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stok_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stok_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stokpesanan_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stokpesanan_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/pemesanan_produk*') ||
    request()->is('toko_bumiayu/penjualan_produk*') ||
    request()->is('toko_bumiayu/hargajual*') || 
    request()->is('toko_bumiayu/stok_barangjadi*')||  
    request()->is('toko_bumiayu/permintaan_produk*')|| 
    request()->is('toko_bumiayu/pengiriman_barangjadi*')|| 
    request()->is('toko_bumiayu/return_barangjadi*')|| 
    request()->is('toko_bumiayu/setoran_tokobumiayu*')|| 
    request()->is('toko_bumiayu/pelunasan_pemesananBmy*')|| 
    request()->is('toko_bumiayu/pemindahan_tokobumiayu*') 
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/pemesanan_produk*') ||
        request()->is('toko_bumiayu/penjualan_produk*') ||
        request()->is('toko_bumiayu/hargajual*') || 
        request()->is('toko_bumiayu/stok_barangjadi*')||  
        request()->is('toko_bumiayu/permintaan_produk*')||  
        request()->is('toko_bumiayu/pengiriman_barangjadi*')||  
        request()->is('toko_bumiayu/retur_barangjadi*')||  
        request()->is('toko_bumiayusetoran_tokobumiayu*')||  
        request()->is('toko_bumiayu/pelunasan_pemesananBmy*')||  
        request()->is('toko_bumiayu/pemindahan_tokobumiayu*')  
        // request()->is('toko_bumiayu/input*')
      
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
            <a href="{{ url('toko_bumiayu/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pelunasan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pelunasan_pemesananBmy') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pelunasan_pemesananBmy*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_bumiayu/stok_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/stok_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_bumiayu/retur_tokobumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/retur_tokobumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Bumiayu</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pemindahan_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pemindahan_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_bumiayu/setoran_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/setoran_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Setoran Tunai Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ||
    request()->is('toko_bumiayu/inquery_penjualanprodukbumiayu*')|| 
    request()->is('toko_bumiayu/inquery_pelunasanbumiayu*')|| 
    request()->is('toko_bumiayu/inquery_perubahanharga*')|| 
    request()->is('toko_bumiayu/inquery_permintaanproduk*')|| 
    request()->is('toko_bumiayu/inquery_stokbarangjadi*')|| 
    request()->is('toko_bumiayu/pengiriman_tokobumiayu*')|| 
    request()->is('toko_bumiayu/inquery_pemindahanbumiayu*')|| 
    request()->is('toko_bumiayu/inquery_depositbumiayu*')|| 
    request()->is('toko_bumiayu/inquery_dsetorantunaibumiayu*')|| 
    request()->is('toko_bumiayu/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ||
        request()->is('toko_bumiayu/inquery_penjualanprodukbumiayu*')||
        request()->is('toko_bumiayu/inquery_pelunasanbumiayu*')||
        request()->is('toko_bumiayu/inquery_perubahanharga*')||
        request()->is('toko_bumiayu/inquery_permintaanproduk*')||
        request()->is('toko_bumiayu/inquery_stokbarangjadi*')||
        request()->is('toko_bumiayu/pengiriman_tokobumiayu*')||
        request()->is('toko_bumiayu/inquery_pemindahanbumiayu*')||
        request()->is('toko_bumiayu/inquery_depositbumiayu*')||
        request()->is('toko_bumiayu/inquery_setorantunaitbumiayu*')||
        request()->is('toko_bumiayu/inquerypengirimanbarangjadi*')
        // request()->is('toko_bumiayu/input*')
      
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
            <a href="{{ url('toko_bumiayu/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_penjualanprodukbumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_penjualanprodukbumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_pelunasanbumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_pelunasanbumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/pengiriman_tokobumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/pengiriman_tokobumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Bumiayu</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_returbumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_returbumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Bumiayu</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_pemindahanbumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_pemindahanbumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_bumiayu/inquery_depositbumiayu') }}"
                class="nav-link {{ request()->is('toko_bumiayu/inquery_depositbumiayu*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_bumiayu/inquery_setorantunaibumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/inquery_setorantunaibumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Inquery Setotan Tunai</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_bumiayu/laporan_pemesananprodukbmy*') ||
    request()->is('toko_bumiayu/laporan_penjualanproduk*')  ||
    request()->is('toko_bumiayu/laporan_perubahanharga*')||
    request()->is('toko_bumiayu/laporan_permintaanproduk*')||
    request()->is('toko_bumiayu/laporan_pengirimanproduk*')||
    request()->is('toko_bumiayu/laporan_returbumiayu*')||
    request()->is('toko_bumiayu/laporan_pemindahanbumiayu*')||
    request()->is('toko_bumiayu/laporan_stokbarangjadi*')||
    request()->is('toko_bumiayu/laporan_stoktokobumiayu*')||
    request()->is('toko_bumiayu/laporan_setorantokobumiayu*')||
    request()->is('toko_bumiayu/laporan_depositbumiayu*')||
    request()->is('toko_bumiayu/laporan_historibumiayu*')||
    request()->is('toko_bumiayu/laporan_pengirimanbarangjadi*')
    // request()->is('toko_bumiayu/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_bumiayu/laporan_pemesananprodukbmy*') ||
        request()->is('toko_bumiayu/laporan_penjualanproduk*')|| 
        request()->is('toko_bumiayu/laporan_perubahanharga*') ||
        request()->is('toko_bumiayu/laporan_permintaanproduk*')|| 
        request()->is('toko_bumiayu/laporan_pengirimanproduk*')|| 
        request()->is('toko_bumiayu/laporan_returbumiayu*')|| 
        request()->is('toko_bumiayu/laporan_pemindahanbumiayu*')|| 
        request()->is('toko_bumiayu/laporan_stokbarangjadi*')|| 
        request()->is('toko_bumiayu/laporan_stoktokobumiayu*')||
        request()->is('toko_bumiayu/laporan_setorantokobumiayu*')||

        request()->is('toko_bumiayu/laporan_setorantokobumiayu*')|| 
        request()->is('toko_bumiayu/laporan_depositbumiayu*')|| 
        request()->is('toko_bumiayu/laporan_historibumiayu*')|| 
        request()->is('toko_bumiayu/laporan_spengirimanarangjadi*') 
        // request()->is('toko_bumiayu/input*')
      
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
            <a href="{{ url('toko_bumiayu/laporan_pemesananprodukbmy') }}"
                class="nav-link {{ request()->is('toko_bumiayu/laporan_pemesananprodukbmy*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_bumiayu/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_bumiayu/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_pengirimantokobumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_pengirimantokobumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_returbumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_returbumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_pemindahanbumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_pemindahanbumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_stoktokobumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_stoktokobumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_setorantunaibumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_setorantunaibumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Kasir</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_setorantokobumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_setorantokobumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Penjualan</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_depositbumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_depositbumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_bumiayu/laporan_historibumiayu') }}"
                    class="nav-link {{ request()->is('toko_bumiayu/laporan_historibumiayu*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Histori Barang</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_bumiayu/profile') }}" class="nav-link {{ request()->is('toko_bumiayu/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
