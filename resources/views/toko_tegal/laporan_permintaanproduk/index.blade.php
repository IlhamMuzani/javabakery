@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .permintaan-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .permintaan-header:hover {
            background-color: #f0f0f0;
        }
        .permintaan-header.active {
            background-color: #e0e0e0;
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
                    <h1 class="m-0">Laporan Permintaan Produk - Global</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item active">Laporan penjualan Produk</li> --}}
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
                <div class="card-header">
                        <div class="float-right">
                            <select class="form-control" id="kategori1" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="global" {{ old('kategori1') == 'global' ? 'selected' : '' }}>Laporan Permintaan Global</option>
                                <option value="rinci" {{ old('kategori1') == 'rinci' ? 'selected' : '' }}>Laporan Permintaan Rinci</option>
                            </select>
                        </div>
                        {{-- <div class="float-right">
                            <a href="{{ route('toko_slawi.penjualan_produk.pelunasan') }}"  class="btn btn-primary btn-sm">Pelunasan Pemesanan
                            </a>
                        </div> --}}
       
                    <h3 class="card-title">Laporan Permintaan Produk</h3>
                </div>

                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_permintaan" name="tanggal_permintaan" type="date"
                                    value="{{ Request::get('tanggal_permintaan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_permintaan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi" name="klasifikasi_id" onchange="filterProduk()">
                                    <option value="">- Semua Divisi -</option>
                                    @foreach ($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>{{ $klasifikasi->nama }}</option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi">(Pilih Divisi)</label>
                            </div>
                            {{-- <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="produk" name="produk">
                                    <option value="">- Semua Produk -</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" data-klasifikasi="{{ $produk->klasifikasi_id }}" {{ Request::get('produk') == $produk->id ? 'selected' : '' }}>{{ $produk->nama_produk }}</option>
                                    @endforeach
                                </select>
                                <label for="produk">(Pilih Produk)</label>
                            </div> --}}
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                
                    {{-- <table id="datatables66" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Cabang</th>
                                <th>Kode Permintaan</th>
                                <th>Tanggal Permintaan</th>
                                <th>Jumlah Produk</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $permintaan)
                                <tr class="permintaan-header" data-permintaan-id="{{ $permintaan->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ optional($permintaan->detailpermintaanproduks->first()->toko)->nama_toko ?? 'Toko tidak ditemukan' }}</td>
                                    <td>{{ $permintaan->kode_permintaan }}</td>
                                    <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $permintaan->detailpermintaanproduks->count() }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('toko_slawi/permintaan_produk/' . $permintaan->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-print"></i>
                                        </a>
                                      
                                    </td>
                                    
                                </tr>
                                <tr class="permintaan-details" id="details-{{ $permintaan->id }}" style="display: none;">
                                    <td colspan="5">
                                        <table class="table table-bordered" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Divisi</th>
                                                    <th>Kode Produk</th>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permintaan->detailpermintaanproduks as $detail)
                                                <tr >
                                                    <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $detail->produk->klasifikasi->nama }}</td>
                                                        <td>{{ $detail->produk->kode_produk }}</td>
                                                        <td>{{ $detail->produk->nama_produk }}</td>
                                                        <td>{{ $detail->jumlah }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
<table id="datatables66" class="table table-bordered" style="font-size: 13px">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Cabang</th>
            <th>Kode Permintaan</th>
            <th>Tanggal Permintaan</th>
            <th>Jumlah Produk</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inquery as $permintaan)
            @php
                $filteredDetails = $permintaan->detailpermintaanproduks->filter(function ($detail) use ($klasifikasi_id) {
                    return !$klasifikasi_id || $detail->produk->klasifikasi_id == $klasifikasi_id;
                });
            @endphp
            @if ($filteredDetails->isNotEmpty())
                <tr class="permintaan-header" data-permintaan-id="{{ $permintaan->id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ optional($permintaan->detailpermintaanproduks->first()->toko)->nama_toko ?? 'Toko tidak ditemukan' }}</td>
                    <td>{{ $permintaan->kode_permintaan }}</td>
                    <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                    <td>{{ $filteredDetails->count() }}</td>
                   
                </tr>
                <tr class="permintaan-details" id="details-{{ $permintaan->id }}" style="display: none;">
                    <td colspan="6">
                        <table class="table table-bordered" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Divisi</th>
                                    <th>Kode Produk</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filteredDetails as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->produk->klasifikasi->nama }}</td>
                                        <td>{{ $detail->produk->kode_produk }}</td>
                                        <td>{{ $detail->produk->nama_produk }}</td>
                                        <td>{{ $detail->jumlah }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

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

    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_permintaan');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            };
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });
        var form = document.getElementById('form-action')

        function cari() {
            form.action = "{{ url('toko_slawi/laporan_penjualanproduk') }}";
            form.submit();
        }
    </script>

<script>
    function printReport() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_slawi/printReport1') }}";
    form.target = "_blank";
    form.submit();
}

</script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'global') {
            window.location.href = "{{ url('toko_slawi/laporan_permintaanproduk') }}";
        } else if (selectedValue === 'rinci') {
            window.location.href = "{{ url('toko_slawi/indexrinci') }}";
        }
    });
</script>

<script>
    function filterProduk() {
        var klasifikasiId = document.getElementById('klasifikasi').value;
        var produkSelect = document.getElementById('produk');
        var produkOptions = produkSelect.options;
    
        for (var i = 0; i < produkOptions.length; i++) {
            var option = produkOptions[i];
            if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        }
    
        // Reset the selected value of the product select box
        produkSelect.selectedIndex = 0;
    }
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
 // Handle click event for permintaan-header
 const permintaanHeaders = document.querySelectorAll('.permintaan-header');
 
 permintaanHeaders.forEach(header => {
     header.addEventListener('click', function() {
         const permintaanId = header.dataset.permintaanId;
         const detailsRow = document.getElementById(`details-${permintaanId}`);
         
         // Hide all details rows and remove active class from all headers
         const allDetailsRows = document.querySelectorAll('.permintaan-details');
         const allHeaders = document.querySelectorAll('.permintaan-header');
         
         // Check if the clicked row is already open
         const isActive = header.classList.contains('active');

         allDetailsRows.forEach(row => row.style.display = 'none');
         allHeaders.forEach(h => h.classList.remove('active'));
         
         // Toggle the clicked row only if it wasn't already active
         if (!isActive) {
             detailsRow.style.display = '';
             header.classList.add('active');
         }
     });
 });

 // Handle click event for show-btn
 document.querySelectorAll('.show-btn').forEach(button => {
     button.addEventListener('click', function() {
         const permintaanId = this.dataset.permintaanId;
         const href = this.dataset.href;

         // Redirect to the specified URL
         window.location.href = href;
     });
 });
});

 </script>
@endsection
