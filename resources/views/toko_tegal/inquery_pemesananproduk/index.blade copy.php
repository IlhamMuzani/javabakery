@extends('layouts.app')

@section('title', 'Data Pemesanan Produk')

@section('content')
<style>
    .context-menu {
        display: none;
        position: absolute;
        z-index: 1000;
        width: 150px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }
    .context-menu ul {
        list-style: none;
        padding: 5px 0;
        margin: 0;
    }
    .context-menu ul li {
        padding: 8px 12px;
        cursor: pointer;
    }
    .context-menu ul li:hover {
        background-color: #f2f2f2;
    }
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.inquery_pemesananproduk.index') }}">Data Pemesanan Produk</a></li>
                        <li class="breadcrumb-item active">Filtered Data</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
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

            <!-- Form filter -->
            <div class="row mb-3" style="font-size: 14px">
                <form action="{{ route('admin.inquery_pemesananproduk.index') }}" method="get" class="form-inline">
                    <div class="col-mb-3 ml-2">
                        <label for="start_date" class="mr-2">Dari Tanggal:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-mb-3">
                        <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-mb-3" style="margin-top: 22px">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </div>
                </form>
            </div>
            

            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-bordered table-hover " style="font-size: 14px">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Kode Pemesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total</th>
                   

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemesanans as $pemesanan)
                                @foreach ($pemesanan->detailpemesananproduk as $detail)
                                    <tr id="row_{{ $detail->id }}">
                                        <td class="text-center">{{ $loop->parent->iteration }}</td>
                                        <td>{{ $pemesanan->kode_pemesanan }}</td>
                                        <td>{{ $pemesanan->nama_pelanggan }}</td>
                                        <td>{{ $detail->nama_produk }}</td>
                                        <td>{{ $detail->harga }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>{{ $detail->total }}</td>
                                    </tr>

                                    <!-- Modal QR Code -->
                                    <div class="modal fade" id="modal-qrcode-{{ $detail->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Gambar QR Code</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="text-align: center;">
                                                        <p style="font-size:20px; font-weight: bold;">
                                                            {{ $pemesanan->kode_pemesanan }}</p>
                                                        <div style="display: inline-block;">
                                                            {!! DNS2D::getBarcodeHTML($pemesanan->qrcode_pemesanan, 'QRCODE', 10, 10) !!}
                                                        </div>
                                                        <p style="font-size:20px; font-weight: bold;">
                                                            {{ $pemesanan->nama_pelanggan }}</p>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                        <a href="{{ url('admin/karyawan/cetak-pdf/' . $pemesanan->id) }}" class="btn btn-primary btn-sm">
                                                            <i class=""></i> Cetak
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

   <!-- Context Menu -->
   <div class="context-menu" id="context-menu">
    <ul>
        <li id="edit">
            <a href="">
            </a>Ubah</li>
        <li id="delete">Hapus</li>
    </ul>
</div>

<script>
    $(document).ready(function() {
        let currentRowId;
        $('#datatables1 tbody tr').on('contextmenu', function(e) {
    e.preventDefault();
    currentRowId = $(this).data('id');
    
    // Mendapatkan koordinat klik
    var posX = e.pageX;
    var posY = e.pageY;
    
    // Menyesuaikan posisi dropdown agar muncul di samping kursor
    $('#context-menu').css({
        display: 'block',
        position: 'fixed',
        left: posX + 'px',
        top: posY + 'px'
    });
    
        return false; // Mencegah munculnya konteks menu browser bawaan
    });

        // Hide context menu on clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#context-menu').length) {
                $('#context-menu').hide();
            }
        });

        // Handle context menu options
        $('#edit').on('click', function() {
            window.location.href = '/admin/pemesanan_produk/edit/' + currentRowId;
        });

        $('#delete').on('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                window.location.href = '/admin/pemesanan_produk/delete/' + currentRowId;
            }
        });
    });
</script>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Menangkap acara klik kanan pada baris tabel
            $('table tbody tr').on('contextmenu', function(e) {
                // Hentikan perilaku default dari klik kanan
                e.preventDefault();

                // Sembunyikan semua menu konteks
                $('.dropdown-menu').hide();

                // Tampilkan menu konteks yang sesuai hanya pada baris yang diklik
                $(this).find('.dropdown-menu').css({
                    display: "block",
                    left: e.pageX,
                    top: e.pageY
                });
            });

            // Sembunyikan menu konteks saat mengklik di luar menu
            $(document).on("click", function(e) {
                if (!$(e.target).closest("table tbody tr").length) {
                    $(".dropdown-menu").hide();
                }
            });
        });
    </script>
@endsection
