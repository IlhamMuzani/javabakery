@extends('layouts.app')

@section('title', 'Input Barang Jadi')

@section('content')
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
                    <h1 class="m-0">Input Stok Barang Jadi</h1>

                    {{-- <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin/input/data') }}">Barang Jadi</a></li>
                        </ol>
                    </div><!-- /.col --> --}}

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Barang Jadi</a></li>
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
        <form action="{{ url('admin/input') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="card">
                <div class="card-header" >
                    <div class="row">
                        <div class="col-md-4 mb-3" hidden>
                            <label for="kode_faktur">No Faktur</label>
                            <input type="text"  class="form-control" id="kode_faktur" name="kode_faktur"
                               value="{{ old('kode_faktur') }}">
                        </div>
                        {{-- <div class="col-md-4 mb-3">
                            <label>Tanggal :</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal" name="tanggal"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div> --}}
                        <div class="col-md-4 mb-3">
                            <label>Tanggal :</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal" name="tanggal"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div>
                        
                        <div class="col mb-3">
                            <label class="form-label" for="cabang">Pilih Cabang</label>
                            <select class="form-control" id="cabang" name="cabang">
                                <option value="">- Pilih -</option>
                                <option value="procot" {{ old('cabang') == 'L' ? 'selected' : null }}>
                                    Procot</option>
                                <option value="benjaran" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Benjaran</option>
                                <option value="tegal" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Tegal</option>
                                <option value="bumiayu" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Bumaiayu</option>
                                <option value="pekalongan" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Pekalongan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-header">
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
                                <th class="text-center">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-pembelian">
                            <!-- Data dari JavaScript akan dimasukkan di sini -->
                        </tbody>
                    </table>
                </div>
            </div> --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><span>
                        </span></h3>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-center">No</th>
                                        <th style="font-size:14px">Kode Barang</th>
                                        <th style="font-size:14px">Nama Barang</th>
                                        <th style="font-size:14px">Jumlah</th>
                                        <th style="font-size:14px">Harga</th>
                                        <th style="font-size:14px">Total</th>
                                        <th style="font-size:14px; text-align:center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-pembelian">
                                
                                </tbody>
                            </table>
                        </div>

                        {{-- <div class="col">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-center">No</th>
                                        <th style="font-size:14px">Kode Barang</th>
                                        <th style="font-size:14px">Nama Barang</th>
                                        <th style="font-size:14px">Jumlah</th>
                                        <th style="font-size:14px">Harga</th>
                                        <th style="font-size:14px">Total</th>
                                        <th style="font-size:14px; text-align:center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-pembelian">
                                
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                    {{-- <div class="form-group mt-2">
                        <label style="font-size:14px" for="keterangan">Keterangan</label>
                        <textarea style="font-size:14px" type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Masukan keterangan">{{ old('keterangan') }}</textarea>
                    </div> --}}
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3 ml-auto">
                            <label for="sub_total">Sub Total</label>
                            <input type="text" class="form-control large-font" id="sub_total" name="sub_total"
                                value="{{ old('sub_total') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="catatan">Catatan</label>
                            <textarea type="text"  class="form-control" id="catatan" name="catatan"
                               value="{{ old('catatan') }}"></textarea>
                        </div>
                        {{-- <div class="col-md-4 mb-3">
                            <label>Tanggal Pengiriman:</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal_pengiriman') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div> --}}

                        <div class="col-md-5 mb-3">
                            <label for="catatan">Bagian Input  :</label>
                           <input type="text" class="form-control" readonly value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
                        </div>
                    </div>
                </div>
            </div>

                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            
        </form>

        {{-- Modal untuk memilih barang --}}
        <div class="modal fade" id="tableMarketing" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Produk</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{number_format($item->harga, 0, ',', '.') }}</td> <!-- Format harga -->
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="getSelectedData('{{ $item->id }}','{{ $item->kode_produk }}', '{{ $item->nama_produk }}', '{{ $item->harga}}')">
                                            <i class="fas fa-plus"></i> Pilih
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

        </div>



    {{-- <script>
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
    
        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            $('#tableMarketing').modal('show');
            // Simpan urutan untuk menyimpan data ke baris yang sesuai
            $('#tableMarketing').attr('data-urutan', urutan);
        }
    
        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(id, kode_produk, subsub_id, harga) {
            var urutan = $('#tableMarketing').attr('data-urutan');
            // Set nilai input pada baris yang sesuai
            $('#produk_id-' + urutan).val(id);
            $('#kode_produk-' + urutan).val(kode_produk);
            $('#nama_produk-' + urutan).val(subsub_id);
            $('#harga-' + urutan).val(harga);
            // Hitung total
            hitungTotal(urutan);
            // Tutup modal
            $('#tableMarketing').modal('hide');
        }
    
        // Fungsi untuk menghitung total berdasarkan harga dan jumlah
        function hitungTotal(urutan) {
            var harga = parseFloat($('#harga-' + urutan).val()) || 0;
            var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;
            var total = harga * jumlah;
            // Format total ke dalam format rupiah dan set nilai input total
            $('#total-' + urutan).val(total.toFixed(2));
            // Hitung subtotal setiap kali total di baris berubah
            hitungSubTotal();
        }
    
        // Fungsi untuk menghitung subtotal semua barang
        function hitungSubTotal() {
            var subTotal = 0;
            $('[id^=total-]').each(function() {
                var total = parseFloat($(this).val()) || 0;
                subTotal += total;
            });
            $('#sub_total').val(subTotal.toFixed(2));
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
            var nama_produk = '';
            var jumlah = '';
            var harga = '';
            var total = '';
    
            if (value !== null) {
                produk_id = value.produk_id;
                kode_produk = value.kode_produk;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                harga = value.harga;
                total = value.total;
            }
    
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';
            item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
            item_pembelian += '<td onclick="tableMarketing(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
            item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + harga + '"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + total + '"></div></td>';
            item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
            item_pembelian += '</tr>';
    
            $('#tabel-pembelian').append(item_pembelian);
        }
    
 
        function formatRupiah(angka, prefix = 'Rp') {
            var number_string = angka.toString().replace(/[^,\d]/g, '');
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }
    
    </script>
     --}}

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
    
        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            $('#tableMarketing').modal('show');
            // Simpan urutan untuk menyimpan data ke baris yang sesuai
            $('#tableMarketing').attr('data-urutan', urutan);
        }
    
        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(id, kode_produk, subsub_id, harga) {
            var urutan = $('#tableMarketing').attr('data-urutan');
            // Set nilai input pada baris yang sesuai
            $('#produk_id-' + urutan).val(id);
            $('#kode_produk-' + urutan).val(kode_produk);
            $('#nama_produk-' + urutan).val(subsub_id);
            $('#harga-' + urutan).val(formatRupiah(harga));
            // Hitung total
            hitungTotal(urutan);
            // Tutup modal
            $('#tableMarketing').modal('hide');
        }
    
        // Fungsi untuk menghitung total berdasarkan harga dan jumlah
        function hitungTotal(urutan) {
            var harga = parseFloat($('#harga-' + urutan).val().replace(/[^0-9]/g, '')) || 0;
            var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;
            var total = harga * jumlah;
            // Format total ke dalam format rupiah dan set nilai input total
            $('#total-' + urutan).val(formatRupiah(total));
            // Hitung subtotal setiap kali total di baris berubah
            hitungSubTotal();
        }
    
        // Fungsi untuk menghitung subtotal semua barang
        function hitungSubTotal() {
            var subTotal = 0;
            $('[id^=total-]').each(function() {
                var total = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
                subTotal += total;
            });
            $('#sub_total').val(formatRupiah(subTotal));
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
            var nama_produk = '';
            var jumlah = '';
            var harga = '';
            var total = '';
    
            if (value !== null) {
                produk_id = value.produk_id;
                kode_produk = value.kode_produk;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                harga = value.harga;
                total = value.total;
            }
    
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';
            item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
            item_pembelian += '<td onclick="tableMarketing(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
            item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + formatRupiah(harga) + '"></div></td>';
            item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + formatRupiah(total) + '"></div></td>';
            item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
            item_pembelian += '</tr>';
    
            $('#tabel-pembelian').append(item_pembelian);
        }
    
        function formatRupiah(angka, prefix = 'Rp') {
            var number_string = angka.toString().replace(/[^,\d]/g, '');
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }
    </script>
    
@endsection
