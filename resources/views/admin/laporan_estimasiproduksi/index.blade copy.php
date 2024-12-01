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
                    <h1 class="m-0">Laporan Estimasi Produksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Estimasi Produksi</li>
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
                    <h3 class="card-title">Estimasi Produksi</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <!-- Tanggal Awal -->
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_awal" name="tanggal_awal" type="date"
                                    value="{{ Request::get('tanggal_awal') }}" />
                                <label for="tanggal_awal">(Dari Tanggal)</label>
                            </div>
                            
                            <!-- Tanggal Akhir -->
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            
                            <!-- Tabel Type -->
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="table_type" name="table_type" onchange="updateAction()">
                                    <option value="all" {{ Request::get('table_type') == 'all' ? 'selected' : '' }}>All Data</option>
                                    <option value="permintaan" {{ Request::get('table_type') == 'permintaan' ? 'selected' : '' }}>Atas Permintaan</option>
                                    <option value="pemesanan" {{ Request::get('table_type') == 'pemesanan' ? 'selected' : '' }}>Atas Pesanan</option>
                                </select>
                                <label for="table_type">(Pilih Tabel)</label>
                            </div>

                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko">
                                    <option value="">- Semua Toko -</option>
                                    @foreach($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko') == $toko->id ? 'selected' : '' }}>
                                            {{ $toko->nama_toko }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            
                            <!-- Klasifikasi Filter -->
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi" name="klasifikasi">
                                    <option value="">- Semua Klasifikasi -</option>
                                    @foreach($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi">(Pilih Klasifikasi)</label>
                            </div>
                    
                            <!-- Submit Buttons -->
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

                    <div class="card-body">
                        @if($tableType == '' || $tableType == 'permintaan' || $tableType == 'all')
                            <h4>Atas Permintaan</h4>
                            <table id="datatables67" class="table table-bordered" style="font-size: 13px">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Divisi</th>
                                        <th>Produk</th>
                                        <th>Kode Produk</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($permintaanProduks as $produkId => $tokoDetails)
                                        @php
                                            $firstDetail = $tokoDetails->first();
                                            $produk = $firstDetail['produk'] ?? null;
                                            $totalJumlah = $tokoDetails->sum('jumlah');
                                        @endphp
                                        <tr class="dropdown-permintaan" data-permintaan-id="{{ $produkId }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $produk->klasifikasi->nama ?? 'Klasifikasi Tidak Ditemukan' }}</td>
                                            <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                                            <td>{{ $produk->kode_lama ?? 'N/A' }}</td>
                                            <td>{{ $totalJumlah }}</td>
                                        </tr>
                                        <tr class="permintaan-details" id="details-permintaan-{{ $produkId }}" style="display: none;">
                                            <td colspan="5">
                                                <table class="table table-bordered" style="font-size: 13px;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode Permintaan</th>
                                                            <th>Cabang</th>
                                                            <th>Jumlah</th>
                                                            <th>Tanggal Permintaan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tokoDetails as $tokoDetail)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $firstDetail['kode_permintaan'] ?? 'Kode permintaan Tidak Ditemukan' }}</td>
                                                                <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                                                <td>{{ $tokoDetail['jumlah'] }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_permintaan'])->format('d-m-Y H:i') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                        <!-- Repeat similar block for pemesanan if applicable -->
                    </div>
                    
                    

                <!-- Tabel Pemesanan -->
              <!-- Tabel Pemesanan -->
<div class="card-body">
    @if($tableType == '' || $tableType == 'pemesanan' || $tableType == 'all')
    <h4>Atas Pemesanan</h4>
    <table id="data" class="table table-bordered" style="font-size: 13px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Produk</th>
                <th>Kode Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $firstDetail = $tokoDetails->first();
                    $produk = $firstDetail['produk'] ?? null;
                    $totalJumlah = $tokoDetails->sum('jumlah');
                @endphp
                <tr class="dropdown-pemesanan" data-pemesanan-id="{{ $produkId }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $produk->kode_produk ?? 'N/A' }}</td>
                    <td>{{ $totalJumlah }}</td>
                </tr>
                <tr class="pemesanan-details" id="details-pemesanan-{{ $produkId }}" style="display: none;">
                    <td colspan="4">
                        <table class="table table-bordered" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokoDetails as $tokoDetail)
                                    @foreach ($tokoDetail['detail'] as $detail)
                                        <tr>
                                            <td>{{ $loop->parent->iteration }}</td>
                                            <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                            <td>{{ $detail->pemesananProduk->kode_pemesanan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_kirim'])->format('d-m-Y H:i') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>

       
            </div>
        </div>
    </section>

<script>
        document.getElementById('tanggal_awal').addEventListener('change', function() {
        let tanggalAwal = this.value;
        let tanggalAkhir = document.getElementById('tanggal_akhir');
        
        if (!tanggalAwal) {
            tanggalAkhir.readOnly = true;
        } else {
            tanggalAkhir.readOnly = false;
            tanggalAkhir.value = new Date().toISOString().split('T')[0];
            tanggalAkhir.setAttribute('min', tanggalAwal);
        }
    });

    document.getElementById('form-action').addEventListener('submit', function() {
        this.action = "{{ url('admin/laporan_estimasiproduksi') }}";
    });

    </script>

{{-- <script>
    function printReport() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('admin/printReport') }}";
    form.target = "_blank";
    form.submit();
}

</script> --}}
<script>
    function updateAction() {
        const tableType = document.getElementById('table_type').value;
        let actionUrl = '';

        switch (tableType) {
            case 'permintaan':
                actionUrl = "{{ url('admin/printReportPermintaan') }}";
                break;
            case 'pemesanan':
                actionUrl = "{{ url('admin/printReportPemesanan') }}";
                break;
            case 'all':
                actionUrl = "{{ url('admin/printReportAll') }}";
                break;
        }

        document.getElementById('form-action').action = actionUrl;
    }

    function printReport() {
        const form = document.getElementById('form-action');
        form.target = "_blank";
        form.submit();
    }
</script>
   

<script>
    $(document).ready(function() {
        // Handle Permintaan Table
        $('tbody tr.dropdown-permintaan').click(function(e) {
            if ($(e.target).is('input[type="checkbox"]')) {
                return;
            }

            $('tbody tr.dropdown-permintaan').not(this).removeClass('selected').css('background-color', '');
            $('.permintaan-details').not('#details-permintaan-' + $(this).data('permintaan-id')).hide();

            var detailRowId = $(this).data('permintaan-id');
            var detailRow = $('#details-permintaan-' + detailRowId);
            var isActive = detailRow.is(':visible');

            $('tr.dropdown-permintaan').removeClass('selected').css('background-color', '');
            
            if (isActive) {
                detailRow.hide();
            } else {
                $(this).addClass('selected').css('background-color', '#b0b0b0');
                detailRow.show();
            }

            $('tbody tr.dropdown-permintaan').not(this).find('.dropdown-menu').hide();
            e.stopPropagation();
        });

        // Handle Pemesanan Table
        $('tbody tr.dropdown-pemesanan').click(function(e) {
            if ($(e.target).is('input[type="checkbox"]')) {
                return;
            }

            $('tbody tr.dropdown-pemesanan').not(this).removeClass('selected').css('background-color', '');
            $('.pemesanan-details').not('#details-pemesanan-' + $(this).data('pemesanan-id')).hide();

            var detailRowId = $(this).data('pemesanan-id');
            var detailRow = $('#details-pemesanan-' + detailRowId);
            var isActive = detailRow.is(':visible');

            $('tr.dropdown-pemesanan').removeClass('selected').css('background-color', '');
            
            if (isActive) {
                detailRow.hide();
            } else {
                $(this).addClass('selected').css('background-color', '#b0b0b0');
                detailRow.show();
            }

            $('tbody tr.dropdown-pemesanan').not(this).find('.dropdown-menu').hide();
            e.stopPropagation();
        });

        // Hide dropdowns when clicking outside
        $(document).click(function() {
            $('.dropdown-menu').hide();
            $('tr.dropdown-permintaan, tr.dropdown-pemesanan').removeClass('selected').css('background-color', '');
        });
    });
</script>

@endsection
