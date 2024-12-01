@extends('layouts.app')

@section('title', 'Inquery Pemesanan Produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquery Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_tegal/pemesanan_produk') }}">Inquery Pemesanan
                                Produk</a>
                        </li>
                        <li class="breadcrumb-item active">Inquery Pemesanan Produk</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
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
            <form action="{{ url('toko_tegal/inquery_pemesananproduk/' . $inquery->id) }}" method="post"
                autocomplete="off">
                @csrf
                @method('put')
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Pelanggan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-auto mt-2">
                                            <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                            <select class="form-control" id="kategori" name="kategori">
                                                <option value="">- Pilih -</option>
                                                <option value="member"
                                                    {{ old('kategori') == 'member' || (isset($inquery) && $inquery->kategori == 'member') ? 'selected' : '' }}>
                                                    Member</option>
                                                <option value="nonmember"
                                                    {{ old('kategori') == 'nonmember' || (isset($inquery) && $inquery->kategori == 'nonmember') ? 'selected' : '' }}>
                                                    Non Member</option>
                                            </select>
                                        </div>

                                        <div class="col-auto mt-2" id="kode_pemesanan">
                                            <label for="kode_pemesanan">No. Pemesanan</label>
                                            <input type="text" class="form-control" id="kode_pemesanan"
                                                name="kode_pemesanan" readonly value="{{ $inquery->kode_pemesanan }}">
                                        </div>
                                        <div class="col-auto mt-2" id="kodePelangganRow" hidden>
                                            <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                            <input type="text" class="form-control" id="qrcode_pelanggan"
                                                name="qrcode_pelanggan" placeholder="scan kode Pelanggan"
                                                onchange="getData(this.value)">
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="namaPelangganRow">
                                        <div class="col-md-12 mb-3">
                                            <input readonly placeholder="Masukan Nama Pelanggan" type="text"
                                                class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                                value="{{ $inquery->nama_pelanggan }}"
                                                onclick="showCategoryModalpemesanan()">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telpRow">
                                        <div class="col-md-12 mb-3">
                                            <label for="telp">No. Telepon</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp" name="telp" class="form-control"
                                                    placeholder="Masukan nomor telepon" value="{{ $inquery->telp }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="alamatRow">
                                        <div class="col-md-12 mb-3">
                                            <label for="catatan">Alamat</label>
                                            <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ $inquery->alamat }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Pengiriman</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 mb-3">
                                        <label for="tanggal_kirim">Tanggal Pengiriman:</label>
                                        <div class="input-group date" id="reservationdatetime"
                                            data-target-input="nearest">
                                            <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                                class="form-control datetimepicker-input"
                                                data-target="#reservationdatetime" value="{{ $inquery->tanggal_kirim }}"
                                                placeholder="DD/MM/YYYY HH:mm">
                                            <div class="input-group-append" data-target="#reservationdatetime"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-12">
                                            <label for="nama_penerima">Nama Penerima</label> <span
                                                style="font-size: 10px;">(kosongkan jika sama dengan nama pelanggan)</span>
                                            <input type="text" class="form-control" id="nama_penerima"
                                                name="nama_penerima" placeholder="masukan nama Penerima"
                                                value="{{ $inquery->nama_penerima }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telp_penerima">
                                        <div class="col-md-12">
                                            <label for="telp_penerima">No. Telepon</label> <span
                                                style="font-size: 10px;">(kosongkan jika sama dengan Nomer telepon
                                                pelanggan)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp_penerima" name="telp_penerima"
                                                    class="form-control" placeholder="Masukan nomor telepon"
                                                    value="{{ $inquery->telp_penerima }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="alamat_penerima">
                                        <div class="col-md-12 mb-3">
                                            <label for="alamat_penerima">Alamat Penerima</label><span
                                                style="font-size: 10px;"> (kosongkan jika sama dengan alamat
                                                pelanggan)</span>
                                            <textarea placeholder="Masukan alamat penerima" type="text" class="form-control" id="alamat_penerima"
                                                name="alamat_penerima">{{ $inquery->alamat_penerima }}</textarea>
                                        </div>
                                    </div>
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
                                @foreach ($details as $detail)
                                    <tr id="pembelian-{{ $loop->index }}">
                                        <td style="width: 70px; font-size:14px" class="text-center" id="urutan">
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
                                        <td>
                                            <div class="form-group">
                                                <input style="font-size:14px" type="text" readonly
                                                    onclick="barang({{ $loop->index }})" class="form-control"
                                                    id="kode_lama-{{ $loop->index }}" name="kode_lama[]"
                                                    value="{{ $detail['kode_lama'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="font-size:14px" type="text" readonly
                                                    onclick="barang({{ $loop->index }})" class="form-control"
                                                    id="nama_produk-{{ $loop->index }}" name="nama_produk[]"
                                                    value="{{ $detail['nama_produk'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input readonly style="font-size:14px" type="number"
                                                    onclick="barang({{ $loop->index }})" class="form-control harga"
                                                    id="harga-{{ $loop->index }}" name="harga[]"
                                                    value="{{ $detail['harga'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input style="font-size:14px" type="number" class="form-control jumlah"
                                                    id="jumlah-{{ $loop->index }}" name="jumlah[]"
                                                    value="{{ $detail['jumlah'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input readonly style="font-size:14px" type="number"
                                                    onclick="barang({{ $loop->index }})" class="form-control total"
                                                    id="total-{{ $loop->index }}" name="total[]"
                                                    value="{{ $detail['total'] }}">
                                            </div>
                                        </td>
                                        <td style="width: 100px">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="barang({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm"
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
                    <input style="font-size:14px" type="text" class="form-control text-right" id="grand_total"
                        name="grand_total" readonly placeholder="" value="{{ old('grand_total') }}">
                </div>
                {{-- pembayaran --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div style="margin-bottom:50px" class="card-header">
                                <div class="row">
                                    <div class="col mb-3 ml-auto d-flex align-items-center">
                                        <label for="sub_total" class="mr-2 label-width">Sub Total</label>
                                        <input type="text" class="form-control large-font input-width" id="sub_total"
                                            name="sub_total" value="{{ old('sub_total', 'Rp') }}">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli"
                                            name="sub_totalasli" value="{{ old('sub_totalasli') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 ml-auto d-flex align-items-center">
                                        <label for="dp_pemesanan" class="mr-2 label-width">DP</label>
                                        <input type="text" class="form-control large-font input-width format-rupiah"
                                            id="dp_pemesanan" name="dp_pemesanan"
                                            value="{{ old('dp_pemesanan', $inquery->dppemesanan->dp_pemesanan ?? null) }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 ml-auto d-flex align-items-center">
                                        <label for="kekurangan_pemesanan" class="mr-2 label-width">Kekurangan</label>
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
                                <select class="form-control" name="metode_id" style="width: 100%;" id="nama_metode">
                                    <option value="">- Pilih -</option>
                                    @foreach ($metodes as $metode)
                                        <option value="{{ $metode->id }}" data-fee="{{ $metode->fee }}"
                                            {{ old('metode_id', $inquery->metode_id) == $metode->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control" id="total_fee" name="total_fee"
                                            placeholder="" value="{{ old('total_fee') }}" readonly>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label for="keterangan">Keterangan</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                                            placeholder="" value="{{ old('keterangan') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="catatan">Catatan</label>
                                <textarea placeholder="" type="text" class="form-control" id="catatan" name="catatan">{{ old('catatan') }}</textarea>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="kasir">Bagian Input :</label>
                                <input type="text" class="form-control" readonly name="kasir"
                                    value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
                        <table id="tables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
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
                                    <tr data-id="{{ $item->id }}" data-kode="{{ $item->kode_lama }}"
                                        data-nama="{{ $item->nama_produk }}" data-kode_produk="{{ $item->kode_produk }}"
                                        data-member="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                        data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                        data-nonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                        data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}" onclick="getBarang({{ $loop->index }})">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->tokoslawi->first()->member_harga_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->member_diskon_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->non_harga_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->non_diskon_slw }}</td>
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
        $(document).ready(function() {
            // Set locale Moment.js ke bahasa Indonesia
            moment.locale('id');

            // Inisialisasi datetimepicker
            $('#reservationdatetime').datetimepicker({
                format: 'DD/MM/YYYY HH:mm',
                locale: 'id',
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-arrow-up',
                    down: 'fa fa-arrow-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });

            // Pastikan locale diterapkan ulang setelah inisialisasi datetimepicker
            $('#reservationdatetime').datetimepicker('locale', 'id');

            $('#pemesananForm').submit(function(event) {
                event.preventDefault(); // Mencegah pengiriman form default

                // Check if tanggal_kirim is filled
                if (!$('#tanggal_kirim').val()) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Tanggal pengambilan harus diisi!',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                    return; // Stop the submission
                }

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
                                    location
                                        .reload(); // Ini akan merefresh seluruh halaman
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
            $('#pemesananForm').find('input[type="text"], input[type="number"], textarea, select').each(function() {
                $(this).data('default-value', $(this).val());
            });
        });
    </script>




    <script>
        // memunculkan datatable pelaanggan dan produk
        $(document).ready(function() {
            // Inisialisasi datatables
            var pelangganTable = $('#datatables4').DataTable();
            var produkTable = $('#datatables5').DataTable();

            $('#tableMarketing').on('shown.bs.modal', function() {
                pelangganTable.columns.adjust().draw();
            });

            $('#tableProduk').on('shown.bs.modal', function() {
                produkTable.columns.adjust().draw();
            });
        });

        function showCategoryModalpemesanan() {
            $('#tableMarketing').modal('show');
        }

        function getSelectedDataPemesanan(nama_pelanggan, telp, alamat) {
            document.getElementById('nama_pelanggan').value = nama_pelanggan;
            document.getElementById('telp').value = telp;
            document.getElementById('alamat').value = alamat;
            $('#tableMarketing').modal('hide');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Mencegah aksi default dari tombol Enter
                    addPesanan(); // Memanggil addPesanan saat tombol Enter ditekan
                }
                if (event.key === 'F1') { // Misalnya, F1 untuk menampilkan modal produk
                    event.preventDefault(); // Mencegah aksi default dari tombol F1
                    var urutan = $('#tabel-pembelian tr').length; // Ambil urutan terakhir atau default
                    showCategoryModal(urutan); // Menampilkan modal produk
                }
            });
        });
    </script>




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
            var id = selectedRow.data('id');
            var kode = selectedRow.data('kode');
            var kode_produk = selectedRow.data('kode_produk');
            var nama = selectedRow.data('nama');
            var member = selectedRow.data('member');
            var diskonmember = selectedRow.data('diskonmember');
            var nonmember = selectedRow.data('nonmember');
            var diskonnonmember = selectedRow.data('diskonnonmember');

            var kategori = $('#kategori').val();
            var harga = kategori === 'member' ? member : nonmember;
            var diskon = kategori === 'member' ? diskonmember : diskonnonmember;

            // Update the form fields for the active specification
            $('#produk_id-' + activeSpecificationIndex).val(id);
            $('#kode_lama-' + activeSpecificationIndex).val(kode);
            $('#kode_produk-' + activeSpecificationIndex).val(kode_produk);
            $('#nama_produk-' + activeSpecificationIndex).val(nama);
            $('#harga-' + activeSpecificationIndex).val(harga);
            $('#diskon-' + activeSpecificationIndex).val(diskon);

            $('#tableProduk').modal('hide');
        }
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
                    url: "{{ url('toko_tegal/inquery_pemesanantegal/deletedetail/') }}/" + detailId,
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

@endsection
