@extends('layouts.app')

@section('title', 'Laporan BM')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

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
                    <h1 class="m-0">Laporan Barang Masuk (Semua)</h1>
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
                    <h3 class="card-title">Laporan Barang Masuk (Semua)</h3>
                </div>
                
                <div class="card-body">
                    <!-- Tabel -->
                    <form method="GET" id="form-action">

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="form-control" id="kategori2" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="permintaan" {{ old('kategori2') == 'permintaan' ? 'selected' : '' }}>BM STOK</option>
                                    <option value="pemesanan" {{ old('kategori2') == 'pemesanan' ? 'selected' : '' }}>BM PEMESANAN</option>
                                    <option value="semua" {{ old('kategori2') == 'semua' ? 'selected' : '' }}>SEMUA BM</option>
                                </select>
                            </div>
                            <div hidden class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko_id" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko_id">(Pilih Toko)</label>
                            </div>
                              
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" type="date"
                                    value="{{ Request::get('tanggal_pengiriman') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_pengiriman">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>

                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi_id" name="klasifikasi_id">
                                    <option value="">- Semua Klasifikasi -</option>
                                    @foreach($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi_id">(Pilih Klasifikasi)</label>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" name="produk_id"
                                    data-placeholder="Pilih Produk" style="width: 100%;" data-select2-id="23"
                                    tabindex="-1" aria-hidden="true" id="produk_id">
                                    <option value="">- Semua Produk -</option> <!-- Opsi untuk semua produk -->
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" data-klasifikasi="{{ $produk->klasifikasi_id }}" {{ Request::get('produk_id') == $produk->id ? 'selected' : '' }}>
                                            {{ $produk->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                                <label style="margin-top:7px" for="produk_id">Produk</label>
                            </div>

                            <div class="col-md-3 mb-3" >
                                <input class="form-control" style="border: none; background-color: transparent; color: white;"/>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input  class="form-control" style="border: none; background-color: transparent; color: white;"/>
                            </div>
                          
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                <button type="button" class="btn btn-success btn-block" onclick="exportExcelBMsemua()">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </form>
                

                    <table id="datatables66" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Kode Produk</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalJumlah = 0;
                                $grandTotal = 0;
                            @endphp
                            @foreach ($stokBarangJadi as $index => $item)
                            @php
                                $totalJumlah += $item->jumlah;
                                $totalHarga = $item->jumlah * $item->produk->harga;
                                $grandTotal += $totalHarga;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->produk->kode_lama }}</td>
                                <td>{{ $item->produk->nama_produk }}</td>
                                <td style="text-align: right">{{ $item->jumlah }}</td>
                                <td style="text-align: right">{{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                                <td style="text-align: right">{{ number_format($totalHarga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-center">Total</th>
                                <th style="text-align: right">{{ $totalJumlah }}</th>
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

    <script>
        var tanggalAwal = document.getElementById('tanggal_pengiriman');
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
            form.action = "{{ route('barangMasuksemuacilacap') }}";
            form.submit();
        }

    </script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'masuk') {
            window.location.href = "{{ url('toko_cilacap/laporan_historicilacap') }}";
        } else if (selectedValue === 'keluar') {
            window.location.href = "{{ url('toko_cilacap/barangKeluarcilacap') }}";
        }else if (selectedValue === 'retur') {
            window.location.href = "{{ url('toko_cilacap/barangReturcilacap') }}";
        }else if (selectedValue === 'oper') {
            window.location.href = "{{ url('toko_cilacap/barangOpercilacap') }}";
        }
    });
</script>

<script>
    function printReport() {
        var tanggalAwal = document.getElementById('tanggal_pengiriman').value;
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
        form.action = "{{ url('toko_cilacap/printLaporanBmsemuacilacap') }}";
        form.target = "_blank";
        form.submit();
    }
</script>
<script>
    document.getElementById('kategori2').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'permintaan') {
            window.location.href = "{{ url('toko_cilacap/laporan_historicilacap') }}";
        } else if (selectedValue === 'pemesanan') {
            window.location.href = "{{ route('barangMasukpesanancilacap') }}"; 
        }else if (selectedValue === 'semua') {
            window.location.href = "{{ route('barangMasuksemuacilacap') }}"; 
        }
    });
</script>


<script>
    function exportExcelBMsemua() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_cilacap/printExcelBmsemuacilacap') }}";
    form.target = "_blank";
    form.submit();
}
</script>

@endsection

