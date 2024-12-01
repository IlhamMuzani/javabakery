@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .klasifikasi-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .klasifikasi-header:hover {
            background-color: #f0f0f0;
        }
        .klasifikasi-header.active {
            background-color: #e0e0e0;
        }
        .produk-table {
            display: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 10); // Adjust the delay time as needed
        });
    </script>
    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemindahan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
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
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                
                <!-- /.card-header -->
                <div class="card-body">
                    

                    <form action="{{ url('toko_tegal/pemindahan_tokotegal') }}" method="POST">
                        @csrf
                        <input type="hidden" name="toko_id" > <!-- Assuming $toko is passed from the controller -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                    </thead>
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
                                                                <th style="font-size:14px">Kode Produk</th>
                                                                <th style="font-size:14px">Nama Produk</th>
                                                                <th style="font-size:14px">Jumlah</th>
                                                                <th style="font-size:14px">Keterangan</th>
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
                                </table>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                      

                        <div class="modal fade" id="tableProduk" data-backdrop="static">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Data Stok Barang Jadi</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-sm-12 text-right">
                                            <input type="text" id="searchProduk" class="form-control" placeholder="Cari produk..." style="display: inline-block; width: auto; margin-bottom: 10px;">
                                        </div>
                                        <table id="tableproduk" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Kode Produk</th>
                                                    <th>Nama Produk</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($produks as $item)
                                                    <tr class="pilih-btn" data-id="{{ $item->id }}" data-kode="{{ $item->kode_lama }}" data-nama="{{ $item->nama_produk }}">
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ $item->kode_lama }}</td>
                                                        <td>{{ $item->nama_produk }}</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-primary btn-sm pilih-btn" data-id="{{ $item->id }}" data-kode="{{ $item->kode_lama }}" data-nama="{{ $item->nama_produk }}">
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
                        
                      
                    </form>
                    
             
                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
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
    // Event listener untuk input pencarian
    $("#searchProduk").on("keyup", function(e) {
           if (e.key === "Enter") {
               e.preventDefault(); // Mencegah submit default
               var value = $(this).val().toLowerCase();
               var visibleRow = $("#tableproduk tbody tr:visible").first();
   
               if (visibleRow.length) {
                   var id = visibleRow.data('id');
                   var kode = visibleRow.data('kode');
                   var nama = visibleRow.data('nama');
   
                   getSelectedData(id, kode, nama);
               }
           } else {
               var value = $(this).val().toLowerCase();
               $("#tableproduk tbody tr").filter(function() {
                   $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
               });
           }
       });

   </script>
   
<script>
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
       $('#tableProduk').modal('show');
       // Simpan urutan untuk menyimpan data ke baris yang sesuai
       $('#tableProduk').attr('data-urutan', urutan);
       $('#searchProduk').focus();
   }
   
   // Event listener for pilih-btn
   $(document).on('click', '.pilih-btn', function() {
       var id = $(this).data('id');
       var kode = $(this).data('kode');
       var nama = $(this).data('nama');
   
       getSelectedData(id, kode, nama);
   });
   
   // Fungsi untuk memilih data barang dari modal
   function getSelectedData(id, kode_lama, nama_produk) {
       var urutan = $('#tableProduk').attr('data-urutan');
   
       // Set nilai input pada baris yang sesuai
       $('#produk_id-' + urutan).val(id);
       $('#kode_lama-' + urutan).val(kode_lama);
       $('#nama_produk-' + urutan).val(nama_produk);
   
       $('#tableProduk').modal('hide');
   
       // Setelah menambahkan data dari modal, fokuskan ke input jumlah
       document.getElementById('jumlah-' + urutan).focus();
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
        var nama_produk = '';
        var jumlah = '';
        var keterangan = '';

        if (value !== null) {
            produk_id = value.produk_id;
            kode_lama = value.kode_lama;
            nama_produk = value.nama_produk;
            jumlah = value.jumlah;
            keterangan = value.keterangan;
        }

        var item_pembelian = '<tr id="pembelian-' + urutan + '">';
        item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';
        item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-' + urutan + '" name="kode_lama[]" value="' + kode_lama + '"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
        item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
        
        // Tambahkan select box untuk memilih toko pada keterangan
        item_pembelian += '<td><div class="form-group"><select class="form-control" style="font-size:14px" id="keterangan-' + urutan + '" name="keterangan[]">';
        @foreach ($tokos as $toko)
        item_pembelian += '<option value="{{ $toko->nama_toko }}"' + (keterangan === '{{ $toko->nama_toko }}' ? ' selected' : '') + '>{{ $toko->nama_toko }}</option>';
        @endforeach
        item_pembelian += '</select></div></td>';

        item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
        item_pembelian += '</tr>';

        $('#tabel-pembelian').append(item_pembelian);
    }


</script>

@endsection
