@extends('layouts.app')

@section('title', 'Produks')

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
                    <h1 class="m-0">Laporan Penjualan Toko</h1>
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
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_setoran" name="tanggal_setoran" type="date"
                                    value="{{ Request::get('tanggal_setoran') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_setoran">(Dari Tanggal)</label>
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
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReportpenjualan()" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                    
                   
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 10px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Setoran</th> 
                                <th>Cabang</th> 
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
                                <th>Noiminal Setoran</th>
                                <th>Plus Minus</th>
                                <th class="text-center" width="20">Opsi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($setoranPenjualans as $index => $item)
                            <tr class="dropdown"{{ $item->id }}>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->tanggal_setoran ? \Carbon\Carbon::parse($item->tanggal_setoran)->format('d-m-Y') : '-' }}</td> <!-- Menampilkan Tanggal item -->
                                <td>{{ $item->toko->nama_toko }}</td>
                                <td>{{ number_format($item->penjualan_kotor, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->diskon_penjualan, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->penjualan_bersih, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->deposit_keluar, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->deposit_masuk, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->mesin_edc ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->gobiz ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->transfer ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->qris ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->total_setoran, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->nominal_setoran, 0, ',', '.') }}</td>
                                <td>{{ number_format($item->plusminus, 0, ',', '.') }}</td>
                        
                                    <td class="text-center">
                                        @if ($item->status == 'posting')
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if ($item->status == 'unpost')
                                        <button type="button" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        @if ($item->status == 'approve')
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                     
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($item->status == 'unpost')
                                            <a class="dropdown-item posting-btn" data-memo-id="{{ $item->id }}">Posting</a>
                                                <a class="dropdown-item" href="{{ route('inquery_penjualantoko.print', $item->id) }}" target="_blank">Print</a>  
                                            @endif

                                            @if ($item->status == 'posting')
                                                <a class="dropdown-item unpost-btn" data-memo-id="{{ $item->id }}">Unpost</a>
                                                <a class="dropdown-item" href="{{ route('inquery_penjualantoko.print', $item->id) }}" target="_blank">Print</a>
                                            @endif
                                           
                                        </div>
                                    </td>
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
        var tanggalAwal = document.getElementById('tanggal_setoran');
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
            form.action = "{{ url('admin/laporan_penjualantoko') }}";
            form.submit();
        }
    </script>

<script>
    function printReportpenjualan() {
        var tanggalAwal = document.getElementById('tanggal_setoran').value;
        var tanggalAkhir = document.getElementById('tanggal_akhir').value;

        if (tanggalAwal === "" || tanggalAkhir === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal Belum Dipilih!',
                text: 'Silakan isi tanggal terlebih dahulu.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                background: '#fff',
                customClass: {
                    popup: 'animated bounceIn'
                }
            });
            return;
        }

            const form = document.getElementById('form-action');
            form.action = "{{ url('admin/printReportpenjualanToko') }}";
            form.target = "_blank";
            form.submit();
        }

</script>

@endsection
