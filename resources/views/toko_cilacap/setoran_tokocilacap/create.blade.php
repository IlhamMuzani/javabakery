@extends('layouts.app')

@section('title', 'Penjualan Toko')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Setoran Penjualan Cilacap</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    

            <form id="setoranForm" action="{{ url('toko_cilacap/setoran_tokocilacap') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                    value="{{ Request::get('tanggal_penjualan') }}" onchange="updateModalLink()" />
                                <label for="tanggal_penjualan">(Tanggal Penjualan)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id" readonly>
                                    <option value="6" selected>CILACAP</option>
                                </select>
                                <label for="toko_id">(Toko)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" id="btnCari" class="btn btn-outline-primary">Cari</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        {{-- <input type="text" id="toko_id" name="toko_id" class="form-control" /> --}}

                        <!-- Tempat untuk menampilkan Penjualan Kotor -->
                        <div class="form-group row mb-3">
                            <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Kotor</a>
                            </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" placeholder="">
                            </div>
                        </div>

                        <!-- Diskon Penjualan -->
                        <div class="form-group row mb-3">
                            <label for="diskon_penjualan" class="col-sm-3 col-form-label">
                                <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Diskon Penjualan</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="diskon_penjualan" name="diskon_penjualan" >
                            </div>
                        </div>

                            <div class="col-sm-3 offset-sm-3">
                                <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>
                    
                        <!-- Penjualan Bersih -->
                        <div class="form-group row mb-3">
                            <label for="penjualan_bersih" class="col-sm-3 col-form-label">
                                <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Bersih</a>
                            </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="penjualan_bersih" name="penjualan_bersih" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="deposit_keluar" class="col-sm-3 col-form-label">
                                <a id="deposit_keluar_link" href="#" data-toggle="modal" data-target="#depositKeluarModal" class="text-decoration-none">Deposit Keluar</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="deposit_keluar" name="deposit_keluar" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="deposit_masuk" class="col-sm-3 col-form-label">
                                <a id="deposit_masuk_link" href="#" data-toggle="modal" data-target="#depositMasukModal" class="text-decoration-none">Deposit Masuk</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="deposit_masuk" name="deposit_masuk" >
                            </div>
                        </div>
                        
                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

                        <div class="form-group row mb-3">
                            <label for="total_penjualan" class="col-sm-3 col-form-label">
                                <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Total Penjualan</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="total_penjualan" name="total_penjualan" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="mesin_edc" class="col-sm-3 col-form-label">
                                <a id="penjualan_mesinedc_link" href="#" data-toggle="modal" data-target="#penjualanMesinedcModal" class="text-decoration-none">Mesin EDC</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="mesin_edc" name="mesin_edc" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="qris" class="col-sm-3 col-form-label">
                                <a id="penjualan_qris_link" href="#" data-toggle="modal" data-target="#penjualanQrisModal" class="text-decoration-none">Qris</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="qris" name="qris" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="gobiz" class="col-sm-3 col-form-label">
                                <a id="penjualan_gobiz_link" href="#" data-toggle="modal" data-target="#penjualanGobizModal" class="text-decoration-none">Gobiz</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="gobiz" name="gobiz" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="transfer" class="col-sm-3 col-form-label">
                                <a id="penjualan_transfer_link" href="#" data-toggle="modal" data-target="#penjualanTransferModal" class="text-decoration-none">Transfer</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="transfer" name="transfer" >
                            </div>
                        </div>

                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

                        <div class="form-group row mb-3">
                            <label for="total_setoran" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Total Setoran</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="total_setoran" name="total_setoran" >
                            </div>
                        </div>

                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="tambahInputCheckbox">
                            <label class="form-check-label" for="tambahInputCheckbox">2 x setoran</label>
                        </div>
                        <div class="form-group row mb-3" id="row1">
                            <div class="col-sm-3">
                                <input class="form-control" id="tanggal_setoran" name="tanggal_setoran" type="date" value="{{ Request::get('tanggal_setoran') }}" />
                            </div>  
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="nominal_setoran" name="nominal_setoran" oninput="formatNumber(this); updatePlusMinus();">
                            </div>
                        </div>
                        
                        <!-- Tempat tambahan input ketika checkbox di centang -->
                        <div id="extraRows"></div>
                        
                        <div class="form-group row mb-3">
                            <label for="plusminus" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">+/-</a>
                            </label>                             
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="plusminus" name="plusminus"> 
                            </div>
                        </div>
                        
                    </div>       
                    </div>   
                    <button type="submit" class="btn btn-primary">Simpan</button>        
            </form>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="penjualanKotorModal" tabindex="-1" role="dialog" aria-labelledby="penjualanKotorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanKotorModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.penjualantoko.kotorclc') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a>

                            <a href="{{ route('print.fakturpenjualantokoclc') }}" 
                            id="faktur_penjualan_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="depositKeluarModal" tabindex="-1" role="dialog" aria-labelledby="depositKeluarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositKeluarModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            {{-- <a href="{{ route('print.penjualantoko.kotorclc') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a> --}}

                            <a href="{{ route('print.fakturdepositkeluartokoclc') }}" 
                            id="faktur_deposit_keluar_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="depositMasukModal" tabindex="-1" role="dialog" aria-labelledby="depositMasukModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositMasukModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            {{-- <a href="{{ route('print.penjualantoko.kotorclc') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a> --}}

                            <a href="{{ route('print.fakturdepositmasuktokoclc') }}" 
                            id="faktur_deposit_masuk_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanMesinedcModal" tabindex="-1" role="dialog" aria-labelledby="penjualanMesinedcModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanMesinedcModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanmesinedcclc') }}" 
                            id="penjualan_mesinedc_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesananmesinedcclc') }}" 
                            id="pemesanan_mesinedc_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanQrisModal" tabindex="-1" role="dialog" aria-labelledby="penjualanQrisModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanQrisModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanqrisclc') }}" 
                            id="penjualan_qris_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesananqrisclc') }}" 
                            id="pemesanan_qris_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanTransferModal" tabindex="-1" role="dialog" aria-labelledby="penjualanTransferModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanTransferModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualantransferclc') }}" 
                            id="penjualan_transfer_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesanantransferclc') }}" 
                            id="pemesanan_transfer_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanGobizModal" tabindex="-1" role="dialog" aria-labelledby="penjualanGobizModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanGobizModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualangobizclc') }}" 
                            id="penjualan_gobiz_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesanangobizclc') }}" 
                            id="pemesanan_gobiz_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </section>

    <script>
        // Fungsi untuk memperbarui URL link di dalam modal
        function updateModalLink() {
            const tanggalPenjualan = document.getElementById('tanggal_penjualan').value;
            const tokoId = document.getElementById('toko').value;
    
            // Base URL untuk Barang Keluar (link di dalam modal)
            const baseUrlBarangKeluar = "{{ route('print.penjualantoko.kotorclc') }}";
            const baseUrlFakturPenjualan = "{{ route('print.fakturpenjualantokoclc') }}"; 
            const baseUrlFakturDeposit = "{{ route('print.fakturdepositmasuktokoclc') }}"; 
            const baseUrlFakturDepositKeluar = "{{ route('print.fakturdepositkeluartokoclc') }}"; 
            const baseUrlFakturPenjualanMesinedc = "{{ route('print.fakturpenjualanmesinedcclc') }}"; 
            const baseUrlFakturPemesananMesinedc = "{{ route('print.fakturpemesananmesinedcclc') }}"; 
            const baseUrlFakturPenjualanQris = "{{ route('print.fakturpenjualanqrisclc') }}"; 
            const baseUrlFakturPemesananQris = "{{ route('print.fakturpemesananqrisclc') }}"; 
            const baseUrlFakturPenjualanTransfer = "{{ route('print.fakturpenjualantransferclc') }}"; 
            const baseUrlFakturPemesananTransfer = "{{ route('print.fakturpemesanantransferclc') }}"; 
            const baseUrlFakturPenjualanGobiz = "{{ route('print.fakturpenjualangobizclc') }}"; 
            const baseUrlFakturPemesananGobiz = "{{ route('print.fakturpemesanangobizclc') }}"; 

    
            // Perbarui URL untuk Barang Keluar
            const urlBarangKeluar = new URL(baseUrlBarangKeluar, window.location.origin);
            if (tanggalPenjualan) {
                urlBarangKeluar.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlBarangKeluar.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_kotor_link_modal').href = urlBarangKeluar.toString();
    
            // Perbarui URL untuk Faktur Penjualan
            const urlFakturPenjualan = new URL(baseUrlFakturPenjualan, window.location.origin); // Perbaikan nama variabel
            if (tanggalPenjualan) {
                urlFakturPenjualan.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPenjualan.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('faktur_penjualan_link_modal').href = urlFakturPenjualan.toString();

            const urlFakturDeposit = new URL(baseUrlFakturDeposit, window.location.origin); // Perbaikan nama variabel
            if (tanggalPenjualan) {
                urlFakturDeposit.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturDeposit.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('faktur_deposit_masuk_link_modal').href = urlFakturDeposit.toString();

            const urlFakturDepositKeluar = new URL(baseUrlFakturDepositKeluar, window.location.origin); // Perbaikan nama variabel
            if (tanggalPenjualan) {
                urlFakturDepositKeluar.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturDepositKeluar.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('faktur_deposit_keluar_link_modal').href = urlFakturDepositKeluar.toString();

            const urlFakturPenjualanMesinedc = new URL(baseUrlFakturPenjualanMesinedc, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPenjualanMesinedc.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPenjualanMesinedc.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_mesinedc_link_modal').href = urlFakturPenjualanMesinedc.toString();

            const urlFakturPemesananMesinedc = new URL(baseUrlFakturPemesananMesinedc, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPemesananMesinedc.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPemesananMesinedc.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('pemesanan_mesinedc_link_modal').href = urlFakturPemesananMesinedc.toString();

            const urlFakturPenjualanQris = new URL(baseUrlFakturPenjualanQris, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPenjualanQris.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPenjualanQris.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_qris_link_modal').href = urlFakturPenjualanQris.toString();

            const urlFakturPemesananQris = new URL(baseUrlFakturPemesananQris, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPemesananQris.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPemesananQris.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('pemesanan_qris_link_modal').href = urlFakturPemesananQris.toString();

            const urlFakturPenjualanTransfer = new URL(baseUrlFakturPenjualanTransfer, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPenjualanTransfer.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPenjualanTransfer.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_transfer_link_modal').href = urlFakturPenjualanTransfer.toString();

            const urlFakturPemesananTransfer = new URL(baseUrlFakturPemesananTransfer, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPemesananTransfer.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPemesananTransfer.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('pemesanan_transfer_link_modal').href = urlFakturPemesananTransfer.toString();

            const urlFakturPenjualanGobiz = new URL(baseUrlFakturPenjualanGobiz, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPenjualanGobiz.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPenjualanGobiz.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_gobiz_link_modal').href = urlFakturPenjualanGobiz.toString();

            const urlFakturPemesananGobiz = new URL(baseUrlFakturPemesananGobiz, window.location.origin);
            if (tanggalPenjualan) {
                urlFakturPemesananGobiz.searchParams.set('tanggal_penjualan', tanggalPenjualan);
                }
                if (tokoId) {
                    urlFakturPemesananGobiz.searchParams.set('toko_id', tokoId);
                }
            document.getElementById('penjualan_gobiz_link_modal').href = urlFakturPemesananGobiz.toString();

        }
    
        // Pastikan modal dipicu dengan tautan yang benar saat ditampilkan
        $('#penjualanKotorModal').on('show.bs.modal', function () {
            updateModalLink(); // Panggil fungsi untuk memperbarui link di dalam modal
        });
    
        // Inisialisasi pertama
        document.addEventListener("DOMContentLoaded", function () {
            updateModalLink();
        });
    </script>

    <script>
        document.getElementById('tambahInputCheckbox').addEventListener('change', function() {
            const extraRowsContainer = document.getElementById('extraRows');
            
            if (this.checked) {
                // Buat elemen input tambahan
                const newRow1 = document.createElement('div');
                newRow1.className = 'form-group row mb-3';
                newRow1.innerHTML = `
                    <div class="col-sm-3">
                        <input class="form-control" name="tanggal_setoran2" type="date">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="nominal_setoran2" oninput="formatNumber(this); updatePlusMinus();">
                    </div>
                `;
    
                // Tambahkan kedua row ke container
                extraRowsContainer.appendChild(newRow1);
            } else {
                // Hapus semua input tambahan jika checkbox di-uncheck
                extraRowsContainer.innerHTML = '';
            }
        });
    
        // Fungsi untuk menghapus format angka
        function unformatNumber(number) {
            // Hapus semua titik dan ganti koma dengan titik
            number = number.replace(/\./g, '').replace(',', '.');
            
            // Cek jika ada lebih dari satu titik setelah dihapus
            const parts = number.split('.');
            if (parts.length > 2) {
                return 0; // Mengembalikan 0 jika terdapat lebih dari satu titik
            }

            return parseFloat(number) || 0;
        }
    
        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatNumber(input) {
            let value = input.value.replace(/\./g, ''); // Hapus titik sebelumnya
            if (!isNaN(value) && value !== "") {
                input.value = new Intl.NumberFormat('id-ID').format(value);
            }
        }
    
        // Fungsi untuk menghitung nilai plus/minus
        function updatePlusMinus() {
            const totalSetoran = unformatNumber(document.getElementById('total_setoran').value);
            let totalNominalSetoran = 0;
    
            // Ambil semua input nominal_setoran dan hitung totalnya
            const nominalInputs = document.querySelectorAll('input[name^="nominal_setoran"]');
            nominalInputs.forEach(input => {
                totalNominalSetoran += unformatNumber(input.value);
            });
    
            // Hitung selisih antara total setoran dan total nominal setoran
            const plusMinus = totalNominalSetoran - totalSetoran;
    
            // Update nilai plusminus dengan format yang benar
            document.getElementById('plusminus').value = new Intl.NumberFormat('id-ID').format(plusMinus);
        }
    </script>
    
    
<!-- Tambahkan script JQuery untuk Ajax -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
        $('#btnCari').on('click', function () {
            var tanggalPenjualan = $('#tanggal_penjualan').val();
            var tokoId = $('#toko').val();

            if (tanggalPenjualan) {
                $.ajax({
                    url: "{{ url('toko_cilacap/get-penjualancilacap') }}", // Sesuaikan URL sesuai dengan rute Anda
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tanggal_penjualan: tanggalPenjualan,
                        toko_id: tokoId
                    },
                    success: function(response) {
                        // Isi field-form dengan data dari respons
                        $('#penjualan_kotor').val(response.penjualan_kotor);
                        $('#diskon_penjualan').val(response.diskon_penjualan);
                        $('#penjualan_bersih').val(response.penjualan_bersih);
                        $('#deposit_keluar').val(response.deposit_keluar);
                        $('#deposit_masuk').val(response.deposit_masuk);
                        $('#mesin_edc').val(response.mesin_edc);
                        $('#qris').val(response.qris);
                        $('#gobiz').val(response.gobiz);
                        $('#transfer').val(response.transfer);
                        $('#total_penjualan').val(response.total_penjualan);
                        $('#total_setoran').val(response.total_setoran);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // Untuk debugging
                    }
                });
            } else {
                alert("Silakan pilih tanggal penjualan terlebih dahulu.");
            }
        });
    });
</script>



<script>
    $(document).ready(function() {
        // Tambahkan event listener pada tombol "Simpan"
        $('#btnSimpan').click(function() {
            // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
            $(this).hide();
            $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
            $('#loading').show();

            // Lakukan pengiriman formulir
            $('form').submit();
        });
    });
</script>


@endsection


