@extends('layouts.app')

@section('title', 'Laporan BR')

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
                            <option value="masuk" {{ old('kategori1') == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="keluar" {{ old('kategori1') == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
                            <option value="retur" {{ old('kategori1') == 'retur' ? 'selected' : '' }}>Barang Retur</option>
                            <option value="oper" {{ old('kategori1') == 'oper' ? 'selected' : '' }}>Barang Oper</option>

                        </select>
                    </div>
                    <h3 class="card-title">Laporan Barang Operan (Masuk)</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                         
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="kategori2" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="masuk" {{ old('kategori2') == 'masuk' ? 'selected' : '' }}>OPERAN MASUK</option>
                                    <option value="keluar" {{ old('kategori2') == 'keluar' ? 'selected' : '' }}>OPERAN KELUAR</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi_id" name="klasifikasi_id">
                                    <option value="">- Semua Klasifikasi -</option>
                                    <!-- Populate klasifikasi options dynamically -->
                                    @foreach($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi_id">(Pilih Klasifikasi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_input" name="tanggal_input" type="date"
                                    value="{{ Request::get('tanggal_input') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_input">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                        </div>
                    
                    </form>
                    
                    <form id="searchForm" method="GET">
                        <!-- Form fields go here -->
                    
                        <div class="col-md-3 mb-3">
                            <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                            <button type="button" class="btn btn-success btn-block" onclick="exportExcelBR()">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                    </form>
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Oper</th>
                                <th>No. Faktur Oper</th>
                                <th>Dari Toko</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($stokBarangJadi as $returGroup)
                                @foreach($returGroup as $retur)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($retur['tanggal_input'])->format('d/m/Y H:i') }}</td>
                                    <td>{{ $retur->kode_pemindahan }}</td>
                                    <td>{{ $retur->toko->nama_toko }}</td>
                                    <td>{{ $retur->produk->nama_produk }}</td>
                                    <td style="text-align: right">{{ number_format($retur->jumlah, 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($retur->produk->harga, 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($retur->jumlah * $retur->produk->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-center">Total</th>
                                <th style="text-align: right">{{ number_format($totalJumlah, 0, ',', '.') }}</th>
                                <th></th>
                                <th style="text-align: right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
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
        var tanggalAwal = document.getElementById('tanggal_input');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
    
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
                var today = new Date().toISOString().split('T')[0];
                tanggalAkhir.value = today;
                tanggalAkhir.setAttribute('min', this.value);
            }
        });
    
        function cari() {
            var form = document.getElementById('form-action');
            form.action = "{{ route('barangOperantegalMasuk') }}";
            form.submit();  
        }
    </script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'masuk') {
            window.location.href = "{{ url('toko_tegal/laporan_historitegal') }}";
        } else if (selectedValue === 'keluar') {
            window.location.href = "{{ url('toko_tegal/barangKeluartegal') }}";
        }else if (selectedValue === 'retur') {
            window.location.href = "{{ url('toko_tegal/barangReturtegal') }}";
        }else if (selectedValue === 'oper') {
            window.location.href = "{{ url('toko_tegal/barangOpertegal') }}";
        }
    });
</script>

<script>
    document.getElementById('kategori2').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'masuk') {
            window.location.href = "{{ route('barangOperantegalMasuk') }}";
        } else if (selectedValue === 'keluar') {
            window.location.href = "{{ route('barangOpertegal') }}"; 
        }
    });
</script>

<script>
    function printReport() {
        var tanggalAwal = document.getElementById('tanggal_input').value;
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
        form.action = "{{ url('toko_tegal/printLaporanBOtegalMasuk') }}";
        form.target = "_blank";
        form.submit();
    }
</script>


<script>
    function exportExcelBR() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_tegal/printExcelBotegal') }}";
    form.target = "_blank";
    form.submit();
}
</script>

@endsection
