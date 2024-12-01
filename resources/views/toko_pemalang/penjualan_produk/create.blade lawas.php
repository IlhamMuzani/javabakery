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
                <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Produk Pemalang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                 
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
            <form id="penjualanForm" action="{{ url('toko_pemalang/penjualan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                {{-- detail pelanggan --}}
                <div class="card">
                    <div class="card-header">
                        {{-- <div class="float-right">
                            <select class="form-control" id="kategori1" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="penjualan" {{ old('kategori1') == 'penjualan' ? 'selected' : '' }}>Penjualan Produk</option>
                                <option value="pelunasan" {{ old('kategori1') == 'pelunasan' ? 'selected' : '' }}>Pelunasan Pemesanan Produk</option>
                            </select>
                        </div> --}}
                        {{-- <div class="float-right">
                            <a href="{{ route('toko_slawi.penjualan_produk.pelunasan') }}"  class="btn btn-primary btn-sm">Pelunasan Pemesanan
                            </a>
                        </div> --}}
                    </div>
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

                        </div>
                    
                        <div class="row mb-3 align-items-center" id="namaPelangganRow" style="display: none;">
                            <div class="col-md">
                                <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search" style=""></i>Cari pelanggan
                                </button> 
                            </div>      
                            <div class="col-md-6 mb-3 "> 
                                <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                <input hidden type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama" value="{{ old('kode_pelangganlama') }}" onclick="showCategoryModalpemesanan()">
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                            </div>     
                        </div>

                        <div class="row  align-items-center" id="telpRow" hidden>
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
                                            <tr onclick="checkExpired('{{ $item->tanggal_akhir }}', '{{ $item->nama_pelanggan }}', '{{ $item->telp }}', '{{ $item->alamat }}', '{{ $item->kode_pelanggan }}', '{{ $item->kode_pelangganlama }}')">
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
                                            <th hidden style="font-size:14px">Kode Produk</th>
                                            <th style="font-size:14px">Kode Lama</th>
                                            <th style="font-size:14px">Nama Produk</th>
                                            <th style="font-size:14px">Jumlah</th>
                                            <th style="font-size:14px">Diskon</th>
                                            <th hidden style="font-size:14px">Nomminal Diskon</th>
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
                                            <th>Stok</th> <!-- Tambahkan kolom stok -->
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $item)
                                            @php
                                                $tokopemalang = $item->tokopemalang->first();
                                                $stok_tokopemalang = $item->stok_tokopemalang ? $item->stok_tokopemalang->jumlah : 0; // Jika stok ada, tampilkan, jika tidak tampilkan 0
                                            @endphp
                                            <tr class="pilih-btn"
                                                data-id="{{ $item->id }}"
                                                data-kode="{{ $item->kode_produk }}"
                                                data-kodel="{{ $item->kode_lama }}"
                                                data-catatan="{{ $item->catatanproduk }}"
                                                data-nama="{{ $item->nama_produk }}"
                                                data-member="{{ $tokopemalang ? $tokopemalang->member_harga_pml : '' }}"
                                                data-diskonmember="{{ $tokopemalang ? $tokopemalang->member_diskon_pml : '' }}"
                                                data-nonmember="{{ $tokopemalang ? $tokopemalang->non_harga_pml : '' }}"
                                                data-diskonnonmember="{{ $tokopemalang ? $tokopemalang->non_diskon_pml : '' }}">

                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td >{{ $item->kode_produk }}</td>
                                                <td>{{ $item->kode_lama }}</td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>
                                                    <span class="member_harga_pml">{{ $tokopemalang ? $tokopemalang->member_harga_pml : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="member_diskon_pml">{{ $tokopemalang ? $tokopemalang->member_diskon_pml : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="non_harga_pml">{{ $tokopemalang ? $tokopemalang->non_harga_pml : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="non_diskon_pml">{{ $tokopemalang ? $tokopemalang->non_diskon_pml : '' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    {{ $stok_tokopemalang }} <!-- Tampilkan stok produk -->
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-kode="{{ $item->kode_produk }}"
                                                        data-kodel="{{ $item->kode_lama }}"
                                                        data-catatan="{{ $item->catatanproduk }}"
                                                        data-nama="{{ $item->nama_produk }}"
                                                        data-member="{{ $tokopemalang ? $tokopemalang->member_harga_pml : '' }}"
                                                        data-diskonmember="{{ $tokopemalang ? $tokopemalang->member_diskon_pml : '' }}"
                                                        data-nonmember="{{ $tokopemalang ? $tokopemalang->non_harga_pml : '' }}"
                                                        data-diskonnonmember="{{ $tokopemalang ? $tokopemalang->non_diskon_pml : '' }}">
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
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" >
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="bayar" class="mr-2">Uang Bayar</label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kembali" class="mr-2">Kembali</label>
                                        <input type="text" class="form-control large-font" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
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
    </section>
    <script>
        function checkExpired(tanggal_akhir, nama, telp, alamat, kode, kode_lama) {
            var today = new Date();  // Tanggal hari ini
            var tanggalAkhir = new Date(tanggal_akhir);  // Mengubah tanggal_akhir ke objek Date
            
            // Periksa apakah tanggal akhir lebih kecil dari hari ini
            if (tanggalAkhir < today) {
                // Menampilkan SweetAlert jika member sudah expired
                Swal.fire({
                    icon: 'error',
                    title: 'Member Expired',
                    text: 'Pelanggan dengan tanggal akhir ' + tanggal_akhir + ' tidak dapat dipilih.',
                    confirmButtonText: 'OK'
                });
            } else {
                // Jika tidak expired, jalankan fungsi ini
                getSelectedDataPemesanan(nama, telp, alamat, kode, kode_lama);
            }
        }
        </script>
  {{-- //pdf tab baru   --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#penjualanForm').submit(function(event) {
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
            $('#penjualanForm').find('input[type="text"], input[type="number"], textarea, select').each(function() {
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
            
            // Check if the selected payment method is "Tunai"
            if (metodeId && document.querySelector('#nama_metode option:checked').text === 'Tunai') {
                paymentFields.style.display = 'none';
                paymentRow.style.display = 'block';
                changeRow.style.display = 'block';
            } else if (metodeId) {
                $.ajax({
                    url: "{{ url('toko_pemalang/metodebayar/metode') }}" + "/" + metodeId,
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
    
                        // Hide payment and change fields for all payment methods
                        paymentRow.style.display = 'none';
                        changeRow.style.display = 'none';
                        
                        // Update calculations whenever data is fetched
                        updateCalculations();
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan dalam permintaan AJAX:', error);
                    }
                });
            } else {
                paymentFields.style.display = 'none';
                paymentRow.style.display = 'block';
                changeRow.style.display = 'block';
                // Reset calculations if no method is selected
                updateCalculations();
            }
        }
    
        function updateCalculations() {
        var subTotal = parseFloat(document.getElementById('sub_total').value.replace('Rp', '').replace(/\./g, '').trim()) || 0;
        var fee = parseFloat(document.getElementById('fee').value.replace('%', '').trim()) || 0;
        
        // Hitung total fee dan bulatkan hasilnya
        var totalFee = Math.round((subTotal * fee / 100)) || 0; 
        var finalTotal = subTotal + totalFee;

        // Format nilai tanpa .00
        function formatCurrency(value) {
            var formattedValue = value.toFixed(2).replace(/\.00$/, '');
            return 'Rp' + formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Update total fee dan sub total akhir
        document.getElementById('total_fee').value = formatCurrency(totalFee);
        document.getElementById('sub_total').value = formatCurrency(finalTotal);
        }

    
        // Add event listeners for initialization
        document.getElementById('nama_metode').addEventListener('change', getData1);
        document.getElementById('sub_total').addEventListener('input', updateCalculations);
    
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
                    
                        kodePelangganRow.hidden = false;
                        namaPelangganInput.readOnly = true;
                        namaPelangganRow.style.display = 'block';
                        telpRow.hidden = false;
                        alamatRow.hidden = false;
                    } else if (kategoriSelect.value === 'nonmember') {
                        
                        kodePelangganRow.hidden = true;
                        namaPelangganInput.readOnly = false;
                        namaPelangganRow.style.display = 'none';
                        telpRow.hidden = true;
                        alamatRow.hidden = true;
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
        
            function getSelectedDataPemesanan(nama_pelanggan,  telp, alamat, kode_pelanggan, kode_pelangganlama) {
                document.getElementById('nama_pelanggan').value = nama_pelanggan;
                document.getElementById('kode_pelanggan').value = kode_pelanggan;
                document.getElementById('kode_pelangganlama').value = kode_pelangganlama;
                document.getElementById('telp').value = telp;
                document.getElementById('alamat').value = alamat;
                $('#tableMarketing').modal('hide');
            }

            function showCategoryModaldeposit() {
                $('#tableDeposit').modal('show');
            }
        
            function getSelectedDataDeposit(kode_dppemesanan, dp_pemesanan, kekurangan_pemesanan) {
                document.getElementById('kode_dppemesanan').value = kode_dppemesanan;
                document.getElementById('dp_pemesanan').value = dp_pemesanan;
                document.getElementById('kekurangan_pemesanan').value = kekurangan_pemesanan;
                $('#tableDeposit').modal('hide');
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

            // Format input dan update kembalian
            function formatAndUpdateKembali() {
                let subTotalElement = document.getElementById('sub_total');
                let bayarElement = document.getElementById('bayar');
                let kembaliElement = document.getElementById('kembali');

                // Mengambil nilai sub_total
                let subTotal = removeRupiahFormat(subTotalElement.value);

                // Format dan ambil nilai bayar
                let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
                let bayar = parseFloat(bayarValue) || 0; // Jika tidak valid, set 0

                // Format input 'bayar'
                bayarElement.value = formatRupiah(bayarValue);

                // Hitung kembalian
                let kembali = bayar - subTotal;
                
                 // Validasi pelunasan
                let bayarElementRaw = removeRupiahFormat(bayarElement.value);
                if (bayar < subTotal) {
                    bayarElement.setCustomValidity('Nominal bayar tidak cukup.');
                } else {
                    bayarElement.setCustomValidity('');
                }
                // Format hasil kembalian sebagai Rupiah
                kembaliElement.value = kembali >= 0 ? formatRupiah(kembali) : 'Rp. 0';
            }

            // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
            document.addEventListener('DOMContentLoaded', function() {
                let subTotalElement = document.getElementById('sub_total');
                let subTotal = removeRupiahFormat(subTotalElement.value);
                subTotalElement.value = formatRupiah(subTotal);
            });

            document.querySelector('form').addEventListener('submit', function(event) {
    let subTotalElement = document.getElementById('sub_total');
    let bayarElement = document.getElementById('bayar');
    let kembaliElement = document.getElementById('kembali');

    // Menghapus format Rupiah dari input sebelum submit
    subTotalElement.value = removeRupiahFormat(subTotalElement.value);
    bayarElement.value = removeRupiahFormat(bayarElement.value);
    kembaliElement.value = removeRupiahFormat(kembaliElement.value);

    // Cek jika Uang Bayar = 0
    let bayarValue = parseFloat(bayarElement.value);
    if (bayarValue === 0 || isNaN(bayarValue)) {
        alert('Uang Bayar tidak boleh 0!');
        event.preventDefault(); // Blokir submit form
        return; // Keluar dari fungsi jika nilai bayar tidak valid
    }

    // Formulir akan disubmit dengan nilai numerik
});



    </script>


    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); 
                addPesanan(); 
            }
            if (event.key === 'F1') { 
                event.preventDefault(); 
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
        var kodel = $(this).data('kodel');
        var nama = $(this).data('nama');
        var member = $(this).data('member');
        var diskonmember = $(this).data('diskonmember');
        var nonmember = $(this).data('nonmember');
        var diskonnonmember = $(this).data('diskonnonmember');
        
        getSelectedData(id, kode,kodel, nama, member, diskonmember, nonmember, diskonnonmember);
    });

    // Fungsi untuk memilih data barang dari modal
    function getSelectedData(id, kode_produk,kode_lama, nama_produk, member, diskonmember, nonmember, diskonnonmember) {
        var urutan = $('#tableProduk').attr('data-urutan');
        var kategori = $('#kategori').val();
        var harga = kategori === 'member' ? member : nonmember;
        var diskon = kategori === 'member' ? diskonmember : diskonnonmember;

        // Set nilai input pada baris yang sesuai
        $('#produk_id-' + urutan).val(id);
        $('#kode_lama-' + urutan).val(kode_lama);
        $('#kode_produk-' + urutan).val(kode_produk);
        $('#nama_produk-' + urutan).val(nama_produk);
        $('#harga-' + urutan).val(harga);
        $('#diskon-' + urutan).val(diskon);

        // Set nilai default untuk input jumlah dan fokuskan ke input jumlah
        $('#jumlah-' + urutan).val(1).focus(); // Set nilai default menjadi 1 dan fokuskan ke input jumlah
        
        // Hitung total
        hitungTotal(urutan);

        // Tutup modal
        $('#tableProduk').modal('hide');
    }


    // Fungsi untuk menghitung total berdasarkan harga dan jumlah
    function hitungTotal(urutan) {
        var harga = parseFloat($('#harga-' + urutan).val().replace(/[^0-9]/g, '')) || 0;
        var diskon = parseFloat($('#diskon-' + urutan).val()) || 0;
        var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;

        var nominalDiskon = (harga * (diskon / 100)) * jumlah; // Hitung nominal diskon
        var hargaSetelahDiskon = harga - (harga * (diskon / 100));
        var total = hargaSetelahDiskon * jumlah;
        var totalasli = harga * jumlah;

        $('#nominal_diskon-' + urutan).val(nominalDiskon); // Format dua desimal

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
    var kode_lama = '';
    var kode_produk = '';
    var nama_produk = '';
    var jumlah = '';
    var diskon = '';
    var nominal_diskon = '';
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
        nominal_diskon = value.nominal_diskon;
        harga = value.harga;
        total = value.total;
        totalasli = value.totalasli;
    }

    var item_pembelian = '<tr id="pembelian-' + urutan + '" style="width:100%;">';
    item_pembelian += '<td style="width: 5%; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';
    item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
    item_pembelian += '<td hidden style="width: 10%" onclick="showCategoryModal(' + urutan + ')" ><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
    item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')" ><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-' + urutan + '" name="kode_lama[]" value="' + kode_lama + '"></div></td>';
    item_pembelian += '<td style="width: 30%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
    item_pembelian += '<td style="width: 10%"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
    item_pembelian += '<td style="width: 10%"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-' + urutan + '" name="diskon[]" value="' + diskon + '" oninput="hitungTotal(' + urutan + ')"></div></td>';
    item_pembelian += '<td hidden style="width: 10%"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nominal_diskon-' + urutan + '" name="nominal_diskon[]" value="' + nominal_diskon + '"></div></td>';
    item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + harga + '"></div></td>';
    item_pembelian += '<td style="width: 10%" onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + total + '"></div></td>';
    item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="totalasli-' + urutan + '" name="totalasli[]" value="' + totalasli + '"></div></td>';
    item_pembelian += '<td style="width: 10%"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
    item_pembelian += '</tr>';


        $('#tabel-pembelian').append(item_pembelian);
    }

</script>


<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'penjualan') {
            window.location.href = "{{ route('toko_pemalang.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
        } else if (selectedValue === 'pelunasan') {
            window.location.href = "{{ route('toko_pemalang.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
        }
    });
</script>

@endsection
