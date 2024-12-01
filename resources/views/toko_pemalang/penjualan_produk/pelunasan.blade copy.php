@extends('layouts.app')

@section('title', 'Pelunasan Pemesanan')

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
            <form action="{{ url('admin/penjualan_produk/pelunasan') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="penjualan" {{ old('kategori') == 'penjualan' ? 'selected' : '' }}>Penjualan Produk</option>
                                <option value="pelunasan" {{ old('kategori') == 'pelunasan' ? 'selected' : '' }}>Pelunasan Pemesanan Produk</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <label style="font-size:14px" class="form-label" for="kode_dppemesanan">Kode Pemesanan</label>
                        <div class="form-group d-flex">
                            <input class="form-control" hidden id="dppemesanan_id" name="dppemesanan_id" type="text"
                                placeholder="" value="{{ old('dppemesanan_id') }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control col-md-4" id="kode_pemesanan" name="kode_pemesanan" type="text" placeholder="masukan kode pemesanan produk"
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

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="sub_total" class="mr-2 label-width">Sub Total</label>
                                                <input type="text" class="form-control large-font input-width" id="sub_total" name="sub_total" value="{{ old('sub_total') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="dp_pemesanan" class="mr-2 label-width">DP</label>
                                                <input type="text" class="form-control large-font input-width" id="dp_pemesanan" name="dp_pemesanan" readonly value="{{ old('dp_pemesanan') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kekurangan_pemesanan" class="mr-2 label-width">Kekurangan</label>
                                                <input type="text" class="form-control large-font input-width" id="kekurangan_pemesanan" name="kekurangan_pemesanan" value="{{ old('kekurangan_pemesanan') }}" readonly>
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
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="pelunasan" class="mr-2 label-width">Bayar</label>
                                                <input type="number" class="form-control large-font input-width" id="pelunasan" name="pelunasan" value="{{ old('pelunasan') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kembali" class="mr-2 label-width">Kembali</label>
                                                <input type="number" class="form-control large-font input-width" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
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
                                    <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>

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
                                    <th>Total</th>
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
                                        <td>{{ 'Rp. ' .number_format($return->pemesananproduk ? $return->pemesananproduk->sub_total : 0, 0, ',', '.') }}</td>
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

    </section>


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
        // Fungsi untuk memformat angka menjadi Rupiah
        function formatRupiah(angka, prefix) {
            var numberString = angka.replace(/[^,\d]/g, '').toString(),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    
        // Fungsi untuk memperbarui format input dengan Rupiah
        function formatAndUpdateKembali() {
            var dpInput = document.getElementById('dp_pemesanan');
            var dpValue = dpInput.value.replace(/\D/g, ''); // Menghapus semua karakter kecuali angka
            dpInput.value = formatRupiah(dpValue, 'Rp. ');
    
            var subTotalValue = document.getElementById('sub_total').value.replace(/\D/g, '');
            var kekuranganPemesanan = parseInt(subTotalValue) - parseInt(dpValue);
            document.getElementById('kekurangan_pemesanan').value = formatRupiah(kekuranganPemesanan.toString(), 'Rp. ');
        }
    
        // Fungsi untuk memformat semua input pada saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('sub_total').value = formatRupiah(document.getElementById('sub_total').value, 'Rp. ');
            document.getElementById('dp_pemesanan').value = formatRupiah(document.getElementById('dp_pemesanan').value, 'Rp. ');
            document.getElementById('kekurangan_pemesanan').value = formatRupiah(document.getElementById('kekurangan_pemesanan').value, 'Rp. ');
        });
    </script>
    
    <script>
        // Function to fetch data based on kode_pemesanan
        function fetchDataByKode(kode) {
            $.ajax({
                url: '{{ route("toko_banjaran.penjualan_produk.fetchData") }}', // Adjust the route accordingly
                method: 'GET',
                data: { kode_pemesanan: kode },
                success: function(response) {
                    // Populate the form fields with the retrieved data
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
                    document.getElementById('sub_total').value = response.sub_total;
                    document.getElementById('dp_pemesanan').value = response.dp_pemesanan;
                    document.getElementById('kekurangan_pemesanan').value = response.kekurangan_pemesanan;
    
                    // Update the form with products details if available
                    if (response.products) {
                        var formHtml = '<div class="card mb-3">' +
                            '<div class="card-header">' +
                            '<h3 class="card-title">Detail Pemesanan</h3>' +
                            '</div>' +
                            '<div class="card-body">' +
                            '<table class="table table-bordered table-striped">' +
                            '<thead>' +
                            '<tr>' +
                            '<th style="font-size:14px" class="text-center">No</th>' +
                            '<th style="font-size:14px">Kode Produk</th>' +
                            '<th style="font-size:14px">Nama Produk</th>' +
                            '<th style="font-size:14px">Jumlah</th>' +
                            '<th style="font-size:14px">Total</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody id="tabel-pembelian">';
    
                        response.products.forEach((product, index) => {
                            formHtml += '<tr>' +
                                '<td style="width: 70px; font-size:14px" class="text-center urutan">' + (index + 1) + '</td>' +
                                '<td>' +
                                '   <div class="form-group">' +
                                '       <input style="font-size:14px" readonly type="text" class="form-control kode_produk" name="kode_produk[]" value="' + product.kode_produk + '">' +
                                '   </div>' +
                                '</td>' +
                                '<td>' +
                                '   <div class="form-group">' +
                                '       <input style="font-size:14px" readonly type="text" class="form-control nama_produk" name="nama_produk[]" value="' + product.nama_produk + '">' +
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
                                '</tr>';
                        });
    
                        formHtml += '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>';
    
                        $('#forms-container').html(formHtml);
                    }
    
                    updateGrandTotal();
                },
                error: function(xhr) {
                    alert('Data tidak ditemukan.');
                }
            });
        }
    
        $(document).ready(function() {
            // Fetch data when input field value changes
            $('#kode_pemesanan').on('input', function() {
                var kode = $(this).val();
                if (kode) {
                    fetchDataByKode(kode);
                }
            });
        });

         
    </script>
    
    <script>
        function formatAndUpdateKembali() {
            // Ambil nilai dari input fields
            var pelunasan = parseFloat(document.getElementById('pelunasan').value) || 0;
            var kekurangan = parseFloat(document.getElementById('kekurangan_pemesanan').value) || 0;
    
            // Hitung kembalian
            var kembali = pelunasan - kekurangan;
    
            // Perbarui nilai input kembalian
            document.getElementById('kembali').value = kembali.toFixed(0);
        }
    
        // Panggil fungsi saat halaman dimuat pertama kali untuk memastikan nilai awal sudah benar
        document.addEventListener('DOMContentLoaded', formatAndUpdateKembali);
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
            window.location.href = "{{ route('toko_banjaran.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
        } else if (selectedValue === 'pelunasan') {
            window.location.href = "{{ route('toko_banjaran.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
        }
    });
</script>


@endsection
