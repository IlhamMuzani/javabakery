@extends('layouts.app')

@section('title', 'pemesanan produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_banjaran/pemesanan_produk') }}">Pemesanan Produk</a></li>
                        <li class="breadcrumb-item active">Pemesanan Produk</li>
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
            <form action="{{ url('toko_banjaran/inquery_pemesananproduk/' . $inquery->id) }}" method="post" autocomplete="off">
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
                                                <option value="member" {{ (old('kategori') == 'member' || (isset($inquery) && $inquery->kategori == 'member')) ? 'selected' : '' }}>Member</option>
                                                <option value="nonmember" {{ (old('kategori') == 'nonmember' || (isset($inquery) && $inquery->kategori == 'nonmember')) ? 'selected' : '' }}>Non Member</option>
                                            </select>
                                        </div>
                            
                                        <div class="col-auto mt-2" id="kode_pemesanan">
                                            <label for="kode_pemesanan">No. Pemesanan</label>
                                            <input type="text" class="form-control" id="kode_pemesanan" name="kode_pemesanan" readonly value="{{ $inquery->kode_pemesanan }}">
                                        </div>
                                        <div class="col-auto mt-2" id="kodePelangganRow" hidden>
                                            <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                            <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="namaPelangganRow">
                                        <div class="col-md-12 mb-3">
                                            <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ $inquery->nama_pelanggan }}" onclick="showCategoryModalpemesanan()">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telpRow">
                                        <div class="col-md-12 mb-3">
                                            <label for="telp">No. Telepon</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ $inquery->telp }}">
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
                                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                            <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                                class="form-control datetimepicker-input"
                                                data-target="#reservationdatetime"
                                                value="{{ $inquery->tanggal_kirim }}"
                                                placeholder="DD/MM/YYYY HH:mm">
                                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-12">
                                            <label for="nama_penerima">Nama Penerima</label> <span style="font-size: 10px;">(kosongkan jika sama dengan nama pelanggan)</span>
                                            <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="masukan nama Penerima" value="{{ $inquery->nama_penerima }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telp_penerima">
                                        <div class="col-md-12">
                                            <label for="telp_penerima">No. Telepon</label> <span style="font-size: 10px;">(kosongkan jika sama dengan Nomer telepon pelanggan)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp_penerima" name="telp_penerima" class="form-control" placeholder="Masukan nomor telepon" value="{{ $inquery->telp_penerima }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="alamat_penerima">
                                        <div class="col-md-12 mb-3">
                                            <label for="alamat_penerima">Alamat Penerima</label><span style="font-size: 10px;"> (kosongkan jika sama dengan alamat pelanggan)</span>
                                            <textarea placeholder="Masukan alamat penerima" type="text" class="form-control" id="alamat_penerima" name="alamat_penerima">{{ $inquery->alamat_penerima }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian">
                                @foreach ($inquery->detailpemesananproduk as $detail)
                                    <tr id="pembelian-{{ $loop->index }}">
                                        <td class="text-center" id="urutan">{{ $loop->index + 1 }}</td>
                                    
                                        <td >
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="kode_lama-{{ $loop->index }}" name="kode_lama[]"
                                                    value="{{ $detail['kode_lama'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="nama_produk-{{ $loop->index }}" name="nama_produk[]"
                                                    value="{{ $detail['nama_produk'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number"  class="form-control"
                                                    id="jumlah-{{ $loop->index }}" name="jumlah[]"
                                                    value="{{ $detail['jumlah'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="diskon-{{ $loop->index }}" name="diskon[]"
                                                    value="{{ $detail['diskon'] }}">
                                            </div>
                                        </td>
          
                                        <td>
                                            <div class="form-group">
                                                <input type="number" readonly class="form-control harga" id="harga-0"
                                                    name="harga[]" value="{{ $detail['harga'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control total" id="total-0"
                                                    name="total[]" data-row-id="0"
                                                    value="{{ $detail['total'] }}">
                                            </div>
                                        </td>
                                        <td style="width: 120px">
                                            <button type="button" class="btn btn-primary"
                                                onclick="barang({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button style="margin-left:5px" type="button" class="btn btn-danger"
                                                onclick="removeBan({{ $loop->index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" 
                                               value="{{ old('sub_total', 'Rp' . number_format($inquery->sub_total, 0, ',', '.')) }}" 
                                               oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" 
                                               value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="dp_pemesanan" class="mr-2">DP</label>
                                        <input type="text" class="form-control large-font" id="dp_pemesanan" name="dp_pemesanan" 
                                        value="{{ old('dp_pemesanan', 'Rp' . number_format($inquery->dppemesanans->dp_pemesanan, 0, ',', '.')) }}" 
                                        oninput="formatAndUpdateKembali()">
                                 
                                 
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kekurangan_pemesanan" class="mr-2">Kurang</label>
                                        <input type="text" class="form-control large-font" id="kekurangan_pemesanan" name="kekurangan_pemesanan" 
                                               value="{{ old('kekurangan_pemesanan', 'Rp' . number_format($inquery->sub_total - $inquery->dppemesanans->dp_pemesanan    , 0, ',', '.')) }}" 
                                               readonly>
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
                                    <option value="{{ $metode->id }}" 
                                            data-fee="{{ $metode->fee }}" 
                                            {{ old('metode_id', $inquery->metode_id) == $metode->id ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control" id="fee" readonly name="fee" 
                                               value="{{ old('fee', $inquery->metode ? $inquery->metode->fee : '') }}">
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
                                    value="{{ old('total_fee', 'Rp' . number_format(floatval($inquery->total_fee ?? 0), 0, ',', '.')) }}"
                                    readonly>



                                </div>
                            </div>
                        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="keterangan">Keterangan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" 
                                           value="{{ old('keterangan', $inquery->keterangan) }}">
                                </div>
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
                        <table id="datatables5" class="table table-bordered table-striped">
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
                                    <tr class="pilih-btn"
                                        data-id="{{ $item->id }}"
                                        data-kode="{{ $item->kode_lama }}"
                                        data-nama="{{ $item->nama_produk }}"
                                        data-member="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                        data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                        data-nonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                        data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->tokoslawi->first()->member_harga_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->member_diskon_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->non_harga_slw }}</td>
                                        <td>{{ $item->tokoslawi->first()->non_diskon_slw }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                            data-id="{{ $item->id }}"
                                            data-kode="{{ $item->kode_lama }}"
                                            data-nama="{{ $item->nama_produk }}"
                                            data-member="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                            data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                            data-nonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                            data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}">
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
            $('#pemesananForm').find('input[type="text"], input[type="number"], textarea, select').each(function() {
                $(this).data('default-value', $(this).val());
            });
        });
    </script>

<script>
    var isPaymentMethodChanged = false;

    function getData1() {
        var metodeId = document.getElementById('nama_metode').value;
        var fee = document.getElementById('fee');
        var keterangan = document.getElementById('keterangan');
        var paymentFields = document.getElementById('payment-fields');
        var paymentRow = document.getElementById('payment-row');
        var changeRow = document.getElementById('change-row');
        
        if (metodeId && document.querySelector('#nama_metode option:checked').text === 'Tunai') {
            paymentFields.style.display = 'none';
        } else if (metodeId) {
            $.ajax({
                url: "{{ url('toko_banjaran/metodebayar/metode') }}" + "/" + metodeId,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    fee.value = '';
                    keterangan.value = '';
                    paymentFields.style.display = 'block';

                    if (response && response.fee) {
                        fee.value = response.fee;
                    }
                    if (response && response.keterangan) {
                        keterangan.value = response.keterangan;
                    }

                    isPaymentMethodChanged = true; // Set flag to true when method is changed

                    // Update calculations only if payment method is changed
                    updateCalculations();
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan dalam permintaan AJAX:', error);
                }
            });
        } else {
            paymentFields.style.display = 'none';
        }

        // Display payment and change rows for all payment methods
        paymentRow.style.display = 'block';
        changeRow.style.display = 'block';
    }

    function updateCalculations() {
        var subTotal = parseFloat(document.getElementById('sub_total').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
        var fee = parseFloat(document.getElementById('fee').value.replace('%', '').trim()) || 0;

        if (isPaymentMethodChanged) {
            // Clear previous total_fee
            document.getElementById('total_fee').value = 'Rp0';

            if (fee > 0) {
                var totalFee = (subTotal * fee / 100) || 0;
                var finalTotal = subTotal + totalFee;

                function formatCurrency(value) {
                    var formattedValue = value.toFixed(2).replace(/\.00$/, '');
                    return 'Rp' + formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                document.getElementById('total_fee').value = formatCurrency(totalFee);
                document.getElementById('sub_total').value = formatCurrency(finalTotal);
            }

            isPaymentMethodChanged = false; // Reset flag after calculation
        }
    }

    function formatAndUpdateKembali() {
        var subTotal = parseFloat(document.getElementById('sub_total').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
        var dpPemesanan = parseFloat(document.getElementById('dp_pemesanan').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
        var kekuranganPemesanan = subTotal - dpPemesanan;

        document.getElementById('kekurangan_pemesanan').value = formatCurrency(kekuranganPemesanan);
    }

    function formatCurrency(value) {
        var formattedValue = value.toFixed(2).replace(/\.00$/, '');
        return 'Rp' + formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.getElementById('nama_metode').addEventListener('change', getData1);
    document.getElementById('sub_total').addEventListener('input', updateCalculations);
    document.getElementById('dp_pemesanan').addEventListener('input', formatAndUpdateKembali);

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize with default method data if any
        var defaultMethodId = document.getElementById('nama_metode').value;
        if (!isPaymentMethodChanged && defaultMethodId) {
            getData1(); // Load default method data if not changed
        }
    });
</script>


    
    <script>
        // memunculkan datatable pelaanggan dan produk
        $(document).ready(function() {
            // Inisialisasi datatables
            var pelangganTable = $('#datatables4').DataTable();
            var produkTable = $('#datatables5').DataTable();
    
            $('#tableMarketing').on('shown.bs.modal', function () {
                pelangganTable.columns.adjust().draw();
            });
    
            $('#tableProduk').on('shown.bs.modal', function () {
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
    
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = data_pembelian ? data_pembelian.length : 0;
    
        if (data_pembelian) {
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan++;
                itemPembelian(urutan, key, value);
            });
        }
    
        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            $('#tableProduk').modal('show');
            $('#tableProduk').attr('data-urutan', urutan);
        }
    
        // Event listener for pilih-btn
        $(document).on('click', '.pilih-btn', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var member = $(this).data('member');
            var diskonmember = $(this).data('diskonmember');
            var nonmember = $(this).data('nonmember');
            var diskonnonmember = $(this).data('diskonnonmember');
            
            getSelectedData(id, kode, nama, member, diskonmember, nonmember, diskonnonmember);
        });
    
        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(id, kode_lama, nama_produk, member, diskonmember, nonmember, diskonnonmember) {
            var urutan = $('#tableProduk').attr('data-urutan');
            var kategori = $('#kategori').val();
            var harga = kategori === 'member' ? member : nonmember;
            var diskon = kategori === 'member' ? diskonmember : diskonnonmember;
    
            $('#produk_id-' + urutan).val(id);
            $('#kode_lama-' + urutan).val(kode_lama);
            $('#nama_produk-' + urutan).val(nama_produk);
            $('#harga-' + urutan).val(harga);
            $('#diskon-' + urutan).val(diskon);
            hitungTotal(urutan);
            
            // Hide the modal
            $('#tableProduk').modal('hide');
            document.getElementById('jumlah-' + urutan).focus();
        }
    
        // Fungsi untuk menghitung total berdasarkan harga dan jumlah
        function hitungTotal(urutan) {
            var harga = parseFloat($('#harga-' + urutan).val().replace(/[^0-9]/g, '')) || 0;
            var diskon = parseFloat($('#diskon-' + urutan).val()) || 0;
            var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;
    
            var hargaSetelahDiskon = harga - (harga * (diskon / 100));
            var total = hargaSetelahDiskon * jumlah;
            var totalasli = harga * jumlah;
    
            $('#total-' + urutan).val(total);
            $('#totalasli-' + urutan).val(totalasli);
            hitungSubTotal();
        }
    
        // Fungsi untuk menghitung subtotal semua barang
        function hitungSubTotal() {
            var subTotal = 0;
            var subTotalAsli = 0;
    
            $('[id^=total-]').each(function() {
                var total = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
                subTotal += total;
            });
    
            $('[id^=totalasli-]').each(function() {
                var totalAsli = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
                subTotalAsli += totalAsli;
            });
    
            $('#sub_total').val(formatRupiah(subTotal));
            $('#sub_totalasli').val(formatRupiah(subTotalAsli));
        }
    
        function addPesanan() {
            jumlah_ban++;
            itemPembelian(jumlah_ban, jumlah_ban - 1);
        }
    
        function removeBan(params) {
            jumlah_ban--;
            $('#pembelian-' + params).remove();
            if (jumlah_ban === 0) {
                $('#tabel-pembelian').html('<tr><td class="text-center" colspan="8">- Barang Jadi belum ditambahkan -</td></tr>');
            } else {
                $('#tabel-pembelian tr').each(function(index) {
                    $(this).find('#urutan').text(index + 1);
                });
            }
            hitungSubTotal();
        }
    
        function itemPembelian(urutan, key, value = null) {
            var produk_id = '';
            var kode_lama = '';
            var nama_produk = '';
            var jumlah = '';
            var diskon = '';
            var harga = '';
            var total = '';
            var totalasli = '';
    
            if (value !== null) {
                produk_id = value.produk_id;
                kode_lama = value.kode_lama;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                diskon = value.diskon;
                harga = value.harga;
                total = value.total;
                totalasli = value.totalasli;
            }
    
            var item_pembelian = `<tr id="pembelian-${urutan}">
                <td style="width: 70px; font-size:14px" class="text-center" id="urutan-${urutan}">${urutan}</td>
                <td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-${urutan}" name="produk_id[]" value="${produk_id}"></div></td>
                <td onclick="showCategoryModal(${urutan})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-${urutan}" name="kode_lama[]" value="${kode_lama}"></div></td>
                <td onclick="showCategoryModal(${urutan})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-${urutan}" name="nama_produk[]" value="${nama_produk}"></div></td>
                <td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-${urutan}" name="jumlah[]" value="${jumlah}" oninput="hitungTotal(${urutan})" onkeydown="handleEnter(event, ${urutan})"></div></td>
                <td onclick="showCategoryModal(${urutan})" style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-${urutan}" name="diskon[]" value="${diskon}"></div></td>
                <td onclick="showCategoryModal(${urutan})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-${urutan}" name="harga[]" value="${harga}"></div></td>
                <td onclick="showCategoryModal(${urutan})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-${urutan}" name="total[]" value="${total}"></div></td>
                <td hidden onclick="showCategoryModal(${urutan})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" hidden id="totalasli-${urutan}" name="totalasli[]" value="${totalasli}"></div></td>
                <td style="width: 100px">
                    <button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(${urutan})"><i class="fas fa-plus"></i></button>
                    <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(${urutan})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
    
            $('#tabel-pembelian').append(item_pembelian);
        }
    </script>
    
    
@endsection
