@extends('layouts.app')

@section('title', 'Data Plenggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data pelanggan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data pelanggan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">       
                </div>
                <div class="card-header">
                    <h3 class="card-title">Data Pelanggan</h3>
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#modaladd">
                        <i class="fas fa-plus"></i>
                    </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <div class="mb-3">
                        <form method="GET" action="" class="form-control">
                            <label for="filter">Filter:</label>
                            <select name="filter" id="filter" onchange="this.form.submit()">
                                {{-- <option value="">Semua Data</option> --}}
                                <option value="belum" {{ request('filter') == 'belum' ? 'selected' : '' }}>no kartu belum terpakai</option>
                                <option value="sudah" {{ request('filter') == 'sudah' ? 'selected' : '' }}>no kartu terpakai</option>
                            </select>
                        </form>
                    </div>
                 
                    <table id="datatables1" class="table table-bordered table-striped">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-center">No</th>
                                <th>No pelanggan</th>
                               
                                <th class="text-center">Qr Code</th>
                                <th class="text-center" width="125">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($pelanggans as $pelanggan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $pelanggan->kode_pelanggan }}</td>
                                   
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $pelanggan->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 1, 1) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                  
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#modal-hapus-{{ $pelanggan->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-hapus-{{ $pelanggan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Karyawan</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus Kode <strong>{{ $pelanggan->kode_pelanggan }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/kartu/' . $pelanggan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal-qrcode-{{ $pelanggan->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $pelanggan->kode_pelanggan }}</p>
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $pelanggan->nama_pelanggan }}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/pelanggan/cetak-qrcode/' . $pelanggan->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="modal fade" id="modaladd">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah No Member</h4>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Tekan OK untuk menambhakan data?
                        </p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">Batal</button>
                            <form action="{{ url('admin/kartu') }}" method="POST" enctype="multipart/form-data"
                                autocomplete="off">
                                @csrf
                                <div class="text-right">
                                
                                <button type="submit" class="btn btn-primary float-right">OK</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </section>
    <!-- /.card -->
@endsection
