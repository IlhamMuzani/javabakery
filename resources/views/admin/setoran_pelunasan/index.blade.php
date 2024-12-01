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
                    <h1 class="m-0">Pelunasan Penjualan</h1>
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
                        <a href="{{ url('admin/setoran_pelunasan/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>

                    {{-- <h3 class="card-title">Laporan Setoran Pelunasan</h3> --}}
                </div>

                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 10px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Setoran</th> <!-- Tambahkan kolom tanggal setoran -->
                                <th>Penjualan Kotor</th>
                                <th>Diskon Penjualan</th>
                                <th>Penjualan Bersih</th>
                                <th>Deposit Keluar</th>
                                <th>Deposit Masuk</th>
                                <th>Total Penjualan</th>
                                <th>Mesin EDC</th>
                                <th>Gobiz</th>
                                <th>Transfer</th>
                                <th>Qris</th>
                                <th>Total Setoran</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($setoranPenjualans as $index => $setoran)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $setoran->tanggal_setoran ? \Carbon\Carbon::parse($setoran->tanggal_setoran)->format('d-m-Y') : '-' }}
                                    </td> <!-- Menampilkan Tanggal Setoran -->
                                    <td>{{ $setoran->penjualan_kotor1 }}</td>
                                    <td>{{ $setoran->diskon_penjualan1 }}</td>
                                    <td>{{ $setoran->penjualan_bersih1 }}</td>
                                    <td>{{ $setoran->deposit_keluar1 }}</td>
                                    <td>{{ $setoran->deposit_masuk1 }}</td>
                                    <td>{{ $setoran->total_penjualan1 }}</td>
                                    <td>{{ $setoran->mesin_edc1 ?? '0' }}</td>
                                    <td>{{ $setoran->gobiz1 ?? '0' }}</td>
                                    <td>{{ $setoran->transfer1 ?? '0' }}</td>
                                    <td>{{ $setoran->qris1 ?? '0' }}</td>
                                    <td>{{ $setoran->total_setoran1 }}</td>

                                </tr>
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
            form.action = "{{ url('admin/laporan_setorantokobanjaran') }}";
            form.submit();
        }
    </script>

    <script>
        function printReport() {
            const form = document.getElementById('form-action');
            form.action = "{{ url('admin/printReportsetoran') }}";
            form.target = "_blank";
            form.submit();
        }
    </script>

    <script>
        document.getElementById('kategori1').addEventListener('change', function() {
            var selectedValue = this.value;

            if (selectedValue === 'global') {
                window.location.href = "{{ url('admin/indexglobal') }}";
            } else if (selectedValue === 'rinci') {
                window.location.href = "{{ url('admin/laporan_penjualanproduk') }}";
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
