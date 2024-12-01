@extends('layouts.app')

@section('title', 'Laporan Pememsanan Produk')

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
            }, 100); // Adjust the delay time as needed
        });
    </script>

    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Pemesanan Produk</li>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Laporan Pemesanan Produk</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form id="form-action" method="GET" action="{{ url('admin/laporan_pemesananproduk/print') }}">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko_id" name="toko_id">
                                    <option value="">- Pilih -</option>
                                    <option value="0" {{ $toko_id == '0' ? 'selected' : '' }}>Global</option>
                                    <option value="1" {{ $toko_id == '1' ? 'selected' : '' }}>Banjaran</option>
                                    <option value="2" {{ $toko_id == '2' ? 'selected' : '' }}>Tegal</option>
                                    <option value="3" {{ $toko_id == '3' ? 'selected' : '' }}>Slawi</option>
                                    <option value="4" {{ $toko_id == '4' ? 'selected' : '' }}>Pemalang</option>
                                    <option value="5" {{ $toko_id == '5' ? 'selected' : '' }}>Bumiayu</option>
                                    <option value="5" {{ $toko_id == '6' ? 'selected' : '' }}>Cilacap</option>
                                </select>
                                <label for="toko_id">(Pilih Toko)</label>
                            </div>  
                            
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_pemesanan" name="tanggal_pemesanan" type="date"
                                    value="{{ Request::get('tanggal_pemesanan') }}"  />
                                <label for="tanggal_pemesanan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
              
                    {{-- Global --}}
                    @if($toko_id == '0')
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Divisi</th>
                                <th>Tanggal Kirim/Ambil</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Banjaran</th>
                                <th>Tegal</th>
                                <th>Slawi</th>
                                <th>Pemalang</th>
                                <th>Bumiayu</th>
                                <th>Cilacap</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $totalSubtotal = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @php
                                    $subtotal = $detail['benjaran'] + $detail['tegal'] + $detail['slawi'] + $detail['pemalang'] + $detail['bumiayu']+ $detail['cilacap'];
                                    $totalSubtotal += $subtotal;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $detail['klasifikasi'] }}</td>
                                    <td>{{ $detail['tanggal_kirim'] }}</td>
                                    <td>{{ $detail['kode_produk'] }}</td>
                                    <td>{{ $detail['nama_produk'] }}</td>
                                    <td>{{ $detail['benjaran'] }}</td>
                                    <td>{{ $detail['tegal'] }}</td>
                                    <td>{{ $detail['slawi'] }}</td>
                                    <td>{{ $detail['pemalang'] }}</td>
                                    <td>{{ $detail['bumiayu'] }}</td>
                                    <td>{{ $detail['cilacap'] }}</td>
                                    <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    @endif

                    {{-- Benjaran --}}
                    @if($toko_id == '1' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko Banjaran</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '1')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['benjaran'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['benjaran'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['benjaran'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif
                    
                    

                    {{-- Tegal --}}
                    @if($toko_id == '2' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko Tegal</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '2')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['tegal'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['tegal'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['tegal'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif
                    
                    {{-- Slawi --}}
                    @if($toko_id == '3' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko Slawi</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '3')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['slawi'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['slawi'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['slawi'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif

                    {{-- Pemalang --}}
                    @if($toko_id == '4' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko Pemalang</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '4')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['pemalang'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['pemalang'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['pemalang'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif

                    {{-- Bumiayu --}}
                    @if($toko_id == '5' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko bumiayu</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '5')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['bumiayu'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['bumiayu'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['bumiayu'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif

                    @if($toko_id == '6' )
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Divisi</th>
                                <th>Kode Pemesanan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Toko Cilacap</th>
                                <th>Catatan</th>
                                {{-- <th>Total</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                                $currentKodeProduk = null;
                                $totalPerProduk = 0;
                            @endphp
                            @foreach ($groupedData as $detail)
                                @if ($toko_id == '5')
                                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                            <td>{{ $totalPerProduk }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                        @php
                                            $totalPerProduk = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $detail['tanggal_pemesanan'] }}</td>
                                        <td>{{ $detail['klasifikasi'] }}</td>
                                        <td>{{ $detail['kode_pemesanan'] }}</td>
                                        <td>{{ $detail['kode_produk'] }}</td>
                                        <td>{{ $detail['nama_produk'] }}</td>
                                        <td>{{ $detail['cilacap'] }}</td>
                                        <td>{{ $detail['catatanproduk'] }}</td>
                                        {{-- <td>{{ $detail['bumiayu'] }}</td> --}}
                                    </tr>
                                    @php
                                        $currentKodeProduk = $detail['kode_produk'];
                                        $totalPerProduk += $detail['cilacap'];
                                    @endphp
                                @endif
                            @endforeach
                            @if ($currentKodeProduk)
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                                    <td>{{ $totalPerProduk }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    @endif
         
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_pemesanan');
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
            form.action = "{{ url('admin/laporan_pemesananproduk') }}";
            form.submit();
        }

    </script>

<script>
    function printReport() {
        var startDate = document.getElementById('tanggal_pemesanan').value;
        var endDate = document.getElementById('tanggal_akhir').value;
        var tokoId = document.getElementById('toko_id').value; // Ambil nilai toko_id dari dropdown
    
        if (startDate && endDate) {
            var form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ url('admin/print_pemesanan') }}";
            form.target = "_blank";

    
            var startDateInput = document.createElement('input');
            startDateInput.type = 'hidden';
            startDateInput.name = 'start_date';
            startDateInput.value = startDate;
            form.appendChild(startDateInput);
    
            var endDateInput = document.createElement('input');
            endDateInput.type = 'hidden';
            endDateInput.name = 'end_date';
            endDateInput.value = endDate;
            form.appendChild(endDateInput);
    
            var tokoIdInput = document.createElement('input');
            tokoIdInput.type = 'hidden';
            tokoIdInput.name = 'toko_id';
            tokoIdInput.value = tokoId;
            form.appendChild(tokoIdInput);
    
            document.body.appendChild(form);
            form.submit();
        } else {
            alert("Silakan isi kedua tanggal sebelum mencetak.");
        }
    }
    </script>
@endsection
