@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
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
                {{-- <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Produk Banjaran</h1>
                </div> --}}
            </div>
        </div>
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
            <form id="penjualanForm" action="{{ url('toko_cilacap/penjualan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <!-- Bagian card form -->
                <div class="card" id="formContainer">
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 mt-2">
                                <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="member" {{ old('kategori') == 'member' ? 'selected' : null }}>Member</option>
                                    <option value="nonmember" {{ old('kategori') == 'nonmember' ? 'selected' : null }}>Non Member</option>
                                </select>
                            </div>
                
                            <div class="col-md-2 mt-2" hidden>
                                <label class="form-label" for="toko">Pilih Cabang</label>
                                <select class="form-control" id="toko" name="toko">
                                    <option value="">- Pilih -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ old('toko') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="col-md-3 mt-2" id="kodePelangganRow" hidden>
                                <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                            </div>
                
                            <div class="col-md-5 mt-4" id="namaPelangganRow" style="display: none;">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <div class="input-group mb-3">
                                    <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                    <input hidden type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama" value="{{ old('kode_pelangganlama') }}" onclick="showCategoryModalpemesanan()">
                                    <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}">
                                    <button class="btn btn-outline-primary" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                
                        <div class="row align-items-center" id="telpRow" hidden>
                            <div class="col-md-6 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <label for="catatan">Alamat</label>
                                <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                
                    </div>
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
                                            <tr onclick="getSelectedDataPemesanan('{{ $item->nama_pelanggan }}', '{{ $item->telp }}', '{{ $item->alamat }}', '{{ $item->kode_pelanggan }}', '{{ $item->kode_pelangganlama }}')">
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
            
                <div class="card">
                    <div class="modal-body">
                        <!-- Form Pencarian -->
                        <div class="form-group">
                            <label for="searchInput">Cari Produk:</label>
                            <div class="input-group">
                                <input type="text" id="searchInput" placeholder="Cari produk..." class="form-control" onclick="checkCustomerType()">
                            </div>
                        </div>
                
                        <!-- Tabel Produk -->
                        <table id="datatables5" class="table table-bordered table-striped" style="font-size: 12px;">
                            <thead id="table-head" style="display: none;">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th hidden>Kode Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th hidden>QR Code Produk</th>
                                    <th>Harga Member</th>
                                    <th>Diskon Member</th>
                                    <th>Harga Non Member</th>
                                    <th>Diskon Non Member</th>
                                    <th>Stok</th>
                                    <th hidden>ID Produk</th> <!-- Kolom tersembunyi untuk ID produk -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    @php
                                        $tokocilacap = $item->tokocilacap->first();
                                        $stok_tokocilacap = $item->stok_tokocilacap ? $item->stok_tokocilacap->jumlah : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td hidden>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td hidden>{{ $item->qrcode_produk }}</td>
                                        <td>
                                            <span class="member_harga_clc">{{ $tokocilacap ? $tokocilacap->member_harga_clc : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_clc">{{ $tokocilacap ? $tokocilacap->member_diskon_clc : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_clc">{{ $tokocilacap ? $tokocilacap->non_harga_clc : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_clc">{{ $tokocilacap ? $tokocilacap->non_diskon_clc : 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">{{ $stok_tokocilacap }}</td>
                                        <td hidden>{{ $item->id }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                
                        <!-- Tabel Pembelian -->
                        <table id="tabel-pembelian" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="font-size:12px; width: 5%;" class="text-center">No</th>
                                    <th hidden style="font-size:12px">Kode Produk</th>
                                    <th style="font-size:12px; width: 15%;">Kode Produk</th>
                                    <th style="font-size:12px; width: 40%;">Nama Produk</th>
                                    <th style="font-size:12px; width: 10%;">Jumlah</th>
                                    <th style="font-size:12px; width: 10%;">Diskon</th>
                                    <th hidden style="font-size:12px">Nominal Diskon</th>
                                    <th style="font-size:12px; width: 10%;">Harga</th>
                                    <th style="font-size:12px; width: 10%;">Total</th>
                                    <th style="font-size:12px;; width: 10%; text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex justify-content-between align-items-center">
                                        <label for="sub_total" class="mr-2">Sub Total :</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" readonly style="width: 70%;">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex justify-content-between align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli </label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" readonly style="width: 70%;">
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex justify-content-between align-items-center">
                                        <label for="bayar" class="mr-2">Uang Bayar </label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()" style="width: 70%;" >
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex justify-content-between align-items-center">
                                        <label for="kembali" class="mr-2">Kembali </label>
                                        <input type="text" class="form-control large-font" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly style="width: 70%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="form-group" style="flex: 8;">
                            <label for="metode_id">Jenis Pembayaran</label>
                            <select class="select2bs4 select2-hidden-accessible" name="metode_id" style="width: 100%;" id="nama_metode" onchange="getData1()">
                                <option value="tunai" data-fee="0">Tunai</option> <!-- Ubah opsi di sini -->
                                @foreach ($metodes as $metode)
                                    <option value="{{ $metode->id }}" data-fee="{{ $metode->fee }}" {{ old('metode_id') == $metode->id ? 'selected' : '' }}>
                                        {{ $metode->nama_metode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="payment-fields" class="form-group" style="display: none; margin-top: 20px;">
                              
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="fee" readonly name="fee" placeholder="" value="{{ old('fee') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <label for="fee">Fee (%)</label>

                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="total_fee" name="total_fee" placeholder="" value="{{ old('total_fee') }}" readonly>
                                    <label for="total_fee">Total Fee</label>

                                </div>
                               
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="" value="{{ old('keterangan') }}">
                                    <label for="keterangan">Keterangan</label>

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
                        <button type="button" class="btn btn-primary" id="simpanButton" onclick="handleSave()">Simpan</button>
                        <div id="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                        </div>  
                    </div>
            </form>
        </div>
    </section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function checkCustomerType() {
        var kategori = document.getElementById("kategori").value;
        var namaPelanggan = document.getElementById("nama_pelanggan").value;

        if (kategori === "") {
            Swal.fire({
                title: 'Pilih Tipe Pelanggan',
                text: 'Silakan pilih tipe pelanggan terlebih dahulu!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        } else if (kategori === "member" && namaPelanggan === "") {
            Swal.fire({
                title: 'Masukan Nama Pelanggan',
                text: 'Silakan masukan nama pelanggan untuk tipe member!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        } else {
            console.log("Searching for products...");
        }
    }
</script>


<script>
    $(document).ready(function() {
        // Jalankan handleSave saat tombol "Simpan" diklik
        $('#simpanButton').on('click', function(event) {
            event.preventDefault(); // Mencegah aksi default tombol
            handleSave(); // Panggil fungsi handleSave saat tombol simpan diklik
        });

        // Jalankan handleSave saat tombol Enter ditekan di input "bayar"
        $('#bayar').on('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Mencegah aksi default tombol Enter
                handleSave(); // Panggil fungsi handleSave
            }
        });

        function handleSave() {
            var bayar = parseInt($('#bayar').val().replace(/[^\d]/g, '')) || 0; // Ambil nilai bayar tanpa format dan ubah menjadi integer
            var subTotal = parseInt($('#sub_total').val().replace(/[^\d]/g, '')) || 0; // Ambil nilai sub total tanpa format dan ubah menjadi integer

            if (!bayar) {
                // Tampilkan SweetAlert jika input bayar kosong
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan masukkan jumlah uang bayar terlebih dahulu.',
                    confirmButtonText: 'OK'
                });
            } else if (bayar < subTotal) {
                // Tampilkan SweetAlert jika uang bayar kurang dari sub total
                Swal.fire({
                    icon: 'warning',
                    title: 'Uang Bayar Kurang',
                    text: 'Jumlah uang bayar kurang dari total yang harus dibayar.',
                    confirmButtonText: 'OK'
                });
            } else {
                // Sembunyikan tombol simpan dan tampilkan loading
                $('#simpanButton').hide();
                $('#loading').show();

                // Lanjutkan ke proses simpan dengan submit form secara manual
                $('#penjualanForm').submit(); 
            }
        }

        // Proses submit form menggunakan AJAX
        $('#penjualanForm').on('submit', function(event) {
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
                },
                complete: function() {
                    // Tampilkan kembali tombol simpan dan sembunyikan loading jika terjadi error atau selesai
                    $('#simpanButton').show();
                    $('#loading').hide();
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#kategori').change(function() {
            var tipePelanggan = $(this).val(); 
            updatePrices(tipePelanggan); 
        });

        function updatePrices(tipePelanggan) {
            $('#datatables5 tbody tr').each(function() {
                var hargaMember = parseFloat($(this).find('.member_harga_clc').text()) || 0;
                var diskonMember = parseFloat($(this).find('.member_diskon_clc').text()) || 0;
                var hargaNonMember = parseFloat($(this).find('.non_harga_clc').text()) || 0;
                var diskonNonMember = parseFloat($(this).find('.non_diskon_clc').text()) || 0;

                if (tipePelanggan === 'member') {
                    // Update harga dan diskon member
                    $(this).find('.member_harga_clc').text(hargaMember); 
                    $(this).find('.member_diskon_clc').text(diskonMember); 
                } else if (tipePelanggan === 'nonmember') {
                    // Update harga dan diskon non-member
                    $(this).find('.non_harga_clc').text(hargaNonMember); // Harga non-member
                    $(this).find('.non_diskon_clc').text(diskonNonMember); // Diskon non-member
                }
            });
        }

        // Inisialisasi DataTable tanpa pagination, pencarian, dan info entri
        $('#datatables5').DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false
        });

        // Sembunyikan semua baris di tabel produk di awal
        $('#datatables5 tbody tr').hide();

        // Menampilkan baris yang sesuai pencarian, menyembunyikan yang tidak sesuai
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            if (value) {
                $('#table-head').show(); // Tampilkan thead
                $('#datatables5 tbody tr').filter(function() {
                    var kodeProduk = $(this).find('td').eq(1).text().toLowerCase();
                    var kodeLama = $(this).find('td').eq(2).text().toLowerCase();
                    var namaProduk = $(this).find('td').eq(3).text().toLowerCase();
                    var qrcodeProduk = $(this).find('td').eq(4).text().toLowerCase();

                    $(this).toggle(kodeProduk.indexOf(value) > -1 || 
                                   kodeLama.indexOf(value) > -1 || 
                                   namaProduk.indexOf(value) > -1 || 
                                   qrcodeProduk.indexOf(value) > -1);
                });
            } else {
                $('#table-head').hide(); // Sembunyikan thead jika input kosong
                $('#datatables5 tbody tr').hide();
            }
        });

        // Tangkap event klik pada baris produk untuk menambahkan ke tabel pembelian
        $('#datatables5 tbody').on('click', 'tr', function() {
            addRowToPurchaseTable($(this)); // Tambahkan produk ke tabel pembelian
            $('#searchInput').val(''); // Kosongkan pencarian
            $('#datatables5 tbody tr').hide(); // Sembunyikan semua baris
            $('#table-head').hide(); // Sembunyikan header tabel
        });

        // Tangkap event tekan Enter pada input pencarian
        $('#searchInput').on('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                var selectedRow = $('#datatables5 tbody tr:visible').first(); // Ambil baris pertama yang terlihat
                if (selectedRow.length) {
                    addRowToPurchaseTable(selectedRow); // Tambahkan produk ke tabel pembelian
                    $(this).val(''); // Kosongkan pencarian
                    $('#datatables5 tbody tr').hide(); // Sembunyikan semua baris
                    $('#table-head').hide(); // Sembunyikan header tabel
                }
            }
        });

        $('#searchInput').on('keydown', function(event) {
        if (event.key === 'Tab') {
            event.preventDefault(); // Mencegah perilaku default
            $('#tabel-pembelian-body .jumlah').last().focus(); // Pindahkan fokus ke input jumlah di baris terakhir tabel pembelian
        }
    });

    // Event agar fokus kembali ke kolom pencarian setelah menekan Tab di kolom jumlah
    $('#tabel-pembelian-body').on('keydown', '.jumlah', function(event) {
        if (event.key === 'Tab') {
            event.preventDefault(); // Mencegah perilaku default
            $('#searchInput').focus(); // Pindahkan fokus kembali ke kolom pencarian produk
        }
    });



        function addRowToPurchaseTable(row) {
        var tipePelanggan = $('#kategori').val();
        var kodeProduk = row.find('td').eq(1).text();
        var kodeLama = row.find('td').eq(2).text();
        var namaProduk = row.find('td').eq(3).text();
        var idProduk = row.find('td').eq(10).text();
        
        var harga, diskon;
        if (tipePelanggan === 'member') {
            harga = parseFloat(row.find('.member_harga_clc').text()) || 0;
            diskon = parseFloat(row.find('.member_diskon_clc').text()) || 0;
        } else if (tipePelanggan === 'nonmember') {
            harga = parseFloat(row.find('.non_harga_clc').text()) || 0;
            diskon = parseFloat(row.find('.non_diskon_clc').text()) || 0;
        }
        
        var jumlah = 1; 

        var existingRow = $('#tabel-pembelian-body tr').filter(function() {
            return $(this).find('input[name="kode_produk[]"]').val() === kodeProduk;
        });

        if (existingRow.length > 0) {
            var jumlahInput = existingRow.find('.jumlah');
            var jumlahSekarang = parseInt(jumlahInput.val()) || 1;
            var jumlahBaru = jumlahSekarang + 1;
            jumlahInput.val(jumlahBaru);

            updateTotalAndDiscount(existingRow, harga, diskon, jumlahBaru);
        } else {
            var nominal_diskon = (harga * (diskon / 100)) * jumlah;
            var totalPerItem = (harga - (harga * (diskon / 100))) * jumlah;
            var totalAsli = harga * jumlah;

            var newRow = `
                <tr style="font-size: 13px;">
                    <td class="text-center">${$('#tabel-pembelian-body tr').length + 1}</td>
                    <td hidden><input type="text" name="kode_produk[]" value="${kodeProduk}" class="form-control" readonly hidden></td>
                    <td><input type="text" name="kode_lama[]" value="${kodeLama}" class="form-control" readonly></td>
                    <td><input type="text" name="nama_produk[]" value="${namaProduk}" class="form-control" readonly></td>
                    <td><input type="number" class="form-control form-control-sm jumlah" name="jumlah[]" value="1" min="1"></td>
                    <td><input type="text" name="diskon[]" value="${diskon}" class="form-control" readonly></td>
                    <td hidden class="nominal_diskon"><input type="text" name="nominal_diskon[]" value="${nominal_diskon}" class="form-control" readonly hidden></td> 
                    <td><input type="text" name="harga[]" value="${harga}" class="form-control" readonly></td>
                    <td class="total"><input type="text" name="total[]" value="${totalPerItem}" class="form-control" readonly></td> 
                    <td hidden class="totalasli"><input type="text" name="totalasli[]" value="${totalAsli}" class="form-control" readonly hidden></td> 
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm delete-row"><i class="fas fa-trash"></i></button>
                    </td>
                    <td hidden><input type="text" name="produk_id[]" value="${idProduk}" class="form-control" readonly hidden></td>
                </tr>
            `;
            $('#tabel-pembelian-body').append(newRow);
        }

        calculateTotal();

        $('#tabel-pembelian-body').on('input', '.jumlah', function() {
            var row = $(this).closest('tr');
            var jumlahBaru = parseInt($(this).val()) || 1;
            var hargaSatuan = parseFloat(row.find('input[name="harga[]"]').val()) || 0;
            var diskon = parseFloat(row.find('input[name="diskon[]"]').val()) || 0;

            updateTotalAndDiscount(row, hargaSatuan, diskon, jumlahBaru);
        });

        $('#tabel-pembelian-body').on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });
    }

    function updateTotalAndDiscount(row, harga, diskon, jumlah) {
        var nominal_diskon = (harga * (diskon / 100)) * jumlah;
        var totalPerItem = (harga - (harga * (diskon / 100))) * jumlah;
        var totalAsli = harga * jumlah;

        row.find('.total').find('input').val(totalPerItem);
        row.find('.nominal_diskon').find('input').val(nominal_diskon);
        row.find('.totalasli').find('input').val(totalAsli);

        calculateTotal();
    }

        function calculateTotal() {
            var subtotal = 0;
            var subtotalAsli = 0; // Inisialisasi subtotal asli
            $('#tabel-pembelian-body tr').each(function() {
                var jumlah = parseInt($(this).find('.jumlah').val()) || 0; // Ambil jumlah dari input
                var hargaSatuan = parseFloat($(this).find('input[name="harga[]"]').val()) || 0; // Ambil harga dari input harga[]
                var diskon = parseFloat($(this).find('input[name="diskon[]"]').val()) || 0; // Ambil diskon dari kolom yang tepat

                // Hitung subtotal berdasarkan jumlah dan diskon
                var totalPerItem = (hargaSatuan - (hargaSatuan * (diskon / 100))) * jumlah; 
                subtotal += totalPerItem; // Tambahkan ke subtotal yang mempertimbangkan diskon

                // Hitung subtotal asli
                subtotalAsli += hargaSatuan * jumlah; // Tambahkan ke subtotal asli
            });

            var formattedSubtotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(subtotal);
            var formattedSubtotalAsli = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(subtotalAsli);

            $('#sub_total').val(formattedSubtotal);
            $('#sub_totalasli').val(formattedSubtotalAsli); // Simpan subtotal asli dengan format

            calculateKembali(); // Panggil fungsi untuk menghitung kembali
        }


        function calculateKembali() {
            // Ambil nilai bayar dari input dan hilangkan format
            var bayar = parseFloat($('#bayar').val().replace(/[^\d]/g, '')) || 0; 

            // Ambil subtotal asli yang sudah dihilangkan format
            var subtotal = parseFloat($('#sub_total').val().replace(/[^\d]/g, '')) || 0; 

            // Hitung kembalian
            var kembali = bayar - subtotal; 

            // Format kembalian untuk ditampilkan
            var formattedKembali = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(kembali);

            // Update input kembali
            $('#kembali').val(formattedKembali);
        }


        function formatAndUpdateKembali() {
            var bayar = $('#bayar').val().replace(/[^\d]/g, ''); // Hilangkan semua karakter kecuali angka
            if (bayar !== "") {
                // Tambahkan "Rp" dan format ke rupiah tanpa desimal
                var formattedBayar = "Rp " + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(bayar);
                $('#bayar').val(formattedBayar); // Set kembali ke input setelah selesai mengetik
            }
            calculateKembali(); // Hitung kembalian setelah pemformatan
        }

        $('#bayar').on('input', function() {
            var bayar = $(this).val().replace(/[^\d]/g, ''); // Hilangkan format saat mengetik
            if (bayar !== "") {
                // Format inputan ke rupiah saat pengguna mengetik
                var formattedBayar = "Rp " + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(bayar);
                $(this).val(formattedBayar); // Update input dengan format
            }
            calculateKembali(); // Hitung kembalian
        });

        $('#bayar').on('blur', function() {
            formatAndUpdateKembali(); // Format hanya setelah input selesai
        });

        $('#bayar').on('focus', function() {
            var bayar = $(this).val().replace(/[^\d]/g, ''); // Hilangkan format saat fokus
            $(this).val(bayar); // Tampilkan angka tanpa format ketika input difokuskan
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var kategoriSelect = document.getElementById('kategori');
    var namaPelangganRow = document.getElementById('namaPelangganRow');
    var telpRow = document.getElementById('telpRow');
    var alamatRow = document.getElementById('alamatRow');
    var kodePelangganRow = document.getElementById('kodePelangganRow'); 
    var namaPelangganInput = document.getElementById('nama_pelanggan');
    
    kategoriSelect.addEventListener('change', function() {
        if (kategoriSelect.value === 'member') {
            kodePelangganRow.hidden = false; 
            namaPelangganInput.readOnly = true; 
            namaPelangganRow.style.display = 'block'; 
            telpRow.hidden = true; 
            alamatRow.hidden = true; 
        } else if (kategoriSelect.value === 'nonmember') {
            kodePelangganRow.hidden = true; 
            namaPelangganInput.readOnly = false; 
            namaPelangganRow.style.display = 'none'; 
            telpRow.hidden = true; 
            alamatRow.hidden = true; 
            namaPelangganInput.value = ''; // Mengosongkan nama pelanggan saat diubah ke nonmember
        } else {
            namaPelangganRow.style.display = 'none'; 
            telpRow.hidden = true; 
            alamatRow.hidden = true; 
            kodePelangganRow.hidden = true; 
        }
    });

    // Set kondisi awal sesuai tipe pelanggan
    if (kategoriSelect.value === 'nonmember') {
        namaPelangganRow.style.display = 'none'; 
        telpRow.hidden = true; 
        alamatRow.hidden = true; 
        namaPelangganInput.value = ''; // Mengosongkan nama pelanggan saat diubah ke nonmember
    }
});

</script>

<script>
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
    // Inisialisasi DataTable dan atur modal
    $(document).ready(function() {
        var pelangganTable = $('#datatables4').DataTable();

        $('#tableMarketing').on('shown.bs.modal', function () {
            pelangganTable.columns.adjust().draw();
        });
    });

    // Fungsi untuk menampilkan modal
    function showCategoryModalpemesanan() {
        $('#tableMarketing').modal('show');
    }

    // Fungsi untuk mendapatkan data pelanggan dari modal dan menyembunyikan modal
    function getSelectedDataPemesanan(nama_pelanggan, telp, alamat, kode_pelanggan, kode_pelangganlama) {
        // Masukkan data yang dipilih ke dalam input form
        document.getElementById('nama_pelanggan').value = nama_pelanggan;
        document.getElementById('kode_pelanggan').value = kode_pelanggan;
        document.getElementById('kode_pelangganlama').value = kode_pelangganlama; // Perbaikan: gunakan ID yang benar
        document.getElementById('telp').value = telp;
        document.getElementById('alamat').value = alamat;

        // Sembunyikan modal setelah data dipilih
        $('#tableMarketing').modal('hide');
    }
</script>

<script>
    $(document).ready(function() {
    var subtotalWithDiscount; // Variabel untuk menyimpan subtotal setelah diskon

    function calculateFee() {
        var metodeId = $('#nama_metode').val(); 
        var subTotal = parseFloat($('#sub_total').val().replace(/[^\d]/g, '')) || 0; 

        var feePercentage = 0; 
        if (metodeId && metodeId !== "tunai") { 
            feePercentage = parseFloat($('#nama_metode option:selected').data('fee')) || 0; 

            var totalFee = (subTotal * feePercentage) / 100;
            var totalWithFee = subTotal + totalFee;

            var formattedTotalFee = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalFee);
            var formattedTotalWithFee = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalWithFee);

            $('#total_fee').val(formattedTotalFee); 
            $('#sub_total').val(formattedTotalWithFee); 
            $('#bayar').val(formattedTotalWithFee); 
        } else {
            $('#total_fee').val('Rp0');
            $('#sub_total').val(subtotalWithDiscount); // Kembalikan ke subtotal setelah diskon
            $('#bayar').val(''); 
        }
    }

    $('#nama_metode').change(function() {
        var metodeId = $(this).val(); 

        if (metodeId) {
            $('#payment-fields').show();

            if (metodeId === "tunai") {
                $('#fee').closest('.input-group').hide();
                $('#total_fee').closest('.col-md-4').hide();
                $('#keterangan').closest('.col-md-6').hide();

                $('#sub_total').val(subtotalWithDiscount); // Gunakan subtotal setelah diskon
            } else {
                $('#fee').closest('.input-group').show(); 
                $('#total_fee').closest('.col-md-4').show(); 
                $('#keterangan').closest('.col-md-6').show(); 
                
                calculateTotal(); 
                $('#total_fee').val('Rp0'); 
                $('#keterangan').val(''); 
            }

            calculateFee();
            calculateKembali();
        } else {
            $('#payment-fields').hide();
            $('#total_fee').val('Rp0');
            $('#sub_total').val('Rp0');
            $('#keterangan').val('');
        }
    });

    function calculateTotal() {
        var subtotal = 0;
        $('#tabel-pembelian-body tr').each(function() {
            var jumlah = parseInt($(this).find('.jumlah').val()) || 0; 
            var hargaSatuan = parseFloat($(this).find('input[name="harga[]"]').val()) || 0; 
            var diskon = parseFloat($(this).find('input[name="diskon[]"]').val()) || 0; 

            var totalPerItem = (hargaSatuan - (hargaSatuan * (diskon / 100))) * jumlah; 
            subtotal += totalPerItem; 
        });

        var formattedSubtotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(subtotal);

        $('#sub_total').val(formattedSubtotal); 
        subtotalWithDiscount = formattedSubtotal; // Simpan subtotal setelah diskon

        calculateKembali();
    }

    function calculateKembali() {
        var bayar = parseFloat($('#bayar').val().replace(/[^\d]/g, '')) || 0; 
        var subtotal = parseFloat($('#sub_total').val().replace(/[^\d]/g, '')) || 0; 

        var kembali = bayar - subtotal; 

        var formattedKembali = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(kembali);

        $('#kembali').val(formattedKembali);
    }

    $('#bayar').on('input', function() {
        formatAndUpdateKembali(); 
    });

    $('#bayar').on('blur', function() {
        formatAndUpdateKembali(); 
    });

    $('#bayar').on('focus', function() {
        var bayar = $(this).val().replace(/[^\d]/g, ''); 
        $(this).val(bayar); 
    });

    function formatAndUpdateKembali() {
        var bayar = $('#bayar').val().replace(/[^\d]/g, ''); 
        if (bayar !== "") {
            var formattedBayar = "Rp " + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(bayar);
            $('#bayar').val(formattedBayar); 
        }
        calculateKembali();
    }
});

</script>

@endsection
