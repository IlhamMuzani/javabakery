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
                    <h1 class="m-0">Pemusnahan Retur Barang Jadi</h1>
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
            <form action="{{ url('admin/pemusnahan_barangjadi') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group d-flex">
                           
                                <div class="col-md">
                                    <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                        <i class="fas fa-plus" style=""></i>
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
                                        <h3 class="card-title">Detail Retur</h3>
                                    </div>
                                    <div class="card-body">
                                      
                                        <div class="form-group">
                                            <label style="font-size:14px" for="kode_retur">Kode Retur</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="kode_retur" readonly name="kode_retur" placeholder="" value="{{ old('kode_retur') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="tanggal_retur">Tanggal Retur</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="tanggal_retur" readonly name="tanggal_retur" placeholder="" value="{{ old('tanggal_retur') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="tanggal_terima">Tanggal Terima</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="tanggal_terima" readonly name="tanggal_terima" placeholder="" value="{{ old('tanggal_terima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="keterangan">Keterangan</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="keterangan" readonly name="keterangan" placeholder="" value="{{ old('keterangan') }}">
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="font-size:14px" for="productDetails">Detail Produk</label>
                                <div id="productDetails" style="font-size:14px;"></div>
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
                        <h4 class="modal-title">Data Retur Barang Jadi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr style="font-size: 13px">
                                    <th class="text-center">No</th>
                                    <th>Kode Retur</th>
                                    <th>Tanggal Retur</th>
                                    <th>Tanggal Terima</th>
                                    <th>Keterangan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="returTableBody">
                                <!-- Data akan dimuat melalui AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        

    </section>

    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
    $('#tableDeposit').on('show.bs.modal', function () {
        $.ajax({
            url: "{{ route('getReturData') }}",
            method: 'GET',
            success: function(data) {
                var tableBody = $('#returTableBody');
                tableBody.empty(); // Bersihkan tabel sebelum menambahkan data baru

                data.forEach(function(item, index) {
                    var row = `
                        <tr style="font-size: 13px">
                            <td class="text-center">${index + 1}</td>
                            <td>${item.kode_retur}</td>
                            <td>${item.tanggal_retur}</td>
                            <td>${item.tanggal_terima}</td>
                            <td>${item.keterangan}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm pilihRetur" 
                                    data-kode="${item.kode_retur}">
                                    Pilih
                                </button>
                            </td>
                        </tr>`;
                    tableBody.append(row);
                });
            }
        });
    });

    $('#returTableBody').on('click', '.pilihRetur', function() {
    var kodeRetur = $(this).data('kode');
    
    // Ambil detail produk berdasarkan kode_retur yang dipilih
    $.ajax({
        url: "{{ url('admin/get-products-by-kode-retru') }}/" + kodeRetur,
        method: 'GET',
        success: function(products) {
            // Reset data form
            $('#kode_retur').val(kodeRetur);
            $('#tanggal_retur').val('');
            $('#tanggal_terima').val('');
            $('#keterangan').val('');

            // Tambah data produk ke form atau modal
            var produkHtml = '';
            products.forEach(function(product) {
                produkHtml += `<div>
                    Nama Produk: ${product.nama_produk} - Jumlah: ${product.jumlah}
                </div>`;
            });

            // Tampilkan produk di form atau modal
            $('#productDetails').html(produkHtml);

            // Tutup modal setelah memilih data
            $('#tableDeposit').modal('hide');
        },
        error: function(xhr, status, error) {
            // Tangani jika terjadi error
            console.error('Terjadi kesalahan:', error);
        }
    });
});

});



    // Ketika tombol 'Pilih' diklik, isi form dengan data yang sesuai
    $('#returTableBody').on('click', '.pilihRetur', function() {
        var kodeRetur = $(this).data('kode');
        var tanggalInput = $(this).data('tanggal-input');
        var tanggalTerima = $(this).data('tanggal-terima');
        var keterangan = $(this).data('keterangan');
        
        $('#kode_retur').val(kodeRetur);
        $('#tanggal_retur').val(tanggalInput);
        $('#tanggal_terima').val(tanggalTerima);
        $('#keterangan').val(keterangan);
        
        // Tutup modal setelah memilih data
        $('#tableDeposit').modal('hide');
    });


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


@endsection
