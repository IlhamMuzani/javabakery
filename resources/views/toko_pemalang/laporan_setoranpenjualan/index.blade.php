@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

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
                    <h1 class="m-0">Laporan Setoran Penjualan</h1>
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

       
                    <h3 class="card-title">Laporan Setoran Penjualan</h3>
                </div>

                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                    value="{{ Request::get('tanggal_penjualan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_penjualan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="kasir" name="kasir">
                                    <option value="">- Semua Kasir -</option>
                                    @foreach ($kasirs as $kasir)
                                        <option value="{{ $kasir->kasir }}" {{ Request::get('kasir') == $kasir->kasir ? 'selected' : '' }}>{{ $kasir->kasir }}</option>
                                    @endforeach
                                </select>
                                <label for="kasir">(Pilih Kasir)</label>
                            </div>
                            
                            
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
                    
                   
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 10px">
                        <thead class="">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Penjualan Kotor</th>
                                <th>Diskon Penjualan</th> <!-- Kolom diskon -->
                                <th>Penjualan Bersih</th>
                                <th>Deposit Keluar</th>
                                <th>Deposit Masuk</th>
                                <th>Total Penjualan</th>
                                <th>Mesin EDC</th>
                                <th>Gobiz</th>
                                <th>Transfer</th>
                                <th>Voucher</th>
                                <th>Qris</th>
                                <th>Total Setoran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>{{ number_format($penjualan_kotor, 0, ',', '.') }}</td>
                                <td>{{ number_format($diskon_penjualan, 0, ',', '.') }}</td>
                                <td>{{ number_format($penjualan_bersih, 0, ',', '.') }}</td>
                                <td>{{ number_format($deposit_keluar, 0, ',', '.') }}</td> <!-- Deposit Masuk -->
                                <td>{{ number_format($deposit_masuk, 0, ',', '.') }}</td> <!-- Deposit Masuk -->
                                <td>{{ number_format($total_penjualan, 0, ',', '.') }}</td>
                                <td>{{ $mesin_edc ? number_format($mesin_edc, 0, ',', '.') : '0' }}</td>
                                <td>{{ $gobiz ? number_format($gobiz, 0, ',', '.') : '0' }}</td>
                                <td>{{ $transfer ? number_format($transfer, 0, ',', '.') : '0' }}</td>
                                <td>0</td>
                                <td>{{ $qris ? number_format($qris, 0, ',', '.') : '0' }}</td>
                                <td>{{ number_format($total_setoran, 0, ',', '.') }}</td>
                            </tr>
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
        var tanggalAwal = document.getElementById('tanggal_penjualan');
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
            form.action = "{{ url('toko_pemalang/laporan_setorantokopemalang') }}";
            form.submit();
        }
    </script>

<script>
    function printReport() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_pemalang/printReportsetoranpml') }}";
    form.target = "_blank";
    form.submit();
}

</script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'global') {
            window.location.href = "{{ url('toko_pemalang/indexglobal') }}";
        } else if (selectedValue === 'rinci') {
            window.location.href = "{{ url('toko_pemalang/laporan_penjualanproduk') }}";
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
@endsection
