<li class="nav-header">
    Dashboard</li>
<li class="nav-item">
    <a href="{{ url('toko_cilacap') }}" class="nav-link {{ request()->is('toko_cilacap') ? 'active' : '' }}">
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
    class="nav-item {{ request()->is('toko_cilacap/karyawan*') ||
    request()->is('toko_cilacap/pelanggan*')||
    // request()->is('toko_cilacap/barang*')||
    request()->is('toko_cilacap/produk*')||
    request()->is('toko_cilacap/toko*')||
    // request()->is('toko_cilacap/harga*')||
    request()->is('toko_cilacap/klasifikasi*')||
    request()->is('toko_cilacap/metode_pembayaran*')||
    request()->is('toko_cilacap/input*')||
    request()->is('toko_cilacap/stokpesanan_tokocilacap*')||
    request()->is('toko_cilacap/data_stokbarangjadi*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_cilacap/karyawan*') ||
        request()->is('toko_cilacap/pelanggan*')||
        // request()->is('toko_cilacap/barang*')||
        request()->is('toko_cilacap/produk*')||
        request()->is('toko_cilacap/toko*')||
        // request()->is('toko_cilacap/harga*')||
        request()->is('toko_cilacap/klasifikasi*')||
        request()->is('toko_cilacap/metode_pembayaran*')||
        request()->is('toko_cilacap/input*')||
        request()->is('toko_cilacap/stokpesanan_tokocilacap*')||
        request()->is('toko_cilacap/data_stokbarangjadi*')
      
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
            <a href="{{ url('toko_cilacap/pelanggan') }}"
                class="nav-link {{ request()->is('toko_cilacap/pelanggan*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Pelanggan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['produk']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/produk') }}"
                class="nav-link {{ request()->is('toko_cilacap/produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Produk</p>
            </a>
        </li>
        {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/stok_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/stok_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Produk</p>
            </a>
            </li>
            {{-- @endif --}}

          {{-- @if (auth()->check() && auth()->user()->menu['stok tokobanjaran']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/stokpesanan_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/stokpesanan_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Data Stok Pesanan</p>
            </a>
            </li>
            {{-- @endif --}}


    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_cilacap/pemesanan_produk*') ||
    request()->is('toko_cilacap/penjualan_produk*') ||
    request()->is('toko_cilacap/hargajual*') || 
    request()->is('toko_cilacap/stok_barangjadi*')||  
    request()->is('toko_cilacap/permintaan_produk*')|| 
    request()->is('toko_cilacap/pengiriman_barangjadi*')|| 
    request()->is('toko_cilacap/return_barangjadi*')|| 
    request()->is('toko_cilacap/pelunasan_pemesananClc*')|| 
    request()->is('toko_cilacap/setoran_tokocilacap*')|| 
    request()->is('toko_cilacap/pemindahan_tokocilacap*') 
    // request()->is('toko_cilacap/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_cilacap/pemesanan_produk*') ||
        request()->is('toko_cilacap/penjualan_produk*') ||
        request()->is('toko_cilacap/hargajual*') || 
        request()->is('toko_cilacap/stok_barangjadi*')||  
        request()->is('toko_cilacap/permintaan_produk*')||  
        request()->is('toko_cilacap/pengiriman_barangjadi*')||  
        request()->is('toko_cilacap/retur_barangjadi*')||  
        request()->is('toko_cilacap/pelunasan_pemesananClc*')||  
        request()->is('toko_cilacap/setoran_tokocilacap*')||  
        request()->is('toko_cilacap/pemindahan_tokocilacap*')  
        // request()->is('toko_cilacap/input*')
      
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
            <a href="{{ url('toko_cilacap/pemesanan_produk') }}"
                class="nav-link {{ request()->is('toko_cilacap/pemesanan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['penjualan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/penjualan_produk') }}"
                class="nav-link {{ request()->is('toko_cilacap/penjualan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['pelunasan banjaran']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/pelunasan_pemesananClc') }}"
                class="nav-link {{ request()->is('toko_cilacap/pelunasan_pemesananClc*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}
   

          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/permintaan_produk') }}"
                class="nav-link {{ request()->is('toko_cilacap/permintaan_produk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

         {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
         <li class="nav-item">
            <a href="{{ url('toko_cilacap/stok_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/stok_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Stok Produk</p>
            </a>
        </li>
        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
            <li class="nav-item">
                <a href="{{ url('toko_cilacap/retur_tokocilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/retur_tokocilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Retur Toko Cilacap</p>
                </a>
            </li>
            {{-- @endif --}}
        
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/pemindahan_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/pemindahan_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/setoran_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/setoran_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Setoran Tunai Penjualan</p>
            </a>
        </li>
        {{-- @endif --}}
    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_cilacap/inquery_pemesananproduk*') ||
    request()->is('toko_cilacap/inquery_penjualanprodukcilacap*')|| 
    request()->is('toko_cilacap/inquery_pelunasancilacap*')|| 
    request()->is('toko_cilacap/inquery_perubahanharga*')|| 
    request()->is('toko_cilacap/inquery_permintaanproduk*')|| 
    request()->is('toko_cilacap/inquery_stokbarangjadi*')|| 
    request()->is('toko_cilacap/pengiriman_tokocilacap*')|| 
    request()->is('toko_cilacap/inquery_pemindahancilacap*')|| 
    request()->is('toko_cilacap/inquery_depositcilacap*')|| 
    request()->is('toko_cilacap/inquery_setorantunaicilacap*')|| 
    request()->is('toko_cilacap/inquery_pengirimanbarangjadi*') 
    // request()->is('toko_cilacap/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_cilacap/inquery_pemesananproduk*') ||
        request()->is('toko_cilacap/inquery_penjualanprodukcilacap*')||
        request()->is('toko_cilacap/inquery_pelunasancilacap*')||
        request()->is('toko_cilacap/inquery_perubahanharga*')||
        request()->is('toko_cilacap/inquery_permintaanproduk*')||
        request()->is('toko_cilacap/inquery_stokbarangjadi*')||
        request()->is('toko_cilacap/pengiriman_tokocilacap*')||
        request()->is('toko_cilacap/inquery_pemindahancilacap*')||
        request()->is('toko_cilacap/inquery_depositcilacap*')||
        request()->is('toko_cilacap/inquery_setorantunaicilacap*')||
        request()->is('toko_cilacap/inquerypengirimanbarangjadi*')
        // request()->is('toko_cilacap/input*')
      
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
            <a href="{{ url('toko_cilacap/inquery_pemesananproduk') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_pemesananproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_penjualanprodukcilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_penjualanprodukcilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_pelunasancilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_pelunasancilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 12px;">Inquery Pelunasan Pemesanan</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_permintaanproduk') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_permintaanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Permintaan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/pengiriman_tokocilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/pengiriman_tokocilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 13px;">Inquery Pengiriman Cilacap</p>
            </a>
        </li>
        {{-- @endif --}}
        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_returcilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_returcilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Retur Cilacap</p>
            </a>
        </li>
        {{-- @endif --}}


          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_pemindahancilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_pemindahancilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Pemindahan Produk</p>
            </a>
        </li>
        {{-- @endif --}}
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_depositcilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_depositcilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Deposit</p>
            </a>
        </li>
        {{-- @endif --}}
        
          {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
          <li class="nav-item">
            <a href="{{ url('toko_cilacap/inquery_setorantunaicilacap') }}"
                class="nav-link {{ request()->is('toko_cilacap/inquery_setorantunaicilacap*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Inquery Setotan Tunai</p>
            </a>
        </li>
        {{-- @endif --}}

    </ul>
</li>

<li
    class="nav-item {{ request()->is('toko_cilacap/laporan_pemesananprodukclc*') ||
    request()->is('toko_cilacap/laporan_penjualanproduk*')  ||
    request()->is('toko_cilacap/laporan_perubahanharga*')||
    request()->is('toko_cilacap/laporan_permintaanproduk*')||
    request()->is('toko_cilacap/laporan_pengirimanproduk*')||
    request()->is('toko_cilacap/laporan_returcilacap*')||
    request()->is('toko_cilacap/laporan_pemindahancilacap*')||
    request()->is('toko_cilacap/laporan_stokbarangjadi*')||
    request()->is('toko_cilacap/laporan_stoktokocilacap*')||
    request()->is('toko_cilacap/laporan_setorantokocilacap*')||
    request()->is('toko_cilacap/laporan_setorantunaicilacap*')||
    request()->is('toko_cilacap/laporan_depositcilacap*')||
    request()->is('toko_cilacap/laporan_historicilacap*')||
    request()->is('toko_cilacap/laporan_pengirimanbarangjadi*')
    // request()->is('toko_cilacap/input*')
  
        ? 'menu-open'
        : '' }}">
    <a href="#"
        class="nav-link {{ request()->is('toko_cilacap/laporan_pemesananprodukclc*') ||
        request()->is('toko_cilacap/laporan_penjualanproduk*')|| 
        request()->is('toko_cilacap/laporan_perubahanharga*') ||
        request()->is('toko_cilacap/laporan_permintaanproduk*')|| 
        request()->is('toko_cilacap/laporan_pengirimanproduk*')|| 
        request()->is('toko_cilacap/laporan_returcilacap*')|| 
        request()->is('toko_cilacap/laporan_pemindahancilacap*')|| 
        request()->is('toko_cilacap/laporan_stokbarangjadi*')|| 
        request()->is('toko_cilacap/laporan_stoktokocilacap*')|| 
        request()->is('toko_cilacap/laporan_setorantokocilacap*')|| 
        request()->is('toko_cilacap/laporan_setorantunaicilacap*')|| 
        request()->is('toko_cilacap/laporan_depositcilacap*')|| 
        request()->is('toko_cilacap/laporan_historicilacap*')|| 
        request()->is('toko_cilacap/laporan_spengirimanarangjadi*') 
        // request()->is('toko_cilacap/input*')
      
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
            <a href="{{ url('toko_cilacap/laporan_pemesananprodukclc') }}"
                class="nav-link {{ request()->is('toko_cilacap/laporan_pemesananprodukclc*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Pemesanan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
        <li class="nav-item">
            <a href="{{ url('toko_cilacap/laporan_penjualanproduk') }}"
                class="nav-link {{ request()->is('toko_cilacap/laporan_penjualanproduk*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                <p style="font-size: 14px;">Laporan Penjualan Produk</p>
            </a>
        </li>
        {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_permintaanproduk') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_permintaanproduk*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Permintaan Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_pengirimantokocilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_pengirimantokocilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Pengiriman Produk</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_returcilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_returcilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 14px;">Laporan Retur Produk</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_pemindahancilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_pemindahancilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Pemindahan Produk</p>
                </a>
            </li>
            {{-- @endif --}}
            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_stoktokocilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_stoktokocilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Stok Toko</p>
                </a>
            </li>
            {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_setorantokocilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_setorantokocilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Setoran Kasir</p>
                </a>
            </li>
            {{-- @endif --}}

                        {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
                        <li class="nav-item">
                            <a href="{{ url('toko_cilacap/laporan_setorantunaicilacap') }}"
                                class="nav-link {{ request()->is('toko_cilacap/laporan_setorantunaicilacap*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                                <p style="font-size: 13px;">Laporan Setoran Tunai</p>
                            </a>
                        </li>
                        {{-- @endif --}}

            {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_depositcilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_depositcilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Depsit</p>
                </a>
            </li>
            {{-- @endif --}}

              {{-- @if (auth()->check() && auth()->user()->menu['karyawan']) --}}
              <li class="nav-item">
                <a href="{{ url('toko_cilacap/laporan_historicilacap') }}"
                    class="nav-link {{ request()->is('toko_cilacap/laporan_historicilacap*') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon" style="font-size: 12px;"></i>
                    <p style="font-size: 13px;">Laporan Histori Barang</p>
                </a>
            </li>
            {{-- @endif --}}

    </ul>
</li>






<li class="nav-header">Profile</li>
<li class="nav-item">
    <a href="{{ url('toko_cilacap/profile') }}" class="nav-link {{ request()->is('toko_cilacap/profile') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Update Profile</p>
    </a>
<li class="nav-item">
    <a href="#" data-toggle="modal" data-target="#modalLogout" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </a>
</li>
