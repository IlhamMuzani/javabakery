@extends('layouts.app')

@section('title', 'Data Stok Toko')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Stok Toko </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Stok </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
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
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="stok" {{ old('kategori1') == 'stok' ? 'selected' : '' }}>Data Stok</option>
                            <option value="stokpesanan" {{ old('kategori1') == 'stokpesanan' ? 'selected' : '' }}>Data Stok Pesanan</option>
                            <option value="semuastok" {{ old('kategori1') == 'semuastok' ? 'selected' : '' }}>Data Semua Stok</option>
                        </select>
                    </div>
                    <h3 class="card-title">Laporan Stok Toko</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                   

                    <form method="GET" id="form-action">
                        <div class="row">
                            <!-- Filter Toko -->
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id">
                                    {{-- <option value="">- Semua Toko -</option> --}}
                                    <option value="3" {{ Request::get('toko_id') == '3' ? 'selected' : '' }}>Toko Slawi</option>
                                    {{-- <option value="2" {{ Request::get('toko_id') == '2' ? 'selected' : '' }}>Toko Tegal</option>
                                    <option value="3" {{ Request::get('toko_id') == '3' ? 'selected' : '' }}>Toko Slawi</option>
                                    <option value="4" {{ Request::get('toko_id') == '4' ? 'selected' : '' }}>Toko Pemalang</option>
                                    <option value="5" {{ Request::get('toko_id') == '5' ? 'selected' : '' }}>Toko Bumiayu</option>
                                    <option value="6" {{ Request::get('toko_id') == '6' ? 'selected' : '' }}>Toko Cilacap</option> --}}
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                    
                            <!-- Filter Divisi -->
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="klasifikasi" name="klasifikasi_id" onchange="filterSubKlasifikasi()">
                                    <option value="">- Semua Divisi -</option>
                                    @foreach ($klasifikasis as $klasifikasi)
                                        <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                            {{ $klasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="klasifikasi">(Pilih Divisi)</label>
                            </div>
                    
                            <!-- Filter Sub Divisi -->
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="subklasifikasi" name="subklasifikasi_id">
                                    <option value="">- Semua Sub Klasifikasi -</option>
                                    @foreach ($subklasifikasis as $subklasifikasi)
                                        <option value="{{ $subklasifikasi->id }}" data-klasifikasi="{{ $subklasifikasi->klasifikasi_id }}" {{ Request::get('subklasifikasi_id') == $subklasifikasi->id ? 'selected' : '' }}>
                                            {{ $subklasifikasi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="subklasifikasi">(Pilih Sub Klasifikasi)</label>
                            </div>
                           
                            <!-- Tombol Cari -->
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport(event)">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                <button type="button" class="btn btn-success btn-block" onclick="printExcel(event)">
                                    <i class="fas fa-print"></i> Ekspor Excel
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    
                
                    <table id="datatables1" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkWithStok as $produk)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->kode_lama }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td style="text-align: right">{{ $produk->jumlah }}</td>
                                    <td style="text-align: right">{{ number_format($produk->harga, 0, ',', '.') }} </td>
                                    <td style="text-align: right">{{ number_format($produk->subTotal, 0, ',', '.') }} </td> <!-- Sub Total -->
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center">Total</th>
                                <th style="text-align: right">{{ $totalStok }}</th>
                                <th></th>
                                <th style="text-align: right">{{ 'Rp. ' . number_format($totalSubTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    
<script>
    function printReport() {
        if (event) event.preventDefault();
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_slawi/printstoktokoslawi') }}";
    form.target = "_blank";
    form.submit();
}
</script>


<script>
    function printExcel() {
        if (event) event.preventDefault();
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_slawi/printexcelstoktokoslawi') }}";
    form.target = "_blank";
    form.submit();
}
</script>

<script>
    function filterSubKlasifikasi() {
var klasifikasiId = document.getElementById('klasifikasi').value;
var subKlasifikasiSelect = document.getElementById('subklasifikasi');
var subKlasifikasiOptions = subKlasifikasiSelect.options;

// Show all options initially
for (var i = 0; i < subKlasifikasiOptions.length; i++) {
    var option = subKlasifikasiOptions[i];
    if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
        option.style.display = "block";
    } else {
        option.style.display = "none";
    }
}

// Don't automatically select sub classification, let the user decide
subKlasifikasiSelect.selectedIndex = 0;
}

</script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'stok') {
            window.location.href = "{{ url('toko_slawi/laporan_stoktokoslawi') }}";
        } else if (selectedValue === 'stokpesanan') {
            window.location.href = "{{ url('toko_slawi/stoktokopesananslawi') }}";
        }else if (selectedValue === 'semuastok') {
            window.location.href = "{{ url('toko_slawi/semuastoktokoslawi') }}";
        }
    });
</script>       
@endsection
