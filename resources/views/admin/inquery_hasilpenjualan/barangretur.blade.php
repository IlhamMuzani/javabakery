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
                    <h1 class="m-0">Inquery Hasil Penjualan BR</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Hasil Penjualan BR</li>
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
                        </select>
                    </div>
                    <h3 class="card-title">Inquery Hasil Penjualan</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                        <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="status" name="status">
                                <option value="">- Semua Status -</option>
                                <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>Posting</option>
                                <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>Unpost</option>
                            </select>
                            <label for="status">(Pilih Status)</label>
                        </div>
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
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                
                            </div>
                        </div>
                    </form>

                   
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead class="">
                            <tr>
                                {{-- <th> <input type="checkbox" name="" id="select_all_ids"></th> --}}
                                <th class="text-center">No</th>
                                <th>Kode penjualan</th>
                                <th>Tanggal penjualan</th>
                                <th>Kasir</th>
                                <th>Pelanggan</th>
                          
                                <th>Total</th>
                                <th class="text-center" width="20">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $item)
                                <tr class="dropdown"{{ $item->id }}>
                                   
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->kode_penjualan }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        {{ $item->kasir }}
                                    </td>
                                    <td>
                                        @if ($item->kode_pelanggan && $item->nama_pelanggan)
                                            {{ $item->kode_pelanggan }} / {{ $item->nama_pelanggan }}
                                        @else
                                            Non Member
                                        @endif
                                    </td>
                                  

                                    <td>
                                        {{ number_format($item->sub_total, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if ($item->status == 'posting')
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if ($item->status == 'unpost')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm mt-2">
                                                <i class="fas fa-times"></i> 
                                            </button>
                                        @endif
                                     
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($item->status == 'unpost')
                                               
                                                    <a class="dropdown-item posting-btn"
                                                        data-memo-id="{{ $item->id }}">Posting</a>
                                             
                                                    <a class="dropdown-item"
                                                        href="{{ url('admin/inquery_penjualanproduk/' . $item->id . '/edit') }}">Update</a>
                                                
                                                    <a class="dropdown-item"
                                                    href="{{ url('/admin/penjualan_produk/' . $item->id ) }}">Show</a>
                                                    @endif
                                            @if ($item->status == 'posting')
                                                    <a class="dropdown-item unpost-btn"
                                                        data-memo-id="{{ $item->id }}">Unpost</a>
                                                    <a class="dropdown-item"
                                                    href="{{ url('/admin/penjualan_produk/' . $item->id ) }}">Show</a>
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
            form.action = "{{ url('admin/inquery_hasilpenjualan') }}";
            form.submit();
        }

    </script>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'masuk') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan') }}";
        } else if (selectedValue === 'keluar') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan/barangkeluar') }}";
        }else if (selectedValue === 'retur') {
            window.location.href = "{{ url('admin/inquery_hasilpenjualan/barangretur') }}";
        }
    });
</script>



@endsection
