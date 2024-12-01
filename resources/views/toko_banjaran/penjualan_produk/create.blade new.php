@extends('layouts.app')

@section('title', 'Penjualan Produk')

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
.hidden {
    display: none;
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
                    <h1 class="m-0">Penjualan Produk</h1>
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
            <form id="penjualanForm" action="{{ url('toko_banjaran/penjualan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
    
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="float-right">
                            <select class="form-control" id="kategori1" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="penjualan" {{ old('kategori1') == 'penjualan' ? 'selected' : '' }}>Penjualan Produk</option>
                                <option value="pelunasan" {{ old('kategori1') == 'pelunasan' ? 'selected' : '' }}>Pelunasan Pemesanan Produk</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6 mt-2">
                                <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="member" {{ old('kategori') == 'member' ? 'selected' : null }}>Member</option>
                                    <option value="nonmember" {{ old('kategori') == 'nonmember' ? 'selected' : null }}>Non Member</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    
                </div>
                
                <div class="card" id="customerInfoCard" hidden>
                    <div class="card-body">
                        
                        {{-- <div class="row mb-3 align-items-center">
                            <div class="col-md-3 mt-2" id="kodePelangganRow" hidden>
                                <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="Scan kode Pelanggan" onchange="getData(this.value)">
                            </div>
                        </div> --}}

                        <div class="row mb-3 align-items-center" id="namaPelangganRow" style="display: none;">
                            <div class="col-md">
                                <button class="btn btn-outline-primary  btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search"></i> Cari pelanggan
                                </button>
                            </div>
                           
                        </div>
                
                        <div class="row align-items-center" id="telpRow" hidden>
                            <div class="col-md-6 mb-3">
                                <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                <input hidden type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama" value="{{ old('kode_pelangganlama') }}" onclick="showCategoryModalpemesanan()">
                                <input readonly  type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input readonly type="number" id="telp" name="telp" class="form-control"  value="{{ old('telp') }}">
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
                                <table id="datatables6" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Kode Deposit</th>
                                            <th>Kode Pemesanan</th>
                                            <th>Alamat</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dppemesanans as $item)
                                            <tr onclick="getSelectedDataDeposit('{{ $item->kode_dppemesanan }}', '{{ $item->dp_pemesanan }}', '{{ $item->kekurangan_pemesanan }}')">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_dppemesanan }}</td>
                                                <td>{{ $item->dp_pemesanan }}</td>
                                                <td>{{ $item->kekurangan_pemesanan }}</td>
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


                <div class="row">
                    <div class="col-md-6"> 
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Produk...">
                                </div>
                
                                <table id="data" class="table table-bordered table-striped" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th hidden>Kode Produk</th>
                                            <th>Kode Lama</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Member</th>
                                            <th>Diskon Member</th>
                                            <th>Harga Non Member</th>
                                            <th>Diskon Non Member</th>
                                            <th>Stok</th>
                                            <th hidden>QR</th>
                                            <th hidden>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data-body">
                                        @foreach ($produks as $index => $item)
                                        @php
                                        $tokobanjaran = $item->tokobanjaran->first();
                                        $stok = $item->stok_tokobanjaran->sum('jumlah'); 
                                        @endphp
                                        <tr class="pilih-btn hidden"
                                            data-id="{{ $item->id }}"
                                            data-kode="{{ $item->kode_produk }}"
                                            data-kodel="{{ $item->kode_lama }}"
                                            data-catatan="{{ $item->catatanproduk }}"
                                            data-nama="{{ $item->nama_produk }}"
                                            data-member="{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}"
                                            data-diskonmember="{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}"
                                            data-nonmember="{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}"
                                            data-diskonnonmember="{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}"
                                            data-stok = "{{ $stok }}">

                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td hidden>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->kode_lama }}</td>
                                            <td>{{ $item->nama_produk }}</td>
                                            <td><span class="member_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}</span></td>
                                            <td><span class="member_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}</span></td>
                                            <td><span class="non_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}</span></td>
                                            <td><span class="non_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}</span></td>
                                            <td>{{ $stok }}</td>   
                                            <td hidden>{{ $item->qrcode_produk }}</td>

                                            <td hidden class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-kode="{{ $item->kode_produk }}"
                                                    data-kodel="{{ $item->kode_lama }}"
                                                    data-catatan="{{ $item->catatanproduk }}"
                                                    data-nama="{{ $item->nama_produk }}"
                                                    data-member="{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}"
                                                    data-diskonmember="{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}"
                                                    data-nonmember="{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}"
                                                    data-diskonnonmember="{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}"
                                                    data-stok = "{{ $stok }}">

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
                
                    <div class="col-md-6"> 
                        <div class="card" id="selected-products-card" style="display: none;">
                            <div class="card-body">
                                <table class="table table-bordered table-striped" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th hidden style="background-color: #000; color: #fff;">Kode Produk</th>
                                            <th style="background-color: #000; color: #fff;">Kode Lama</th>
                                            <th style="background-color: #000; color: #fff;">Nama Produk</th>
                                            <th style="background-color: #000; color: #fff;">Jumlah</th>
                                            <th style="background-color: #000; color: #fff;">Diskon</th>
                                            <th style="background-color: #000; color: #fff;">Harga</th>
                                            <th style="background-color: #000; color: #fff;">Total</th>
                                            <th style="background-color: #000; color: #fff;">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected-products-body">

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
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="bayar" class="mr-2" style="width: 150px;">Uang Bayar</label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kembali" class="mr-2" style="width: 150px;">Kembali</label>
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
        document.getElementById('searchInput').addEventListener('input', function () {
        const searchText = this.value.toLowerCase(); // Ambil teks pencarian
        const rows = document.querySelectorAll('#data-body tr'); // Ambil semua baris dalam tabel
        let anyMatch = false; // Flag untuk mengecek apakah ada kecocokan

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td'); // Ambil semua kolom dalam baris
            let found = false;

            // Cari kecocokan di setiap kolom kecuali kolom pertama dan terakhir
            for (let i = 1; i < cells.length - 1; i++) {
                if (cells[i].innerText.toLowerCase().includes(searchText)) {
                    found = true;
                    break;
                }
            }

            // Jika ditemukan kecocokan, tampilkan baris
            if (found) {
                row.classList.remove('hidden'); // Menampilkan baris
                anyMatch = true; // Mengatur flag ke true jika ada kecocokan
            } else {
                row.classList.add('hidden'); // Menyembunyikan baris
            }
        });
        });

        // Event listener untuk menangkap "Enter" dan mengambil data
        document.getElementById('searchInput').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') { // Jika tombol Enter ditekan
                event.preventDefault(); // Mencegah aksi default seperti submit form

                const searchText = this.value.toLowerCase();
                
                // Cek apakah input pencarian kosong
                if (searchText.trim() === '') {
                    return; // Jika kosong, hentikan eksekusi
                }

                const rows = document.querySelectorAll('#data-body tr'); // Ambil semua baris dalam tabel
                let foundData = false;

                // Loop melalui baris untuk mencari yang cocok
                rows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    // Cari kecocokan pada kolom data (bukan kolom pertama dan terakhir)
                    for (let i = 1; i < cells.length - 1; i++) {
                        if (cells[i].innerText.toLowerCase().includes(searchText)) {
                            found = true;
                            break;
                        }
                    }

                    // Jika kecocokan ditemukan, ambil data dan masukkan ke tabel yang dipilih
                    if (found) {
                        const productData = {
                            id: row.querySelector('td:nth-child(1)').innerText, // Ambil produk_id dari kolom pertama
                            kode: row.querySelector('td:nth-child(2)').innerText,
                            kodel: row.querySelector('td:nth-child(3)').innerText,
                            nama: row.querySelector('td:nth-child(4)').innerText,
                            member: row.querySelector('.member_harga_bnjr').innerText,
                            diskonmember: row.querySelector('.member_diskon_bnjr').innerText,
                            nonmember: row.querySelector('.non_harga_bnjr').innerText,
                            diskonnonmember: row.querySelector('.non_diskon_bnjr').innerText
                        };

                        // Tambahkan produk ke tabel di card
                        addProductToCard(productData);

                        foundData = true; // Tandai bahwa data telah ditemukan
                    }
                });

                // Jika data ditemukan, kosongkan input pencarian
                if (foundData) {
                    this.value = ''; // Kosongkan input pencarian
                }
            }
        });

    
        let kategoriPelanggan = ''; // Variabel untuk menyimpan tipe pelanggan
    
        // Event listener untuk dropdown kategori (Tipe Pelanggan)
        document.getElementById('kategori').addEventListener('change', function() {
            kategoriPelanggan = this.value; // Simpan pilihan tipe pelanggan
        });

        function calculateSubTotal() {
            const rows = document.querySelectorAll('#selected-products-body tr'); // Ambil semua baris di tabel
            let total = 0;
            let totalAsli = 0;

            // Loop melalui setiap baris dan tambahkan nilai total-amount serta harga asli ke total keseluruhan
            rows.forEach(row => {
                const jumlah = parseInt(row.querySelector('.jumlah-input').value, 10) || 0;
                const totalAmount = parseFloat(removeRupiahFormat(row.querySelector('.total-amount').innerText)) || 0;
                const hargaAsli = parseFloat(removeRupiahFormat(row.querySelector('input[name="totalasli[]"]').value)) || 0;

                total += totalAmount;
                totalAsli += hargaAsli * jumlah; // Harga asli dikali dengan jumlah produk
            });

            // Tampilkan total ke dalam input sub_total
            const subTotalElement = document.getElementById('sub_total');
            subTotalElement.value = Number.isInteger(total) ? formatRupiah(total.toString()) : formatRupiah(total.toFixed(2));

            // Tampilkan total asli ke dalam input sub_totalasli
            const subTotalAsliElement = document.getElementById('sub_totalasli');
            subTotalAsliElement.value = Number.isInteger(totalAsli) ? formatRupiah(totalAsli.toString()) : formatRupiah(totalAsli.toFixed(2));
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
        
        function addProductToCard(productData) {
        const tbody = document.getElementById('selected-products-body');
        let harga = 0;
        let diskon = 0;

        // Cek tipe pelanggan untuk menentukan harga dan diskon
        if (kategoriPelanggan === 'member') {
            harga = parseFloat(productData.member) || 0;
            diskon = parseFloat(productData.diskonmember) || 0;
        } else if (kategoriPelanggan === 'nonmember') {
            harga = parseFloat(productData.nonmember) || 0;
            diskon = parseFloat(productData.diskonnonmember) || 0;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Tipe Pelanggan Belum Dipilih',
                text: 'Silakan pilih tipe pelanggan terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Cek apakah produk dengan produk_id yang sama sudah ada di tabel
        const existingRow = Array.from(tbody.querySelectorAll('tr')).find(row => {
            const produkId = row.querySelector('input[name="produk_id[]"]').value;
            return produkId === productData.id;
        });

        if (existingRow) {
            // Jika produk sudah ada, tambahkan jumlahnya
            const jumlahInput = existingRow.querySelector('.jumlah-input');
            let jumlah = parseInt(jumlahInput.value, 10) || 0;
            jumlah += 1; // Tambah jumlah 1 (atau sesuaikan logika jika ingin menambah default jumlah input)
            jumlahInput.value = jumlah; // Set jumlah baru

            // Hitung total harga berdasarkan jumlah baru
            const total = calculateTotal(harga, diskon, jumlah);
            existingRow.querySelector('.total-amount').innerText = total;
            existingRow.querySelector('input[name="total[]"]').value = total; // Update hidden input total

            existingRow.querySelector('input[name="jumlah[]"]').value = jumlah; // Update jumlah terbaru

        } else {
            // Jika produk belum ada, tambahkan produk baru ke tabel
            const row = document.createElement('tr');
            const jumlahInput = 1; // Default value for new product
            row.innerHTML = `
            <td hidden>${productData.kode}</td>
            <td>${productData.kodel}</td>
            <td style="width: 30%;">${productData.nama}</td>
            <td style="width: 20%;"><input type="number" class="form-control jumlah-input" value="${jumlahInput}" min="1"></td>
            <td>${diskon}%</td>
            <td>${harga}</td>
            <td class="total-amount">${calculateTotal(harga, diskon, jumlahInput)}</td>
            <td>
                <button class="btn btn-danger delete-btn btn-sm"><i class="fas fa-trash"></i></button>
            </td>
            <input type="hidden" name="produk_id[]" value="${productData.id}" />
            <input type="hidden" name="kode_produk[]" value="${productData.kode}" />
            <input type="hidden" name="kode_lama[]" value="${productData.kodel}" />
            <input type="hidden" name="nama_produk[]" value="${productData.nama}" />
            <input type="hidden" name="harga[]" value="${harga}" />
            <input type="hidden" name="diskon[]" value="${diskon}" />
            <input type="hidden" name="total[]" value="${calculateTotal(harga, diskon, jumlahInput)}" />
            <input type="hidden" name="totalasli[]" value="${harga}" />
            <input type="hidden" name="jumlah[]" value="${jumlahInput}" />
            `;
            tbody.appendChild(row);

            // Tambahkan event listener ke input jumlah
            row.querySelector('.jumlah-input').addEventListener('input', function() {
                const jumlah = parseInt(this.value, 10) || 0;
                const total = calculateTotal(harga, diskon, jumlah);
                row.querySelector('.total-amount').innerText = total;
                row.querySelector('input[name="total[]"]').value = total;
                row.querySelector('input[name="jumlah[]"]').value = jumlah; // Update jumlah sesuai input
                calculateSubTotal();
            });

            // Event listener untuk tombol hapus
            row.querySelector('.delete-btn').addEventListener('click', function() {
                row.remove(); // Menghapus row dari tabel
                calculateSubTotal(); // Update subtotal setelah menghapus
            });
        }

        document.getElementById('selected-products-card').style.display = 'block';
        calculateSubTotal();
        }

        function calculateTotal(harga, diskon, jumlah) {
        const diskonAmount = harga * (diskon / 100); // Hitung diskon berdasarkan persentase
        const hargaSetelahDiskon = harga - diskonAmount; // Kurangi harga dengan diskon
        const total = jumlah * hargaSetelahDiskon; // Total untuk jumlah produk

        // Jika total merupakan bilangan bulat, tampilkan tanpa desimal
        return Number.isInteger(total) ? total : total.toFixed(2);
        }

        // Fungsi untuk format input 'bayar' dan update kembali
        function formatAndUpdateKembali() {
            let subTotalElement = document.getElementById('sub_total');
            let bayarElement = document.getElementById('bayar');
            let kembaliElement = document.getElementById('kembali');

            // Mengambil nilai sub_total
            let subTotal = removeRupiahFormat(subTotalElement.value);

            // Format dan ambil nilai bayar
            let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
            let bayar = parseFloat(bayarValue) || 0;

            // Format input 'bayar'
            bayarElement.value = formatRupiah(bayarValue);

            // Hitung kembalian
            let kembali = bayar - subTotal;

            // Validasi pelunasan
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
                        
        // Event listener untuk tombol "pilih"
        document.querySelectorAll('.pilih-btn').forEach(button => {
            button.addEventListener('click', function() {
                            // Ambil data produk berdasarkan tipe pelanggan
                const productData = {
                    id: this.dataset.id,  // Mengambil produk_id dari data-id

                    kode: this.dataset.kode,
                    kodel: this.dataset.kodel,
                    nama: this.dataset.nama,
                    member: this.dataset.member,
                    diskonmember: this.dataset.diskonmember,
                    nonmember: this.dataset.nonmember,
                    diskonnonmember: this.dataset.diskonnonmember
                };
                
                            // Masukkan produk ke tabel berdasarkan tipe pelanggan
                addProductToCard(productData);
            });
            });
                
                    // Fungsi penyimpanan hanya berjalan ketika tombol simpan ditekan
            document.getElementById('saveButton').addEventListener('click', function() {
            const rows = document.querySelectorAll('#selected-products-body tr');
            const dataToSave = [];

            rows.forEach(row => {
                const productId = row.querySelector('input[name="produk_id[]"]').value;
                const jumlah = row.querySelector('input[name="jumlah[]"]').value;
                const total = row.querySelector('input[name="total[]"]').value;

                dataToSave.push({
                    productId,
                    jumlah,
                    total
                });
            });
            console.log('Simpan data ke server');
                        // Contoh: Mengirim data ke server menggunakan AJAX atau form submit
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
                    url: "{{ url('toko_banjaran/metodebayar/metode') }}" + "/" + metodeId,
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
            var totalFee = (subTotal * fee / 100) || 0;
            var finalTotal = subTotal + totalFee;
    
            // Format the values without .00
            function formatCurrency(value) {
                var formattedValue = value.toFixed(2).replace(/\.00$/, '');
                return 'Rp' + formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
    
            // Update total fee and final sub total fields
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

        // Formulir akan disubmit dengan nilai numerik
    });

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'penjualan') {
            window.location.href = "{{ route('toko_banjaran.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
        } else if (selectedValue === 'pelunasan') {
            window.location.href = "{{ route('toko_banjaran.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
        }
    });
</script>

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
    // Menghide form inputan
    document.addEventListener('DOMContentLoaded', function() {
        var kategoriSelect = document.getElementById('kategori');
        var customerInfoCard = document.getElementById('customerInfoCard');
        // var customerInputCard = document.getElementById('customerInputCard');
        var namaPelangganRow = document.getElementById('namaPelangganRow');
        var telpRow = document.getElementById('telpRow');
        // var alamatRow = document.getElementById('alamatRow');
        // var kodePelangganRow = document.getElementById('kodePelangganRow');
        var namaPelangganInput = document.getElementById('nama_pelanggan');

        kategoriSelect.addEventListener('change', function() {
            if (kategoriSelect.value === 'member') {
                customerInfoCard.hidden = false;  
                // customerInputCard.hidden = false; 
                // kodePelangganRow.hidden = false;
                namaPelangganInput.readOnly = true;
                namaPelangganRow.style.display = 'block';
                telpRow.hidden = false;
                // alamatRow.hidden = false;
            } else if (kategoriSelect.value === 'nonmember') {
                customerInfoCard.hidden = true;  
                // customerInputCard.hidden = true;  
                // kodePelangganRow.hidden = true;
                namaPelangganInput.readOnly = false;
                namaPelangganRow.style.display = 'none';
                telpRow.hidden = true;
                // alamatRow.hidden = true;
            } else {
                customerInfoCard.hidden = true;  // Sembunyikan kartu informasi pelanggan
                // customerInputCard.hidden = true;  // Sembunyikan kartu informasi pelanggan
                namaPelangganRow.style.display = 'none';
                telpRow.hidden = true;
                // alamatRow.hidden = true;
                // kodePelangganRow.hidden = true;
            }
        });
    });
</script>

@endsection
