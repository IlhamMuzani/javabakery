@extends('layouts.app')

@section('title', 'Inquery Pelunasan Pemesanan')
@include('sweetalert::alert')

@section('content')
    <style>
        .card {
            min-height: 100%;
        }

        .label-width {
            width: 100px;
            /* Atur sesuai kebutuhan */
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
                    <h1 class="m-0">Inquery Pelunasan Pemesanan Produk</h1>
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

            <form action="{{ url('toko_pemalang/inquery_pelunasanpemalang/' . $inquery->id) }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <label style="font-size:14px" class="form-label" for="kode_dppemesanan">Kode Pemesanan</label>
                        <div class="form-group d-flex">
                            <input class="form-control" hidden id="penjualanproduk_id" name="penjualanproduk_id"
                                type="text" placeholder=""
                                value="{{ old('penjualanproduk_id', $inquery->penjualanproduk_id) }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control" hidden id="dppemesanan_id" name="dppemesanan_id" type="text"
                                placeholder="" value="{{ old('dppemesanan_id', $inquery->dppemesanan_id) }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control col-md-4" id="kode_pemesanan" name="kode_pemesanan" type="text"
                                readonly
                                value="{{ old('kode_dppemesanan', $inquery->dppemesanan->kode_dppemesanan ?? null) }}"
                                style="margin-right: 10px; font-size:14px" />
                            <div class="col-md">
                                {{-- <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton"
                                    onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search" style=""></i>Cari
                                </button> --}}
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
                                        <div hidden class="form-group">
                                            <label for="pelanggan_id">Id</label>
                                            <input type="text" class="form-control form-control-full-width"
                                                id="pelanggan_id" readonly name="pelanggan_id" placeholder=""
                                                value="{{ old('pelanggan_id') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_pelanggan">Nama Pelanggan</label>
                                            <input style="font-size:14px" type="text"
                                                class="form-control form-control-full-width" id="nama_pelanggan" readonly
                                                name="nama_pelanggan" placeholder=""
                                                value="{{ old('nama_pelanggan', $inquery->penjualanproduk->nama_pelanggan ?? null) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp">No. Telp</label>
                                            <input style="font-size:14px" type="text"
                                                class="form-control form-control-full-width" id="telp" readonly
                                                name="telp" placeholder=""
                                                value="{{ old('telp', $inquery->penjualanproduk->telp ?? null) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat">Alamat</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat" readonly
                                                name="alamat" placeholder="" value="">{{ old('alamat', $inquery->penjualanproduk->alamat ?? null) }}</textarea>
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
                                            <input style="font-size:14px" type="text"
                                                class="form-control form-control-full-width" id="tanggal_kirim" readonly
                                                name="tanggal_kirim" placeholder=""
                                                value="{{ old('tanggal_kirim', $inquery->penjualanproduk->tanggal_penjualan ?? null) }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_penerima">Nama Penerima</label>
                                            <input style="font-size:14px" type="text"
                                                class="form-control form-control-full-width" id="nama_penerima" readonly
                                                name="nama_penerima" placeholder="" value="{{ old('nama_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp_penerima">Telepon Penerima</label>
                                            <input style="font-size:14px" type="text"
                                                class="form-control form-control-full-width" id="telp_penerima" readonly
                                                name="telp_penerima" placeholder="" value="{{ old('telp_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat_penerima">Alamat Penerima</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat_penerima"
                                                readonly name="alamat_penerima" placeholder="" value="">{{ old('alamat_penerima') }}</textarea>
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

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Produk <span>
                                    </span></h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            {{-- <th hidden style="font-size:14px">produk Id</th>
                                            <th hidden style="font-size:14px">Kode Produk</th> --}}
                                            {{-- <th style="font-size:14px">Diskon</th> --}}
                                            <th style="font-size:14px">Kode Lama</th>
                                            <th style="font-size:14px">Nama Produk</th>
                                            <th style="font-size:14px">Harga</th>
                                            <th style="font-size:14px">Jumlah</th>
                                            <th style="font-size:14px">Total</th>
                                            <th style="font-size:14px">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelian">
                                        @foreach ($detail_penjualans as $detail)
                                            <tr id="pembelian-{{ $loop->index }}">
                                                <td style="width: 70px; font-size:14px" class="text-center"
                                                    id="urutan">
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td hidden>
                                                    <div class="form-group" hidden>
                                                        <input type="text" class="form-control" name="detail_ids[]"
                                                            value="{{ $detail['id'] }}">
                                                    </div>

                                                </td>
                                                <td hidden>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" readonly
                                                            class="form-control" id="produk_id-{{ $loop->index }}"
                                                            name="produk_id[]" value="{{ $detail['produk_id'] }}">
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" readonly
                                                            class="form-control" id="kode_produk-{{ $loop->index }}"
                                                            name="kode_produk[]" value="{{ $detail['kode_produk'] }}">
                                                    </div>
                                                </td>
                                                <td hidden>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" readonly
                                                            class="form-control diskon" id="diskon-{{ $loop->index }}"
                                                            name="diskon[]" value="{{ $detail['diskon'] }}">
                                                    </div>
                                                </td>
                                                <td onclick="barang({{ $loop->index }})">
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" readonly
                                                            class="form-control" id="kode_lama-{{ $loop->index }}"
                                                            name="kode_lama[]" value="{{ $detail['kode_lama'] }}">
                                                    </div>
                                                </td>
                                                <td onclick="barang({{ $loop->index }})">
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="text" readonly
                                                            class="form-control" id="nama_produk-{{ $loop->index }}"
                                                            name="nama_produk[]" value="{{ $detail['nama_produk'] }}">
                                                    </div>
                                                </td>
                                                <td onclick="barang({{ $loop->index }})">
                                                    <div class="form-group">
                                                        <input readonly style="font-size:14px" type="number"
                                                            class="form-control harga" id="harga-{{ $loop->index }}"
                                                            name="harga[]" value="{{ $detail['harga'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="number"
                                                            class="form-control jumlah" id="jumlah-{{ $loop->index }}"
                                                            name="jumlah[]" value="{{ $detail['jumlah'] }}">
                                                    </div>
                                                </td>
                                                <td onclick="barang({{ $loop->index }})">
                                                    <div class="form-group">
                                                        <input readonly style="font-size:14px" type="number"
                                                            class="form-control total" id="total-{{ $loop->index }}"
                                                            name="total[]" value="{{ $detail['total'] }}">
                                                    </div>
                                                </td>
                                                <td style="width: 100px">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="barang({{ $loop->index }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <button style="margin-left:5px" type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="removePesanan({{ $loop->index }}, {{ $detail['id'] }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div hidden class="form-group">
                            <label style="font-size:14px" class="mt-0" for="nopol">Grand Total</label>
                            <input style="font-size:14px" type="text" class="form-control text-right"
                                id="grand_total" name="grand_total" readonly placeholder=""
                                value="{{ old('grand_total') }}">
                        </div>
                        {{-- pembayaran --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div style="margin-bottom:50px" class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="sub_total" class="mr-2 label-width">Sub Total</label>
                                                <input type="text" class="form-control large-font input-width"
                                                    id="sub_total" name="sub_total"
                                                    value="{{ old('sub_total', 'Rp') }}">
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="col mb-3 d-flex align-items-center">
                                                <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                                <input type="text" class="form-control large-font" id="sub_totalasli"
                                                    name="sub_totalasli" value="{{ old('sub_totalasli') }}">
                                            </div>
                                        </div>
                                        <div class="row" hidden>
                                            <div class="col mb-3 d-flex align-items-center">
                                                <label for="nominal_diskon" class="mr-2">jumlah diskon</label>
                                                <input type="text" class="form-control large-font" id="nominal_diskon"
                                                    name="nominal_diskon" value="{{ old('nominal_diskon') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="dp_pemesanan" class="mr-2 label-width">DP</label>
                                                <input type="text"
                                                    class="form-control large-font input-width format-rupiah"
                                                    id="dp_pemesanan" name="dp_pemesanan" readonly
                                                    value="{{ old('dp_pemesanan', $inquery->dppemesanan->dp_pemesanan ?? null) }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kekurangan_pemesanan"
                                                    class="mr-2 label-width">Kekurangan</label>
                                                <input type="text" class="form-control large-font input-width"
                                                    id="kekurangan_pemesanan" name="kekurangan_pemesanan"
                                                    value="{{ old('kekurangan_pemesanan') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="form-group" style="margin-top:10px; margin-left:20px; margin-right:20px;">
                                        <label sty for="metode_id">Jenis Pembayaran</label>
                                        <select class="form-control" name="metode_id" style="width: 100%;"
                                            id="nama_metode">
                                            <option value="">- Pilih -</option>
                                            @foreach ($metodes as $metode)
                                                <option value="{{ $metode->id }}" data-fee="{{ $metode->fee }}"
                                                    {{ old('metode_id', $inquery->penjualanproduk->metode_id) == $metode->id ? 'selected' : '' }}>
                                                    {{ $metode->nama_metode }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-top:1px; margin-left:20px; margin-right:20px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="fee">Fee (%)</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="fee" readonly
                                                        name="fee" placeholder="" value="{{ old('fee') }}">
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
                                                <input type="text" class="form-control" id="total_fee"
                                                    name="total_fee" placeholder="" value="{{ old('total_fee') }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <label for="keterangan">Keterangan</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="keterangan"
                                                    name="keterangan" placeholder="" value="{{ old('keterangan') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="pelunasan" class="mr-2 label-width">Bayar</label>
                                                <input type="text" class="form-control large-font input-width "
                                                    id="pelunasan" name="pelunasan"
                                                    value="{{ old('pelunasan', $inquery->pelunasan) }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kembali" class="mr-2 label-width">Kembali</label>
                                                <input type="text" class="form-control large-font input-width"
                                                    id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
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
                                            <td>{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}
                                            </td>
                                            <td>{{ $return->pemesananproduk ? $return->pemesananproduk->nama_pelanggan : 'No Data' }}
                                            </td>
                                            <td>{{ $return->pemesananproduk ? $return->pemesananproduk->tanggal_kirim : 'No Data' }}
                                            </td>
                                            <td>{{ number_format($return->dp_pemesanan, 0, ',', '.') }}</td>
                                            <td td class="text-center">
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
                        <div class="m-2">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        </div>
                        <table id="tables" class="table table-bordered table-striped" style="font-size: 12px;">
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
                                        $tokopemalang = $item->tokopemalang->first();
                                        $stokpesanan_tokopemalang = $item->stokpesanan_tokopemalang
                                            ? $item->stokpesanan_tokopemalang->jumlah
                                            : 0; // Jika stok ada, tampilkan, jika tidak tampilkan 0

                                    @endphp
                                    <tr data-id="{{ $item->id }}" data-kode="{{ $item->kode_produk }}"
                                        data-lama="{{ $item->kode_lama }}" data-catatan="{{ $item->catatanproduk }}"
                                        data-nama="{{ $item->nama_produk }}"
                                        data-member="{{ $tokopemalang ? $tokopemalang->member_harga_bnjr : '' }}"
                                        data-diskonmember="{{ $tokopemalang ? $tokopemalang->member_diskon_bnjr : '' }}"
                                        data-nonmember="{{ $tokopemalang ? $tokopemalang->non_harga_bnjr : '' }}"
                                        data-diskonnonmember="{{ $tokopemalang ? $tokopemalang->non_diskon_bnjr : '' }}"
                                        onclick="getBarang({{ $loop->index }})">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>
                                            <span
                                                class="member_harga_bnjr">{{ $tokopemalang ? $tokopemalang->member_harga_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="member_diskon_bnjr">{{ $tokopemalang ? $tokopemalang->member_diskon_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="non_harga_bnjr">{{ $tokopemalang ? $tokopemalang->non_harga_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="non_diskon_bnjr">{{ $tokopemalang ? $tokopemalang->non_diskon_bnjr : '' }}</span>
                                        </td>

                                        <td class="text-center">
                                            <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getBarang({{ $loop->index }})">
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

    <script>
        $(document).on("input", ".harga, .jumlah, .diskon", function() {
            var currentRow = $(this).closest('tr');
            var harga = parseFloat(currentRow.find(".harga").val()) || 0;
            var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;
            var diskon = parseFloat(currentRow.find(".diskon").val()) || 0;
            var total = harga * jumlah - diskon;
            currentRow.find(".total").val(total);

            updateGrandTotal()
            updateFee(); // Setelah itu hitung fee berdasarkan nilai sub_total

        });
    </script>
    </script>


    <script>
        function updateGrandTotal() {
            var grandTotal = 0;

            // Loop through all elements with name "total[]"
            $('input[name^="total"]').each(function() {
                var nominalValue = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                grandTotal += nominalValue;
            });

            // Ambil nilai total_fee
            var totalFee = parseFloat($('#total_fee').val().replace(/\./g, '').replace(',', '.')) || 0;

            // Tambahkan grand_total dengan total_fee
            var subTotal = grandTotal + totalFee;

            // Update sub_total field
            $('#grand_total').val(formatRupiahsss(grandTotal));
            $('#sub_total').val(formatRupiahsss(subTotal));

            // Update kekurangan_pemesanan by subtracting dp from sub_total
            updateKekuranganPemesanan(subTotal);
        }

        function updateKekuranganPemesanan(subTotal) {
            var dpValue = parseFloat($('#dp_pemesanan').val().replace(/\./g, '').replace(',', '.')) || 0;
            var kekurangan = subTotal - dpValue;

            // Update kekurangan_pemesanan field
            $('#kekurangan_pemesanan').val(formatRupiahsss(kekurangan));

            // Update kembalian when bayarpelunasan input changes
            updateKembalian(kekurangan);
        }

        function updateKembalian(kekurangan) {
            var bayarValue = parseFloat($('#pelunasan').val().replace(/\./g, '').replace(',', '.')) || 0;
            var kembalian = bayarValue - kekurangan;

            // Update kembalian field
            $('#kembali').val(formatRupiahsss(kembalian));
        }

        function updateFee() {
            var selectedOption = $('#nama_metode option:selected');
            var metodeValue = selectedOption.val();
            var subTotal = parseFloat($('#sub_total').val().replace(/\./g, '').replace(',', '.')) || 0;
            var fee_persen = 0;
            var fee = 0;

            if (metodeValue === "1") {
                // Jika metode pembayaran adalah 1, fee 1%
                fee_persen = 1;
                fee = (1 / 100) * subTotal;
            } else if (metodeValue === "2") {
                // Jika metode pembayaran adalah 2, fee 20%
                fee_persen = 20;
                fee = (20 / 100) * subTotal;
            } else if (metodeValue === "3" || metodeValue === null || metodeValue === "") {
                // Jika metode pembayaran adalah 3 atau null atau kosong, fee 0%
                fee_persen = 0;
                fee = 0;
            } else {
                // Ambil fee default dari atribut data-fee (jika ada)
                var feePercent = parseFloat(selectedOption.data('fee')) || 0;
                fee = (feePercent / 100) * subTotal;
            }

            // Update total_fee field
            $('#fee').val(formatRupiahsss(fee_persen));
            $('#total_fee').val(formatRupiahsss(fee));

            // Recalculate grand_total and sub_total when fee changes
            updateGrandTotal();
        }

        function formatRupiahsss(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
            return formatted;
        }

        // Recalculate on any input change
        $('body').on('input', 'input[name^="total"], #dp_pemesanan, #pelunasan', function() {
            updateGrandTotal();
        });

        // Recalculate fee on metode selection change
        $('#nama_metode').on('change', function() {
            updateFee();
        });

        // Initialize values on page load
        $(document).ready(function() {
            updateGrandTotal(); // Hitung grand total terlebih dahulu
            updateFee(); // Setelah itu hitung fee berdasarkan nilai sub_total
        });
    </script>


    {{-- sudah benar namun metode id belum auto 
    <script>
        function updateGrandTotal() {
            var grandTotal = 0;

            // Loop through all elements with name "total[]"
            $('input[name^="total"]').each(function() {
                var nominalValue = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                grandTotal += nominalValue;
            });

            // Ambil nilai total_fee
            var totalFee = parseFloat($('#total_fee').val().replace(/\./g, '').replace(',', '.')) || 0;

            // Tambahkan grand_total dengan total_fee
            var subTotal = grandTotal + totalFee;

            // Update sub_total field
            $('#grand_total').val(formatRupiahsss(grandTotal));
            $('#sub_total').val(formatRupiahsss(subTotal));

            // Update kekurangan_pemesanan by subtracting dp from sub_total
            updateKekuranganPemesanan(subTotal);
        }

        function updateKekuranganPemesanan(subTotal) {
            var dpValue = parseFloat($('#dp_pemesanan').val().replace(/\./g, '').replace(',', '.')) || 0;
            var kekurangan = subTotal - dpValue;

            // Update kekurangan_pemesanan field
            $('#kekurangan_pemesanan').val(formatRupiahsss(kekurangan));

            // Update kembalian when bayarpelunasan input changes
            updateKembalian(kekurangan);
        }

        function updateKembalian(kekurangan) {
            var bayarValue = parseFloat($('#pelunasan').val().replace(/\./g, '').replace(',', '.')) || 0;
            var kembalian = bayarValue - kekurangan;

            // Update kembalian field
            $('#kembali').val(formatRupiahsss(kembalian));
        }

        function updateFee() {
            var selectedOption = $('#nama_metode option:selected');
            var metodeValue = selectedOption.val();
            var subTotal = parseFloat($('#sub_total').val().replace(/\./g, '').replace(',', '.')) || 0;
            var fee_persen = 0;
            var fee = 0;

            if (metodeValue === "1") {
                // Jika metode pembayaran adalah 1, fee 1%
                fee_persen = 1;
                fee = (1 / 100) * subTotal;
            } else if (metodeValue === "2") {
                // Jika metode pembayaran adalah 2, fee 20%
                fee_persen = 20;
                fee = (20 / 100) * subTotal;
            } else if (metodeValue === "3" || metodeValue === null || metodeValue === "") {
                // Jika metode pembayaran adalah 3 atau null atau kosong, fee 0%
                fee_persen = 0;
                fee = 0;
            } else {
                // Ambil fee default dari atribut data-fee (jika ada)
                var feePercent = parseFloat(selectedOption.data('fee')) || 0;
                fee = (feePercent / 100) * subTotal;
            }

            // Update total_fee field
            $('#fee').val(formatRupiahsss(fee_persen));
            $('#total_fee').val(formatRupiahsss(fee));

            // Recalculate grand_total and sub_total when fee changes
            updateGrandTotal();
        }

        function formatRupiahsss(number) {
            var formatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
            return formatted;
        }

        // Recalculate on any input change
        $('body').on('input', 'input[name^="total"], #dp_pemesanan, #pelunasan', function() {
            updateGrandTotal();
        });

        // Recalculate fee on metode selection change
        $('#nama_metode').on('change', function() {
            updateFee();
        });

        // Initialize values on page load
        $(document).ready(function() {
            updateGrandTotal();
        });
    </script> --}}


    <script>
        var activeSpecificationIndex = 0;

        function barang(param) {
            activeSpecificationIndex = param;
            // Show the modal and filter rows if necessary
            $('#tableProduk').modal('show');
        }
    </script>


    <script>
        function getBarang(rowIndex) {
            var selectedRow = $('#tables tbody tr:eq(' + rowIndex + ')');
            var produk_id = selectedRow.data('id');
            var kode_produk = selectedRow.data('kode');
            var kode_lama = selectedRow.data('lama');
            var nama_produk = selectedRow.data('nama');
            var harga_member = selectedRow.data('member');
            var diskon_member = selectedRow.data('diskonmember');

            // Update the form fields for the active specification
            $('#produk_id-' + activeSpecificationIndex).val(produk_id);
            $('#kode_produk-' + activeSpecificationIndex).val(kode_produk);
            $('#kode_lama-' + activeSpecificationIndex).val(kode_lama);
            $('#nama_produk-' + activeSpecificationIndex).val(nama_produk);
            $('#harga-' + activeSpecificationIndex).val(harga_member);
            $('#diskon-' + activeSpecificationIndex).val(diskon_member);

            $('#tableProduk').modal('hide');
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
                window.location.href =
                    "{{ route('toko_pemalang.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
            } else if (selectedValue === 'pelunasan') {
                window.location.href =
                    "{{ route('toko_pemalang.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
            }
        });
    </script>

    <script>
        // Cek apakah session refresh ada, lalu lakukan refresh halaman
        @if (session('refresh'))
            window.onload = function() {
                setTimeout(function() {
                    location.reload();
                }, 3000); // Lakukan refresh setelah 3 detik
            };
        @endif
    </script>


    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }

        function updateUrutan() {
            var urutan = document.querySelectorAll('#urutan');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }

        var counter = 0;

        function addPesanan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-pembelian tr:last');
                var lastRowIndex = lastRow.find('#urutan').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelian(jumlah_ban, jumlah_ban - 1);
            updateUrutan();
        }

        function removePesanan(identifier) {
            var row = $('#pembelian-' + identifier);
            var detailId = row.find("input[name='detail_ids[]']").val();

            row.remove();

            if (detailId) {
                $.ajax({
                    url: "{{ url('toko_pemalang/inquery_pelunasanpemalang/deletedetail/') }}/" + detailId,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Data deleted successfully');
                    },
                    error: function(error) {
                        console.error('Failed to delete data:', error);
                    }
                });
            }
            updateGrandTotal(); // Hitung grand total terlebih dahulu
            updateFee(); // Setelah itu hitung fee berdasarkan nilai sub_total
            updateUrutan();
        }

        function itemPembelian(identifier, key, value = null) {
            var produk_id = '';
            var kode_produk = '';
            var diskon = '';
            var kode_lama = '';
            var nama_produk = '';
            var harga = '';
            var jumlah = '';
            var total = '';

            if (value !== null) {
                produk_id = value.produk_id;
                kode_produk = value.kode_produk;
                diskon = value.diskon;
                kode_lama = value.kode_lama;
                nama_produk = value.nama_produk;
                harga = value.harga;
                jumlah = value.jumlah;
                total = value.total;

            }

            // urutan 
            var item_pembelian = '<tr id="pembelian-' + key + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan">' + key + '</td>';

            // produk_id 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input readonly type="text" class="form-control" style="font-size:14px" id="produk_id-' +
                key +
                '" name="produk_id[]" value="' + produk_id + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_produk 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input readonly type="text" class="form-control" style="font-size:14px" id="kode_produk-' +
                key +
                '" name="kode_produk[]" value="' + kode_produk + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // diskon 
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input readonly type="number" class="form-control diskon" style="font-size:14px" id="diskon-' +
                key +
                '" name="diskon[]" value="' + diskon + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kode_lama 
            item_pembelian += '<td onclick="barang(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input readonly type="text" class="form-control" style="font-size:14px" id="kode_lama-' +
                key +
                '" name="kode_lama[]" value="' + kode_lama + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // nama_produk 
            item_pembelian += '<td onclick="barang(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly style="font-size:14px" id="nama_produk-' +
                key +
                '" name="nama_produk[]" value="' + nama_produk + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // harga 
            item_pembelian += '<td onclick="barang(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian +=
                '<input readonly type="number" class="form-control harga" readonly style="font-size:14px" id="harga-' +
                key +
                '" name="harga[]" value="' + harga + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // jumlah 
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="number" class="form-control jumlah" style="font-size:14px" id="jumlah-' +
                key +
                '" name="jumlah[]" value="' + jumlah + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // total 
            item_pembelian += '<td onclick="barang(' + key +
                ')">';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input readonly type="number" class="form-control total" style="font-size:14px" id="total-' +
                key +
                '" name="total[]" value="' + total + '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';


            item_pembelian += '<td style="width: 100px">';
            item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="barang(' + key +
                ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian +=
                '<button style="margin-left:10px" type="button" class="btn btn-danger btn-sm" onclick="removePesanan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);
        }
    </script>



    <script>
        // Fungsi untuk memformat nilai menjadi format Rupiah
        function formatRupiah(value) {
            if (!value) return ''; // Jika nilai kosong, kembalikan string kosong
            return parseInt(value).toLocaleString('id-ID'); // Format ke Rupiah dengan pemisah ribuan
        }

        // Fungsi yang dipanggil saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen dengan kelas 'format-rupiah'
            const inputs = document.querySelectorAll('.format-rupiah');

            inputs.forEach(input => {
                if (input.value) {
                    // Format nilai yang ada di input ke format Rupiah
                    input.value = formatRupiah(input.value);
                }
            });
        });
    </script>

    <script>
        // filter rute 
        function filterMemo() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("tables");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var displayRow = false;

                // Loop through columns (td 1, 2, and 3)
                for (j = 1; j <= 3; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break; // Break the loop if a match is found in any column
                        }
                    }
                }

                // Set the display style based on whether a match is found in any column
                tr[i].style.display = displayRow ? "" : "none";
            }
        }
        document.getElementById("searchInput").addEventListener("input", filterMemo);
    </script>

@endsection
