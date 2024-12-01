@extends('layouts.app')

@section('title', 'Pemesanan Produk')

@section('content')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>

<!-- Bootstrap 4 (CSS & JS) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Tempus Dominus Bootstrap 4 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css">
<script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>

<style>
    .form-group {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.form-group label {
    margin-right: 1rem;
    white-space: nowrap; /* Prevents label text from wrapping */
    width: 150px; /* Adjust based on your design */
}

.form-group input {
    flex: 1; /* Allows input to fill the remaining space */
}


</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_slawi/pemesanan_produk') }}">Pemesanan Produk</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        .large-font {
            font-size: 1.5em; /* Atur ukuran font sesuai kebutuhan */
            font-weight: bold;
        }
    </style>
    <section class="content" onload="showPaymentFields()">
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
            <form id="pemesananForm" action="{{ url('toko_slawi/pemesanan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                {{-- detail pelanggan --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" id="toggleButton">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <div class="card-body collapse show" id="cardContent">
                                <!-- Your form fields go here -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-6 mt-2">
                                        <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                        <select class="form-control" id="kategori" name="kategori">
                                            <option value="">- Pilih -</option>
                                            <option value="member" {{ old('kategori') == 'member' ? 'selected' : null }}>Member</option>
                                            <option value="nonmember" {{ old('kategori') == 'nonmember' ? 'selected' : null }}>Non Member</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2" id="kodePelangganRow" hidden>
                                        <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                        <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                                    </div>
                                </div>
                    
                                <div class="row mb-3 align-items-center" id="namaPelangganRow" style="display: none;">
                                    <div class="col-md">
                                        <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                            <i class="fas fa-search"></i>Cari pelanggan
                                        </button> 
                                    </div>      
                                    <div class="col-md-12 mb-3 "> 
                                        <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                        <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}">
                                    </div>     
                                </div>
                    
                                <div class="row align-items-center" id="telpRow" hidden>
                                    <div class="col-md-12 mb-3">
                                        <label for="telp">No. Telepon</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+62</span>
                                            </div>
                                            <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ old('telp') }}">
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="row mb-3 align-items-center" id="alamatRow" hidden>
                                    <div class="col-md-12 mb-3">
                                        <label for="catatan">Alamat</label>
                                        <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ old('alamat') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="card">
                           
                            <div class="card-body">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-6">
                                        <label for="tanggal_kirim">Tanggal Pengambilan:</label>
                                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                            <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                                   class="form-control datetimepicker-input"
                                                   data-target="#reservationdatetime"
                                                   value="{{ old('tanggal_kirim') }}" 
                                                   placeholder="DD/MM/YYYY HH:mm">
                                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @if ($errors->has('tanggal_kirim'))
                                            <div class="text-danger">{{ $errors->first('tanggal_kirim') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
       
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><span></span></h3>
                        <div class="float-right">
                            <button  type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            {{-- <th style="font-size:14px">Kode Produk</th> --}}
                                            <th style="font-size:14px">Kode Produk</th>
                                            <th style="font-size:14px">Nama Produk</th>
                                            <th style="font-size:14px">Jumlah</th>
                                            <th style="font-size:14px">Diskon</th>
                                            <th style="font-size:14px">Harga</th>
                                            <th style="font-size:14px">Total</th>
                                            <th style="font-size:14px; text-align:center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelian">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="mr-2" style="width: 150px;">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2" style="width: 150px;">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="dp_pemesanan" class="mr-2" style="width: 150px;">DP</label>
                                        <input type="text" class="form-control large-font" id="dp_pemesanan" name="dp_pemesanan" value="{{ old('dp_pemesanan') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kekurangan_pemesanan" class="mr-2" style="width: 150px;">Kurang</label>
                                        <input type="text" class="form-control large-font" id="kekurangan_pemesanan" name="kekurangan_pemesanan" value="{{ old('kekurangan_pemesanan') }}" readonly>
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
                                    <input type="text" class="form-control" id="total_fee" name="total_fee" placeholder="" value="{{ old('total_fee') }}" readonly>
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
                                <input type="text" class="form-control" readonly name="kasir" value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
                            </div> 
                        </div>     
                    </div>
                </div>
                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="" class="btn btn-primary" id="simpanButton">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            </form>
        </div>

        <div class="modal fade" id="tableMarketing" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Pelanggan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Kode Lama</th>
                                    <th>Nama Pelanggan</th>
                                    <th>No Telpon</th>
                                    <th>Alamat</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $item)
                                    <tr onclick="getSelectedDataPemesanan('{{ $item->nama_pelanggan }}', '{{ $item->telp }}', '{{ $item->alamat }}', '{{ $item->kode_pelanggan }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_pelanggan }}</td>
                                        <td>{{ $item->kode_pelangganlama }}</td>
                                        <td>{{ $item->nama_pelanggan }}</td>
                                        <td>{{ $item->telp }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm" >
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
                                        $tokoslawi = $item->tokoslawi->first();
                                        $stokpesanan_tokoslawi = $item->stokpesanan_tokoslawi ? $item->stokpesanan_tokoslawi->jumlah : 0; // Jika stok ada, tampilkan, jika tidak tampilkan 0

                                    @endphp
                                    <tr class="pilih-btn"
                                        data-id="{{ $item->id }}"
                                        data-kode="{{ $item->kode_produk }}"
                                        data-lama="{{ $item->kode_lama }}"
                                        data-catatan="{{ $item->catatanproduk }}"
                                        data-nama="{{ $item->nama_produk }}"
                                        data-member="{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}"
                                        data-diskonmember="{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}"
                                        data-nonmember="{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}"
                                        data-diskonnonmember="{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>
                                            <span class="member_harga_slw">{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_slw">{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_slw">{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_slw">{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}</span>
                                        </td>
                                    
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                data-id="{{ $item->id }}"
                                                data-kode="{{ $item->kode_produk }}"
                                                data-lama="{{ $item->kode_lama }}"
                                                data-catatan="{{ $item->catatanproduk }}"
                                                data-nama="{{ $item->nama_produk }}"
                                                data-member="{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}"
                                                data-diskonmember="{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}"
                                                data-nonmember="{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}"
                                                data-diskonnonmember="{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}">
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
    function getData1() {
    var metodeId = document.getElementById('nama_metode').value;
    var fee = document.getElementById('fee');
    var keterangan = document.getElementById('keterangan');
    var paymentFields = document.getElementById('payment-fields');
    var paymentRow = document.getElementById('payment-row');
    var changeRow = document.getElementById('change-row');

    // Reset sub_total ke nilai asli
    var subTotalAsli = document.getElementById('sub_totalasli').value;
    document.getElementById('sub_total').value = subTotalAsli;

    if (!metodeId || document.querySelector('#nama_metode option:checked').text === '- Pilih -') {
        // Jika opsi "Pilih" dipilih
        paymentFields.style.display = 'none';
        fee.value = '';
        keterangan.value = '';
        paymentRow.style.display = 'none';
        changeRow.style.display = 'none';
        updateCalculations(); // Pastikan perhitungan direset
        return;
    }

    if (document.querySelector('#nama_metode option:checked').text === 'Tunai') {
        paymentFields.style.display = 'none';
    } else {
        $.ajax({
            url: "{{ url('toko_slawi/metodebayar/metode') }}" + "/" + metodeId,
            type: "GET",
            dataType: "json",
            success: function(response) {
                console.log('Respons dari server:', response);

                fee.value = '';
                keterangan.value = '';
                paymentFields.style.display = 'block';

                if (response && response.fee) {
                    fee.value = response.fee;
                }
                if (response && response.keterangan) {
                    keterangan.value = response.keterangan;
                }

                // Update perhitungan setelah data diambil
                updateCalculations();
            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan dalam permintaan AJAX:', error);
            }
        });
    }

    // Tampilkan payment dan change rows untuk semua metode pembayaran
    paymentRow.style.display = 'block';
    changeRow.style.display = 'block';

    // Update perhitungan untuk merefleksikan perubahan
    updateCalculations();
}


   function updateCalculations() {
       var subTotalAsli = parseFloat(document.getElementById('sub_totalasli').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
   var fee = parseFloat(document.getElementById('fee').value.replace('%', '').trim()) || 0;
   var totalFee = (subTotalAsli * fee / 100) || 0;
   var finalTotal = subTotalAsli + totalFee;

   // Format nilai menjadi mata uang
   function formatCurrency(value) {
       var formattedValue = value.toFixed(2).replace(/\.00$/, '');
       return 'Rp' + formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
   }

   // Update total fee dan final sub total
   document.getElementById('total_fee').value = formatCurrency(totalFee);
   document.getElementById('sub_total').value = formatCurrency(finalTotal);

       // Validate DP
       validateDP();
   }

   function formatAndUpdateKembali() {
       var subTotal = parseFloat(document.getElementById('sub_total').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
       var dpPemesanan = parseFloat(document.getElementById('dp_pemesanan').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
       var kekuranganPemesanan = subTotal - dpPemesanan;

       document.getElementById('kekurangan_pemesanan').value = formatCurrency(kekuranganPemesanan);

       // Validate DP
       validateDP();
   }

   function validateDP() {
   var subTotal = parseFloat(document.getElementById('sub_total').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
   var dpPemesanan = parseFloat(document.getElementById('dp_pemesanan').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
   var minDP = subTotal * 0.5;
   var dpPemesananElement = document.getElementById('dp_pemesanan');
   
   if (dpPemesanan < minDP) {
       dpPemesananElement.setCustomValidity('DP inimal 50% dari Total');
   } else if (dpPemesanan > subTotal) {
       dpPemesananElement.setCustomValidity('DP Tidak Boleh Melebihi Total');
   } else {
       dpPemesananElement.setCustomValidity('');
   }
   }

   document.getElementById('dp_pemesanan').addEventListener('input', function() {
       formatAndUpdateKembali();
       validateDP();
   });


   // Add event listeners for initialization
   document.getElementById('nama_metode').addEventListener('change', getData1);
   document.getElementById('sub_total').addEventListener('input', updateCalculations);
   document.getElementById('dp_pemesanan').addEventListener('input', formatAndUpdateKembali);
   
   // Initialize with "Tunai" as default method
   document.addEventListener('DOMContentLoaded', function() {
       var defaultMethod = 'Tunai';
       var options = document.getElementById('nama_metode').options;
       for (var i = 0; i < options.length; i++) {
           if (options[i].text === defaultMethod) {
               options[i].selected = true;
               break;
           }
       }
       getData1();
   });
</script>
    <script>
        function showCategoryModalCatatan(urutan) {
            // Tampilkan modal
            $('#tableCatatan').modal('show');
    
            // Simpan urutan yang dipilih di elemen tersembunyi di modal
            $('#tableCatatan').data('urutan', urutan);
    
            // Kosongkan input catatan di modal
            $('#modalCatatanInput').val('');
        }
    
        function saveCatatan() {
            var urutan = $('#tableCatatan').data('urutan');
            var catatan = $('#modalCatatanInput').val();
    
            // Masukkan catatan ke input yang sesuai
            $('#catatanproduk-' + urutan).val(catatan);
    
            // Tutup modal
            $('#tableCatatan').modal('hide');
        }
    </script>   
    
    <script>
        $('#tableCatatan').on('show.bs.modal', function (event) {
            var urutan = $(event.relatedTarget).data('urutan');
            $(this).data('urutan', urutan);
    
            // Kosongkan input catatan di modal
            $('#modalCatatanInput').val('');
        });
    
        function showCategoryModalCatatan(urutan) {
            // Tampilkan modal dan set data urutan
            $('#tableCatatan').data('urutan', urutan).modal('show');
        }
    
        function saveCatatan() {
            var urutan = $('#tableCatatan').data('urutan');
            var catatan = $('#modalCatatanInput').val();
    
            // Masukkan catatan ke input yang sesuai
            $('#catatanproduk-' + urutan).val(catatan);
    
            // Tutup modal
            $('#tableCatatan').modal('hide');
        }
    </script>

    <script>
        // menghide form inputan
        document.addEventListener('DOMContentLoaded', function() {
            var kategoriSelect = document.getElementById('kategori');
            var namaPelangganRow = document.getElementById('namaPelangganRow');
            var telpRow = document.getElementById('telpRow');
            var alamatRow = document.getElementById('alamatRow');
            var namaPelangganInput = document.getElementById('nama_pelanggan');


            kategoriSelect.addEventListener('change', function() {
                if (kategoriSelect.value === 'member') {
                    // namaPelangganRow.hidden = false;
                    // namaPelangganRow.readOnly = false;
                    kodePelangganRow.hidden = false;
                    namaPelangganInput.readOnly = true;
                    namaPelangganRow.style.display = 'block';
                    telpRow.hidden = false;
                    alamatRow.hidden = false;
                } else if (kategoriSelect.value === 'nonmember') {
                    // namaPelangganRow.hidden = false;
                    // namaPelangganRow.readonly = false;
                    kodePelangganRow.hidden = true;
                    namaPelangganInput.readOnly = false;
                    namaPelangganRow.style.display = 'block';
                    telpRow.hidden = false;
                    alamatRow.hidden = false;
                } else {
                    namaPelangganRow.style.display = 'none';
                    namaPelangganRow.readonly = true;
                    telpRow.hidden = true;
                    alamatRow.hidden = true;
                    kodePelangganRow.hidden = true;

                }
            });

        });
    </script>
    
    <script>
            //    memunculkan button utk mencari pelanggan yg sudah ada
            document.addEventListener('DOMContentLoaded', function() {
                var kategoriSelect = document.getElementById('kategori');
                var searchButtonRow = document.querySelector('.col-md');

                kategoriSelect.addEventListener('change', function() {
                    if (kategoriSelect.value === 'member') {
                        searchButtonRow.hidden = false;
                    } else {
                        searchButtonRow.hidden = true;
                    }
                });

                if (kategoriSelect.value === 'nonmember') {
                    searchButtonRow.hidden = true;
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
        
            function getSelectedDataPemesanan(nama_pelanggan,  telp, alamat, kode_pelanggan) {
                document.getElementById('nama_pelanggan').value = nama_pelanggan;
                document.getElementById('kode_pelanggan').value = kode_pelanggan;
                document.getElementById('telp').value = telp;
                document.getElementById('alamat').value = alamat;
                $('#tableMarketing').modal('hide');
            }
        
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
            var delayTimer;
            var scanned = false; // Tambahkan variabel untuk menandai apakah sudah discan atau belum
        
            $(document).ready(function() {
                // Fokuskan input saat halaman dimuat
                $('#qrcode_pelanggan').focus();
        
                $('#qrcode_pelanggan').on('input', function() {
                    var qrcode_pelanggan = $(this).val().trim();
        
                    // Hapus timer sebelumnya jika ada
                    clearTimeout(delayTimer);
        
                    // Tunggu sebentar sebelum mengambil data
                    delayTimer = setTimeout(function() {
                        if (qrcode_pelanggan !== '') {
                            // Periksa apakah sudah discan sebelumnya
                            if (!scanned) {
                                getData(qrcode_pelanggan);
                            } else {
                                // Data sudah discan sebelumnya, tidak perlu melakukan apa-apa
                                console.log('Data sudah discan sebelumnya.');
                            }
                        } else {
                            // Handle jika qrcode_pelanggan kosong
                            $('#nama_pelanggan').val('');
                            $('#telp').val('');
                            $('#alamat').val('');
                            scanned = false; // Reset status scanned
                        }
                    }, 200); // Waktu penundaan dalam milidetik (misalnya 200ms)
                });
            });
        
            function getData(qrcode_pelanggan) {
                // Ajax request untuk mengambil data dari backend
                $.ajax({
                    url: '{{ route("get.customer.data") }}', // Menggunakan route() untuk mengambil URL endpoint
                    method: 'GET',
                    data: { qrcode_pelanggan: qrcode_pelanggan },
                    success: function(response) {
                        // Isi nilai nama pelanggan, telepon, dan alamat berdasarkan respons dari backend
                        $('#nama_pelanggan').val(response.nama_pelanggan);
                        $('#telp').val(response.telp);
                        $('#alamat').val(response.alamat);
                        scanned = true; // Tandai bahwa sudah discan
                    },
                    error: function(xhr, status, error) {
                        // Handle error jika ada
                        console.error('Error:', error);
                    }
                });
            }
    </script>
      

    <script>
        function formatAndUpdateKembali() {
                let subTotalElement = document.getElementById('sub_total');
                let subTotalAsliElement = document.getElementById('sub_totalasli');
                let dp_pemesananElement = document.getElementById('dp_pemesanan');
                let kekurangan_pemesananElement = document.getElementById('kekurangan_pemesanan');

                // Mengambil nilai sub_total
                let subTotal = removeRupiahFormat(subTotalElement.value);
                let subTotalAsli = removeRupiahFormat(subTotalAsliElement.value);


                // Format dan ambil nilai dp_pemesanan
                let dp_pemesananValue = dp_pemesananElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
                let dp_pemesanan = parseFloat(dp_pemesananValue) || 0; // Jika tidak valid, set 0

                // Format input 'dp_pemesanan'
                dp_pemesananElement.value = formatRupiah(dp_pemesananValue);

                // Hitung kekurangan_pemesananan
                let kekurangan_pemesanan = subTotal - dp_pemesanan;
                
                // Format hasil kekurangan_pemesananan sebagai Rupiah
                kekurangan_pemesananElement.value = kekurangan_pemesanan >= 0 ? formatRupiah(kekurangan_pemesanan) : 'Rp. 0';

                // Validasi DP
                validateDP();
            }

            // Fungsi untuk menghapus format Rupiah dan mengembalikan nilai numerik
            function removeRupiahFormat(value) {
                return parseFloat(value.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
            }

            // Format angka menjadi format Rupiah
            function formatRupiah(value) {
                let numberString = value.toString().replace(/[^,\d]/g, ''),
                    split = numberString.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                return split[1] !== undefined ? 'Rp. ' + rupiah + ',' + split[1] : 'Rp. ' + rupiah;
            }

            // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
            document.addEventListener('DOMContentLoaded', function() {
                let subTotalElement = document.getElementById('sub_total');
                let subTotalAsliElement = document.getElementById('sub_totalasli');

                let subTotal = removeRupiahFormat(subTotalElement.value);
                subTotalElement.value = formatRupiah(subTotal);
            });

            document.querySelector('form').addEventListener('submit', function(event) {
                let subTotalElement = document.getElementById('sub_total');
                let subTotalAsliElement = document.getElementById('sub_totalasli');

                let dp_pemesananElement = document.getElementById('dp_pemesanan');
                let kekurangan_pemesananElement = document.getElementById('kekurangan_pemesanan');

                // Menghapus format Rupiah dari input sebelum submit
                subTotalElement.value = removeRupiahFormat(subTotalElement.value);
                subTotalAsliElement.value = removeRupiahFormat(subTotalAsliElement.value);

                dp_pemesananElement.value = removeRupiahFormat(dp_pemesananElement.value);
                kekurangan_pemesananElement.value = removeRupiahFormat(kekurangan_pemesananElement.value);

                // Formulir akan disubmit dengan nilai numerik
            });
    </script>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(event) {
        // Cek apakah elemen yang aktif adalah textarea
        const activeElement = document.activeElement;
        if (activeElement.tagName === 'TEXTAREA') {
            return; // Jika ya, biarkan Enter untuk pindah ke baris baru
        }

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
        var jumlah_ban = 0;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }

        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            var kategori = $('#kategori').val(); // Ambil nilai kategori (member/nonmember)

            if (!kategori) {
                // Jika kategori belum dipilih, tampilkan SweetAlert
                Swal.fire({
                    icon: 'warning',
                    title: 'Tipe Pelanggan Belum Dipilih!',
                    text: 'Silakan pilih tipe pelanggan terlebih dahulu sebelum memilih produk.',
                    confirmButtonText: 'OK'
                });
            } else {
                // Jika kategori sudah dipilih, tampilkan modal produk
                $('#tableProduk').modal('show');
                // Simpan urutan untuk menyimpan data ke baris yang sesuai
                $('#tableProduk').attr('data-urutan', urutan);
            }
        }

        // Event listener for pilih-btn
        $(document).on('click', '.pilih-btn', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var lama = $(this).data('lama');
            var nama = $(this).data('nama');
            var member = $(this).data('member');
            var diskonmember = $(this).data('diskonmember');
            var nonmember = $(this).data('nonmember');
            var diskonnonmember = $(this).data('diskonnonmember');
            
            getSelectedData(id, kode,lama, nama, member, diskonmember, nonmember, diskonnonmember);
        });

        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(id, kode_produk,kode_lama, nama_produk, member, diskonmember, nonmember, diskonnonmember) {
            var urutan = $('#tableProduk').attr('data-urutan');
            var kategori = $('#kategori').val();
            var harga = kategori === 'member' ? member : nonmember;
            var diskon = kategori === 'member' ? diskonmember : diskonnonmember;

            // Set nilai input pada baris yang sesuai
            $('#produk_id-' + urutan).val(id);
            $('#kode_produk-' + urutan).val(kode_produk);
            $('#kode_lama-' + urutan).val(kode_lama);
            $('#nama_produk-' + urutan).val(nama_produk);
            $('#harga-' + urutan).val(harga);
            $('#diskon-' + urutan).val(diskon);
            // Hitung total
            hitungTotal(urutan);
            // Tutup modal
            $('#tableProduk').modal('hide');

            // Setelah menambahkan data dari modal, fokuskan ke input jumlah
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

            // Format total ke dalam format rupiah dan set nilai input total
            $('#total-' + urutan).val(total);
            $('#totalasli-' + urutan).val(totalasli);
            // Hitung subtotal setiap kali total di baris berubah
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
            jumlah_ban = jumlah_ban + 1;
            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            }
            itemPembelian(jumlah_ban, jumlah_ban - 1);
        }

        function removeBan(params) {
            jumlah_ban = jumlah_ban - 1;
            var tabel_pesanan = document.getElementById('tabel-pembelian');
            var pembelian = document.getElementById('pembelian-' + params);
            tabel_pesanan.removeChild(pembelian);
            if (jumlah_ban === 0) {
                var item_pembelian = '<tr>';
                item_pembelian += '<td class="text-center" colspan="5">- Barang Jadi belum ditambahkan -</td>';
                item_pembelian += '</tr>';
                $('#tabel-pembelian').html(item_pembelian);
            } else {
                var urutan = document.querySelectorAll('#urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
            hitungSubTotal();
        }

        function itemPembelian(urutan, key, value = null) {
            var produk_id = '';
            var kode_produk = '';
            var kode_lama = '';
            var nama_produk = '';
            var jumlah = '';
            var diskon = '';
            var harga = '';
            var total = '';
            var totalasli = '';

            if (value !== null) {
                produk_id = value.produk_id;
                kode_produk = value.kode_produk;
                kode_lama = value.kode_lama;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                diskon = value.diskon;
                harga = value.harga;
                total = value.total;
                totalasli = value.totalasli;
            }

            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 5%; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>'; 
            item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
            item_pembelian += '<td style="width: 10%" hidden onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
            item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-' + urutan + '" name="kode_lama[]" value="' + kode_lama + '"></div></td>';
            item_pembelian += '<td style="width: 30%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
            item_pembelian += '<td style="width: 10%" style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
            item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')" style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-' + urutan + '" name="diskon[]" value="' + diskon + '" ></div></td>';
            item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + harga + '"></div></td>';
            item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + total + '"></div></td>';
            item_pembelian += '<td style="width: 10%" hidden onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" hidden id="totalasli-' + urutan + '" name="totalasli[]" value="' + totalasli + '"></div></td>';
            item_pembelian += '<td style="width: 10%"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);
            }
    </script>


@endsection
