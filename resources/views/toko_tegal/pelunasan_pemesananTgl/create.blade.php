@extends('layouts.app')

@section('title', 'Pelunasan Pemesanan')
@include('sweetalert::alert')

@section('content')
<style>
    .card {
        min-height: 100%;
    }
    .label-width {
    width: 100px; /* Atur sesuai kebutuhan */
}

.input-width {
    flex: 1;
}

.form-control-full-width {
        width: 100%;
    }
</style>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pelunasan Pemesanan Produk</h1>
                </div><!-- /.col -->
                
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            @if (session('erorrss'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('erorrss') }}
                </div>
            @endif

            @if (session('error_pelanggans') || session('error_pesanans'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @if (session('error_pelanggans'))
                        @foreach (session('error_pelanggans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                    @if (session('error_pesanans'))
                        @foreach (session('error_pesanans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                </div>
            @endif
        
            <form  action="{{ url('toko_tegal/pelunasan_pemesananTgl') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <label style="font-size:14px" class="form-label" for="kode_dppemesanan">Kode Pemesanan</label>
                        <div class="form-group d-flex">
                            <input class="form-control" hidden id="dppemesanan_id" name="dppemesanan_id" type="text"
                                placeholder="" value="{{ old('dppemesanan_id') }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control col-md-4" id="kode_pemesanan" name="kode_pemesanan" type="text" readonly 
                                value="{{ old('kode_pemesanan') }}" style="margin-right: 10px; font-size:14px" />
                                <div class="col-md">
                                    <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                        <i class="fas fa-search" style=""></i>Cari
                                    </button> 
                                </div> 
                        </div>
                    </div>  
                </div>
                <div>
                    <div>
                        {{-- Detail Pelanggan --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Pelanggan</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group" hidden>
                                            <label for="pelanggan_id">Id</label>
                                            <input type="text" class="form-control form-control-full-width" id="pelanggan_id" readonly name="pelanggan_id" placeholder="" value="{{ old('pelanggan_id') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_pelanggan">Nama Pelanggan</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="nama_pelanggan" readonly name="nama_pelanggan" placeholder="" value="{{ old('nama_pelanggan') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp">No. Telp</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="telp" readonly name="telp" placeholder="" value="{{ old('telp') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat">Alamat</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat" readonly name="alamat" placeholder="" value="">{{ old('alamat') }}</textarea>
                                        </div>
                                        <div class="form-check" style="color:white">
                                            <label class="form-check-label">
                                                .
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Detail Pengiriman</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label style="font-size:14px" for="tanggal_kirim">Tanggal Pengiriman</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="tanggal_kirim" readonly name="tanggal_kirim" placeholder="" value="{{ old('tanggal_kirim') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_penerima">Nama Penerima</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="nama_penerima" readonly name="nama_penerima" placeholder="" value="{{ old('nama_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp_penerima">Telepon Penerima</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="telp_penerima" readonly name="telp_penerima" placeholder="" value="{{ old('telp_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat_penerima">Alamat Penerima</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat_penerima" readonly name="alamat_penerima" placeholder="" value="">{{ old('alamat_penerima') }}</textarea>
                                        </div>
                                        <div class="form-check" style="color:white">
                                            <label class="form-check-label">
                                                .
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pemsanan --}}
                        <div id="forms-container"></div>

                        {{-- pembayaran --}}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="sub_total" class="mr-2 label-width">Sub Total</label>
                                                <input type="text" class="form-control large-font input-width" id="sub_total" name="sub_total" value="{{ old('sub_total', 'Rp') }}" >
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="col mb-3 d-flex align-items-center">
                                                <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                                <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="{{ old('sub_totalasli', 'Rp') }}">
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="col mb-3 d-flex align-items-center">
                                                <label for="nominal_diskon" class="mr-2">nominal diskon</label>
                                                <input type="text" class="form-control large-font" id="nominal_diskon" name="nominal_diskon" value="{{ old('nominal_diskon') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="dp_pemesanan" class="mr-2 label-width">DP</label>
                                                <input type="text" class="form-control large-font input-width" id="dp_pemesanan" name="dp_pemesanan" readonly value="{{ old('dp_pemesanan', 'Rp') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kekurangan_pemesanan" class="mr-2 label-width">Kekurangan</label>
                                                <input type="text" class="form-control large-font input-width" id="kekurangan_pemesanan" name="kekurangan_pemesanan" value="{{ old('kekurangan_pemesanan', 'Rp') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="flex: 8;">
                                    <label for="metode_id">Jenis Pembayaran</label>
                                    <select class="select2bs4 select2-hidden-accessible" name="metode_id" style="width: 100%;" id="nama_metode" onchange="getData1()">
                                        <option value="">- Pilih -</option>
                                        @foreach ($metodes as $metode)
                                            <option value="{{ $metode->id }}" data-fee="{{ $metode->fee }}" {{ old('metode_id') == $metode->id ? 'selected' : '' }}>
                                                {{ $metode->nama_metode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="payment-fields" class="form-group" style="display: none; margin-top: 20px;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="fee">Fee (%)</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="fee" readonly name="fee" placeholder="" value="{{ old('fee') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="total_fee">Total Fee</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="total_fee" name="total_fee" placeholder="" value="{{ old('total_fee', 'Rp0') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="keterangan">Keterangan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="" value="{{ old('keterangan') }}">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="pelunasan" class="mr-2 label-width">Bayar</label>
                                                <input type="text" class="form-control large-font input-width" id="pelunasan" name="pelunasan" value="{{ old('pelunasan', 'Rp') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kembali" class="mr-2 label-width">Kembali</label>
                                                <input type="text" class="form-control large-font input-width" id="kembali" name="kembali" value="{{ old('kembali', 'Rp') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-footer text-right">
                                <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                                <div id="loading" style="display: none;">
                                    {{-- <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan... --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>

        {{-- modal deposit --}}
        <div class="modal fade" id="tableDeposit" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Deposit</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
               
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr style="font-size: 13px">
                                    <th class="text-center">No</th>
                                    <th>Kode Deposit</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Tanggal Ambil</th>
                                    <th>Nominal</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($dppemesanans as $return)
                                @if (is_null($return->pelunasan))
                                    <tr style="font-size: 14px" 
                                    onclick="GetReturn(
                                            '{{ $return->id }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}',
                                            '{{ $return->dp_pemesanan }}',  
                                            )">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $return->kode_dppemesanan }}</td>
                                        <td>{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}</td>
                                        <td>{{ $return->pemesananproduk ? $return->pemesananproduk->nama_pelanggan : 'No Data' }}</td>
                                        <td>{{ $return->pemesananproduk ? $return->pemesananproduk->tanggal_kirim : 'No Data' }}</td>
                                        <td>{{number_format($return->dp_pemesanan, 0, ',', '.') }}</td>
                                        <td   td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="GetReturn(
                                            '{{ $return->id }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}',
                                            '{{ $return->dp_pemesanan }}',
                                           
                                            )">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        </td>

                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="modal fade" id="tableProduk" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Produk</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables5" class="table table-bordered table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
                                    <th>Kode Lama</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Member</th>
                                    <th>Diskon Member</th>
                                    <th>Harga Non Member</th>
                                    <th>Diskon Non Member</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    @php
                                        $tokotegal = $item->tokotegal->first();
                                        $stokpesanan_tokotegal = $item->stokpesanan_tokotegal ? $item->stokpesanan_tokotegal->jumlah : 0; // Jika stok ada, tampilkan, jika tidak tampilkan 0

                                    @endphp
                                    <tr class="pilih-btn"
                                        data-id="{{ $item->id }}"
                                        data-kode="{{ $item->kode_produk }}"
                                        data-lama="{{ $item->kode_lama }}"
                                        data-catatan="{{ $item->catatanproduk }}"
                                        data-nama="{{ $item->nama_produk }}"
                                        data-member="{{ $tokotegal ? $tokotegal->member_harga_tgl : '' }}"
                                        data-diskonmember="{{ $tokotegal ? $tokotegal->member_diskon_tgl : '' }}"
                                        data-nonmember="{{ $tokotegal ? $tokotegal->non_harga_tgl : '' }}"
                                        data-diskonnonmember="{{ $tokotegal ? $tokotegal->non_diskon_tgl : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>
                                            <span class="member_harga_tgl">{{ $tokotegal ? $tokotegal->member_harga_tgl : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_tgl">{{ $tokotegal ? $tokotegal->member_diskon_tgl : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_tgl">{{ $tokotegal ? $tokotegal->non_harga_tgl : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_tgl">{{ $tokotegal ? $tokotegal->non_diskon_tgl : '' }}</span>
                                        </td>
                                    
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                data-id="{{ $item->id }}"
                                                data-kode="{{ $item->kode_produk }}"
                                                data-lama="{{ $item->kode_lama }}"
                                                data-catatan="{{ $item->catatanproduk }}"
                                                data-nama="{{ $item->nama_produk }}"
                                                data-member="{{ $tokotegal ? $tokotegal->member_harga_tgl : '' }}"
                                                data-diskonmember="{{ $tokotegal ? $tokotegal->member_diskon_tgl : '' }}"
                                                data-nonmember="{{ $tokotegal ? $tokotegal->non_harga_tgl : '' }}"
                                                data-diskonnonmember="{{ $tokotegal ? $tokotegal->non_diskon_tgl : '' }}">
                                                <i class="fas fa-plus"></i> 
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
         
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script>
    $(document).ready(function() {
        $('#pelunasanForm').submit(function(event) {
            event.preventDefault(); // Mencegah pengiriman form default

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.pdfUrl) {
                        // Membuka URL di tab baru
                        window.open(response.pdfUrl, '_blank');
                    }
                    if (response.success) {
                        // Tampilkan pesan sukses menggunakan SweetAlert2
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'OK',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Lakukan refresh halaman setelah menekan OK
                                location.reload(); // Ini akan merefresh seluruh halaman
                            }
                        });
                    }
                },
                error: function(xhr) {
                    // Tangani error jika diperlukan
                    console.log(xhr.responseText);
                }
            });
        });

        // Menyimpan nilai default untuk setiap elemen form ketika halaman dimuat
        $('#pelunasanForm').find('input[type="text"], input[type="number"], textarea, select').each(function() {
            $(this).data('default-value', $(this).val());
        });
    });
</script> --}}

<script>
    var itemCounter = 0;
    function fetchDataByKode(kode) {
        $.ajax({
            url: '{{ route("toko_tegal.penjualan_produk.fetchData") }}', // Adjust the route accordingly
            method: 'GET',
            data: { kode_pemesanan: kode },
            success: function(response) {
                document.getElementById('dppemesanan_id').value = response.id;
                document.getElementById('kode_pemesanan').value = response.kode_pemesanan;
                document.getElementById('dp_pemesanan').value = response.dp_pemesanan;
                document.getElementById('nama_pelanggan').value = response.nama_pelanggan;
                document.getElementById('telp').value = response.telp;
                document.getElementById('alamat').value = response.alamat;
                document.getElementById('tanggal_kirim').value = response.tanggal_kirim;
                document.getElementById('nama_penerima').value = response.nama_penerima;
                document.getElementById('telp_penerima').value = response.telp_penerima;
                document.getElementById('alamat_penerima').value = response.alamat_penerima;
                document.getElementById('sub_total').value = formatRupiah(response.sub_total);
                document.getElementById('sub_totalasli').value = formatRupiah(response.sub_totalasli);
                document.getElementById('nominal_diskon').value = formatRupiah(response.nominal_diskon);
                document.getElementById('dp_pemesanan').value = formatRupiah(response.dp_pemesanan);
                document.getElementById('kekurangan_pemesanan').value = formatRupiah(response.kekurangan_pemesanan);

                if (response.products) {
                    var formHtml = '<div class="card mb-3">' +
                        '<div class="card-header">' +
                        '<h3 class="card-title">Detail Pemesanan</h3>' +
                        '<div class="float-right">'+
                            '<button type="button" class="btn btn-primary btn-sm" onclick="addRow()"><i class="fas fa-plus"></i></button>' +
                        '</div>'+
                        '</div>' +
                        '<div class="card-body">' +
                        '<table class="table table-bordered table-striped">' +
                        '<thead>' +
                        '<tr>' +
                        '<th style="font-size:14px" class="text-center">No</th>' +
                        '<!-- Kolom kode_produk tidak ditampilkan -->' +
                        '<th style="font-size:14px">Kode Lama</th>' +
                        '<th style="font-size:14px">Nama Produk</th>' +
                        '<th style="font-size:14px">Harga</th>' +
                        '<th style="font-size:14px">Jumlah</th>' +
                        '<th style="font-size:14px">Total</th>' +
                        '<th style="font-size:14px">Aksi</th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody id="tabel-pembelian">';

                    response.products.forEach((product, index) => {
                        formHtml += '<tr id="pembelian-' + index + '">' +
                            '<td style="width: 70px; font-size:14px" class="text-center urutan">' + (index + 1) + '</td>' +
                            '<td hidden>' + 
                            '   <input hidden style="font-size:14px" readonly type="text" class="form-control produk_id" name="produk_id[]" value="' + product.produk_id + '">' +
                            '</td>' +
                            '<td hidden>' +
                            '   <input hidden style="font-size:14px" readonly type="text" class="form-control kode_produk" name="kode_produk[]" value="' + product.kode_produk + '">' +
                            '</td>' +
                            '<td hidden>' +
                            '   <input hidden  style="font-size:14px" readonly type="text" class="form-control diskon" name="diskon[]" value="' + product.diskon + '">' +
                            '</td>' +
                            '<td>' +
                            '   <div class="form-group">' +
                            '       <input style="font-size:14px" readonly type="text" class="form-control kode_lama" name="kode_lama[]" value="' + product.kode_lama + '">' +
                            '   </div>' +
                            '</td>' +
                            '<td>' +
                            '   <div class="form-group">' +
                            '       <input style="font-size:14px" readonly type="text" class="form-control nama_produk" name="nama_produk[]" value="' + product.nama_produk + '">' +
                            '   </div>' +
                            '</td>' +
                            '<td>' +
                            '   <div class="form-group">' +
                            '       <input style="font-size:14px" readonly type="text" class="form-control harga" name="harga[]" value="' + product.harga + '">' +
                            '   </div>' +
                            '</td>' +
                            '<td>' +
                            '   <div class="form-group">' +
                            '       <input style="font-size:14px" type="number" readonly class="form-control jumlah" name="jumlah[]" id="jumlah_' + index + '" value="' + product.jumlah + '">' +
                            '   </div>' +
                            '</td>' +
                            '<td>' +
                            '   <div class="form-group">' +
                            '       <input style="font-size:14px" type="number" readonly class="form-control total" name="total[]" id="total_' + index + '" value="' + product.total + '">' +
                            '   </div>' +
                            '</td>' +
                            '<td>' +
                            '   <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(' + index + ')"><i class="fas fa-trash"></i></button>' +
                            '</td>' +
                            '</tr>';
                    });

                    $('#forms-container').html(formHtml);
                }

                updateGrandTotal();
            },
            error: function(xhr) {
                alert('Data tidak ditemukan.');
            }
        });
    }

    var currentEditingRow = null; // Variabel global untuk melacak baris yang sedang diedit

function addRow() {
    itemCounter++;
    var isFirstRow = itemCounter === 1;
    var newRow = '<tr id="row-' + (itemCounter + 1) + '"' + (isFirstRow ? ' style="display: none;"' : '') + '>' +
        '<td style="width: 70px; font-size:14px" class="text-center urutan">' + (itemCounter + 2) + '</td>' +
        '<td hidden>' + 
        '   <input hidden style="font-size:14px" type="text" class="form-control produk_id" name="produk_id[]" id="produk_id_' + itemCounter + '" value="">' +
        '</td>' +
        '<td hidden>' + 
        '   <input hidden style="font-size:14px" type="text" class="form-control kode_produk" name="kode_produk[]" id="kode_produk_' + itemCounter + '" value="">' +
        '</td>' +
        '<td hidden>' +
        '   <input hidden style="font-size:14px" type="text" class="form-control diskon" name="diskon[]" id="diskon_' + itemCounter + '" value="">' +
        '</td>' +
        '<td onclick="showCategoryModal(' + itemCounter + ')">' +
        '   <div class="form-group">' +
        '       <input style="font-size:14px" type="text" class="form-control kode_lama" name="kode_lama[]" id="kode_lama_' + itemCounter + '" value="" readonly>' +
        '   </div>' +
        '</td>' +
        '<td onclick="showCategoryModal(' + itemCounter + ')">' +
        '   <div class="form-group">' +
        '       <input style="font-size:14px" type="text" class="form-control nama_produk" name="nama_produk[]" id="nama_produk_' + itemCounter + '" value="" readonly>' +
        '   </div>' +
        '</td>' +
        '<td onclick="showCategoryModal(' + itemCounter + ')">' +
        '   <div class="form-group">' +
        '       <input style="font-size:14px" type="text" class="form-control harga" name="harga[]" id="harga_' + itemCounter + '" value="" readonly>' +
        '   </div>' +
        '</td>' +
        '<td>' +
        '   <div class="form-group">' +
        '       <input style="font-size:14px" type="number" class="form-control jumlah" name="jumlah[]" id="jumlah_' + itemCounter + '" value="1" min="1" oninput="updateTotal(' + itemCounter + ')">' +
        '   </div>' +
        '</td>' +
        '<td onclick="showCategoryModal(' + itemCounter + ')">' +
        '   <div class="form-group">' +
        '       <input style="font-size:14px" type="number" class="form-control total" name="total[]" id="total_' + itemCounter + '" value="" readonly>' +
        '   </div>' +
        '</td>' +
        '<td>' +
        '   <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(' + itemCounter + ')"><i class="fas fa-trash"></i></button>' +
        '</td>' +
        '</tr>';

    $('#tabel-pembelian').append(newRow);
    reIndexRows();

    // Show the newly added row if it is not the first one
    if (!isFirstRow) {
        $('#row-' + (itemCounter + 1)).show();
    }
}

function showCategoryModal(rowIndex) {
    currentEditingRow = rowIndex;  // Simpan indeks baris yang sedang diedit
    $('#tableProduk').modal('show');
}



function reIndexRows() {
    $('.urutan').each(function(index) {
        $(this).text(index + 1);
    });
}



    // function fetchProductData(rowId) {
    //     var kodeLama = document.getElementById('kode_lama_' + rowId).value;
    //     $.ajax({
    //         url: '{{ route("toko_tegal.penjualan_produk.fetchProductData") }}', // Sesuaikan dengan rute Anda
    //         method: 'GET',
    //         data: { kode_lama: kodeLama },
    //         success: function(response) {
    //             document.getElementById('produk_id_' + rowId).value = response.produk_id;
    //             document.getElementById('kode_produk_' + rowId).value = response.kode_produk;
    //             document.getElementById('nama_produk_' + rowId).value = response.nama_produk;
    //             document.getElementById('harga_' + rowId).value = response.harga;
    //             updateTotal(rowId);
    //         },
    //         error: function(xhr) {
    //             alert('Data tidak ditemukan.');
    //         }
    //     });
    // }

    function updateTotal(rowId) {
    var harga = parseFloat(document.getElementById('harga_' + rowId).value) || 0;
    var jumlah = parseFloat(document.getElementById('jumlah_' + rowId).value) || 0;
    var total = harga * jumlah;

    // Format total without decimal places
    document.getElementById('total_' + rowId).value = Math.round(total);
    updateGrandTotal();
}

function updateGrandTotal() {
    var grandTotal = 0;
    $('.total').each(function() {
        grandTotal += parseFloat($(this).val()) || 0;
    });

    // Format grandTotal without decimal places
    $('#grandTotal').val(Math.round(grandTotal));
}


    function removeRow(rowId) {
        $('#row-' + rowId).remove();
        reIndexRows();
        updateGrandTotal();
    }

    function reIndexRows() {
        $('.urutan').each(function(index) {
            $(this).text(index + 1);
        });
    }
</script>

<script>
     $(document).on('click', '.pilih-btn', function() {
    // Ambil data dari atribut `data-*` di tombol yang diklik
    var produk_id = $(this).data('id');
    var kode_produk = $(this).data('kode');
    var kode_lama = $(this).data('lama');
    var nama_produk = $(this).data('nama');
    var harga_member = $(this).data('member');
    var diskon_member = $(this).data('diskonmember');

    // Isi data tersebut ke dalam input field di baris yang sedang diisi
    if (currentEditingRow !== null) {
        $('#produk_id_' + currentEditingRow).val(produk_id);
        $('#kode_produk_' + currentEditingRow).val(kode_produk);
        $('#kode_lama_' + currentEditingRow).val(kode_lama);
        $('#nama_produk_' + currentEditingRow).val(nama_produk);
        $('#harga_' + currentEditingRow).val(harga_member);
    }

    // Tutup modal setelah memilih produk
    $('#tableProduk').modal('hide');
});


</script>



    <script>
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number).replace(/(\.|,)00$/g, '');
        }
    
        function unformatRupiah(value) {
            return parseFloat(value.replace(/[^0-9,-]+/g, "").replace(',', '.')) || 0;
        }
    
        function formatAndUpdateKembali() {
            let kekuranganPemesanan = unformatRupiah(document.getElementById('kekurangan_pemesanan').value) || 0;
            let pelunasan = unformatRupiah(document.getElementById('pelunasan').value) || 0;
    
            let kembali = pelunasan - kekuranganPemesanan;
            document.getElementById('kembali').value = formatRupiah(kembali);
    
            // Validasi pelunasan
            let pelunasanElement = document.getElementById('pelunasan');
            if (pelunasan < kekuranganPemesanan) {
                pelunasanElement.setCustomValidity('Nominal bayar tidak cukup');
            } else {
                pelunasanElement.setCustomValidity('');
            }
        }
    
        function formatRupiahInput(value, prefix = 'Rp ') {
            let numberString = value.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
        }
    
        $('#pelunasan').on('input', function() {
            let input = $(this).val();
            $(this).val(formatRupiahInput(input));
            formatAndUpdateKembali(); // Update nilai kembali setelah input
        });
    
        function getData1() {
            let metodeSelect = document.getElementById('nama_metode');
            let selectedOption = metodeSelect.options[metodeSelect.selectedIndex];
    
            let feePercentage = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
            let kekuranganPemesanan = unformatRupiah(document.getElementById('kekurangan_pemesanan').value) || 0;
    
            let totalFee = (kekuranganPemesanan * feePercentage) / 100;
    
            document.getElementById('fee').value = feePercentage;
            document.getElementById('total_fee').value = formatRupiah(totalFee);
    
            let totalToPay = kekuranganPemesanan + totalFee;
            document.getElementById('pelunasan').value = formatRupiah(totalToPay);
    
            if (metodeSelect.value) {
                document.getElementById('payment-fields').style.display = 'block';
            } else {
                document.getElementById('payment-fields').style.display = 'none';
            }
        }
    
        $(document).ready(function() {
            $('#kode_pemesanan').on('input', function() {
                var kode = $(this).val();
                if (kode) {
                    fetchDataByKode(kode);
                }
            });
    
            $('#nama_metode').on('change', function() {
                getData1();
            });
    
            $('#pelunasan').on('input', function() {
                let metodeSelected = $('#nama_metode').val();
                if (!metodeSelected) {
                    formatAndUpdateKembali();
                }
            });
    
            // Validasi form sebelum disubmit
            $('form').on('submit', function(event) {
                formatAndUpdateKembali(); // Pastikan validasi dilakukan
    
                // Cek jika ada pesan validasi di pelunasan
                let pelunasanElement = document.getElementById('pelunasan');
                if (pelunasanElement.validationMessage) {
                    event.preventDefault(); // Hentikan pengiriman form
                    return false;
                }
    
                $('#kekurangan_pemesanan').val(unformatRupiah($('#kekurangan_pemesanan').val()));
                $('#pelunasan').val(unformatRupiah($('#pelunasan').val()));
                $('#kembali').val(unformatRupiah($('#kembali').val()));
                $('#total_fee').val(unformatRupiah($('#total_fee').val()));
            });
        });
    </script>
    <script>
               $(document).ready(function() {
                // Inisialisasi datatables
                var produkTable = $('#datatables5').DataTable();

        
                $('#tableProduk').on('shown.bs.modal', function () {
                    produkTable.columns.adjust().draw();
                });
            });
        


    </script>
    
    
    <script>
        function showCategoryModalpemesanan() {
                $('#tableDeposit').modal('show');
            }

        function GetReturn(id, kode_pemesanan, dp_pemesanan) {
        // Mengisi input hidden dppemesanan_id
        document.getElementById('dppemesanan_id').value = id;
        
        // Mengisi input kode_pemesanan
        document.getElementById('kode_pemesanan').value = kode_pemesanan;

        // Memanggil fetchDataByKode untuk mendapatkan detail pemesanan
        fetchDataByKode(kode_pemesanan);

        // Menutup modal setelah memilih data (opsional)
        $('#tableDeposit').modal('hide');
        }
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

    <script>
        document.getElementById('kategori').addEventListener('change', function() {
            var selectedValue = this.value;

            if (selectedValue === 'penjualan') {
                window.location.href = "{{ route('toko_tegal.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
            } else if (selectedValue === 'pelunasan') {
                window.location.href = "{{ route('toko_tegal.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
            }
        });
    </script>


@endsection
