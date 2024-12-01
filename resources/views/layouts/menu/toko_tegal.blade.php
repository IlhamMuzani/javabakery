<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_tegal') }}" class="nav-link {{ request()->is('toko_tegal') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_tegal/karyawan*') ||
    request()->is('toko_tegal/pelanggan*')||
    // request()->is('toko_tegal/barang*')||
    request()->is('toko_tegal/produk*')||
    request()->is('toko_tegal/toko*')||
    // request()->is('toko_tegal/harga*')||
    request()->is('toko_tegal/klasifikasi*')||
    request()->is('toko_tegal/metode_pembayaran*')||
    request()->is('toko_tegal/input*')||
    request()->is('toko_tegal/stokpesanan_tokotegal*')||
    request()->is('toko_tegal/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_tegal/karyawan*') ||
        request()->is('toko_tegal/pelanggan*')||
        // request()->is('toko_tegal/barang*')||
        request()->is('toko_tegal/produk*')||
        request()->is('toko_tegal/toko*')||
        // request()->is('toko_tegal/harga*')||
        request()->is('toko_tegal/klasifikasi*')||
        request()->is('toko_tegal/metode_pembayaran*')||
        request()->is('toko_tegal/input*')||
        request()->is('toko_tegal/stokpesanan_tokotegal*')||
        request()->is('toko_tegal/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_tegal/pelanggan') }}"
                class="nav-link {{ request()->is('toko_tegal/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/produk') }}"
                class="nav-link {{ request()->is('toko_tegal/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/stok_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/stok_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/stokpesanan_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/stokpesanan_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_tegal/pemesanan_produk*') ||
    request()->is('toko_tegal/penjualan_produk*') ||
    request()->is('toko_tegal/hargajual*') || 
    request()->is('toko_tegal/stok_barangjadi*')||  
    request()->is('toko_tegal/permintaan_produk*')|| 
    request()->is('toko_tegal/pengiriman_barangjadi*')|| 
    request()->is('toko_tegal/return_barangjadi*')|| 
    request()->is('toko_tegal/pelunasan_pemesananTgl*')|| 
    request()->is('toko_tegal/setoran_tokobanajran*')|| 
    request()->is('toko_tegal/pemindahan_tokotegal*') 
    // request()->is('toko_tegal/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_tegal/pemesanan_produk*') ||
        request()->is('toko_tegal/penjualan_produk*') ||
        request()->is('toko_tegal/hargajual*') || 
        request()->is('toko_tegal/stok_barangjadi*')||  
        request()->is('toko_tegal/permintaan_produk*')||  
        request()->is('toko_tegal/pengiriman_barangjadi*')||  
        request()->is('toko_tegal/retur_barangjadi*')||  
        request()->is('toko_tegal/pelunasan_pemesananTgl*')||  
        request()->is('toko_tegal/setoran_tokobanajran*')||  
        request()->is('toko_tegal/pemindahan_tokotegal*')  
        // request()->is('toko_tegal/input*')
      
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
            <a href="{{ url('toko_tegal/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_tegal/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_tegal/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pelunasan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/pelunasan_pemesananTgl') }}"
                class="nav-link {{ request()->is('toko_tegal/pelunasan_pemesananTgl*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_tegal/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_tegal/stok_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/stok_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_tegal/retur_tokotegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/retur_tokotegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Tegal</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/pemindahan_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/pemindahan_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/setoran_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/setoran_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Setoran Tunai Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_tegal/inquery_pemesananproduk*') ||
    request()->is('toko_tegal/inquery_penjualanproduktegal*')|| 
    request()->is('toko_tegal/inquery_pelunasantegal*')|| 
    request()->is('toko_tegal/inquery_perubahanharga*')|| 
    request()->is('toko_tegal/inquery_permintaanproduk*')|| 
    request()->is('toko_tegal/inquery_stokbarangjadi*')|| 
    request()->is('toko_tegal/pengiriman_tokotegal*')|| 
    request()->is('toko_tegal/inquery_pemindahantegal*')|| 
    request()->is('toko_tegal/inquery_deposittegal*')|| 
    request()->is('toko_tegal/inquery_setorantunaibanjaran*')|| 
    request()->is('toko_tegal/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_tegal/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_tegal/inquery_pemesananproduk*') ||
        request()->is('toko_tegal/inquery_penjualanproduktegal*')||
        request()->is('toko_tegal/inquery_pelunasantegal*')||
        request()->is('toko_tegal/inquery_perubahanharga*')||
        request()->is('toko_tegal/inquery_permintaanproduk*')||
        request()->is('toko_tegal/inquery_stokbarangjadi*')||
        request()->is('toko_tegal/pengiriman_tokotegal*')||
        request()->is('toko_tegal/inquery_pemindahantegal*')||
        request()->is('toko_tegal/inquery_deposittegal*')||
        request()->is('toko_tegal/inquery_setorantunaibanjaran*')||
        request()->is('toko_tegal/inquerypengirimanbarangjadi*')
        // request()->is('toko_tegal/input*')
      
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
            <a href="{{ url('toko_tegal/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_penjualanproduktegal') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_penjualanproduktegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_pelunasantegal') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_pelunasantegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 12px;">Inquery Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/pengiriman_tokotegal') }}"
                class="nav-link {{ request()->is('toko_tegal/pengiriman_tokotegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Tegal</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_returtegal') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_returtegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Tegal</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_pemindahantegal') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_pemindahantegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_deposittegal') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_deposittegal*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}
        
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_tegal/inquery_setorantunaibanjaran') }}"
                class="nav-link {{ request()->is('toko_tegal/inquery_setorantunaibanjaran*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Setotan Tunai</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_tegal/laporan_pemesananproduktgl*') ||
    request()->is('toko_tegal/laporan_penjualanproduk*')  ||
    request()->is('toko_tegal/laporan_perubahanharga*')||
    request()->is('toko_tegal/laporan_permintaanproduk*')||
    request()->is('toko_tegal/laporan_pengirimanproduk*')||
    request()->is('toko_tegal/laporan_returtegal*')||
    request()->is('toko_tegal/laporan_pemindahantegal*')||
    request()->is('toko_tegal/laporan_stokbarangjadi*')||
    request()->is('toko_tegal/laporan_stoktokotegal*')||
    request()->is('toko_tegal/laporan_setorantokotegal*')||
    request()->is('toko_tegal/laporan_setorantunaitegal*')||
    request()->is('toko_tegal/laporan_deposittegal*')||
    request()->is('toko_tegal/laporan_historitegal*')||
    request()->is('toko_tegal/laporan_pengirimanbarangjadi*')
    // request()->is('toko_tegal/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_tegal/laporan_pemesananproduktgl*') ||
        request()->is('toko_tegal/laporan_penjualanproduk*')|| 
        request()->is('toko_tegal/laporan_perubahanharga*') ||
        request()->is('toko_tegal/laporan_permintaanproduk*')|| 
        request()->is('toko_tegal/laporan_pengirimanproduk*')|| 
        request()->is('toko_tegal/laporan_returtegal*')|| 
        request()->is('toko_tegal/laporan_pemindahantegal*')|| 
        request()->is('toko_tegal/laporan_stokbarangjadi*')|| 
        request()->is('toko_tegal/laporan_stoktokotegal*')|| 
        request()->is('toko_tegal/laporan_setorantokotegal*')|| 
        request()->is('toko_tegal/laporan_setorantunaitegal*')|| 
        request()->is('toko_tegal/laporan_deposittegal*')|| 
        request()->is('toko_tegal/laporan_historitegal*')|| 
        request()->is('toko_tegal/laporan_pengirimanarangjadi*') 
        // request()->is('toko_tegal/input*')
      
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
            <a href="{{ url('toko_tegal/laporan_pemesananproduktgl') }}"
                class="nav-link {{ request()->is('toko_tegal/laporan_pemesananproduktgl*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_tegal/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_tegal/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_pengirimantokotegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_pengirimantokotegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_returtegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_returtegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_pemindahantegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_pemindahantegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_stoktokotegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_stoktokotegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_setorantokotegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_setorantokotegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Kasir</p>
                </a>
            </li>
            {{-- @endif --}}

                        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
                        <li class="nav-item">
                            <a href="{{ url('toko_tegal/laporan_setorantunaitegal') }}"
                                class="nav-link {{ request()->is('toko_tegal/laporan_setorantunaitegal*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                                <p style="font-size: 13px;">Laporan Setoran Tunai</p>
                            </a>
                        </li>
                        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_deposittegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_deposittegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_tegal/laporan_historitegal') }}"
                    class="nav-link {{ request()->is('toko_tegal/laporan_historitegal*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Histori Barang</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_tegal/profile') }}" class="nav-link {{ request()->is('toko_tegal/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
