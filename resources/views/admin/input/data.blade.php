@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Barang</li>
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
                    <h3 class="card-title">Data barang jadi</h3>
                </div>

                {{-- <div class="row mb-2">
                    <div class="col">
                        <form action="#" method="GET" class="form-inline float-sm-right">
                            <label for="tanggal">Filter Tanggal:</label>
                            <input type="date" class="form-control mx-2" id="tanggal" name="tanggal">
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        </form>
                    </div>
                </div><!-- /.row --> --}}
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Faktur</th>
                                <th>Tanggal</th>
                                <th>Cabang</th>
                                <th>Total</th>
                                <th>Kategori</th>
                                <th>opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inputs as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_faktur }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->cabang }}</td>
                                    <td>{{ $item->sub_total }}</td>
                                    <td>
                                        @php
                                        $printedKlasifikasi = [];
                                    @endphp
                                    @foreach ($item->details as $detail)
                                        @php
                                            $klasifikasiNama = $detail->barang->subsub->subklasifikasi->klasifikasi->nama;
                                        @endphp
                                        @if (!in_array($klasifikasiNama, $printedKlasifikasi))
                                            {{ $klasifikasiNama }}<br>
                                            @php
                                                $printedKlasifikasi[] = $klasifikasiNama;
                                            @endphp
                                        @endif
                                    @endforeach
                                    </td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-info btn-sm">
                                            <i class="fas fa-print"></i>
                                        </a>
                                      
                                  
                                    </td>
                                    
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
