@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pelanggan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Pelanggan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif --}}
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
                    <h3 class="card-title">Data Pelanggann</h3>

                    <div class="float-right">
                    <a href="{{ url('admin/pelanggan/create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                    </a>
                    </div>
                </div>
    
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="GET" action="{{ url('admin/pelanggan') }}" class="form-inline float-right">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Cari pelanggan...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form><br><br>
                    <table id="data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>No Member Baru</th>
                                <th>No Member Lama</th>
                                <th>Nama Pelanggan</th>
                                <th>Telepon</th>
                                <th class="text-center">Qr Code</th>
                                <th class="text-center" width="125">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($pelanggans as $pelanggan)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $pelanggan->kode_pelanggan }}</td>
                                    <td>{{ $pelanggan->kode_pelangganlama }}</td>
                                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                                    <td>{{ $pelanggan->telp }}</td>
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $pelanggan->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 1, 1) !!}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('admin/pelanggan/' . $pelanggan->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ url('admin/pelanggan/' . $pelanggan->id . '/edit') }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
                                                <p>Yakin hapus pelanggan <strong>{{ $pelanggan->nama_pelanggan }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/pelanggan/' . $pelanggan->id) }}"
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
                                                        {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 10, 10) !!}
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

        <div class="modal fade" id="modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pilih Kode</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <form action="{{ url('admin/pelanggan') }}" enctype="multipart/form-data"
                                autocomplete="off" method="post">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Pilih Nomor Member</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group" style="flex: 8;"> <!-- Adjusted flex value -->
                                            <select class="select2bs4 select2-hidden-accessible"
                                                name="pelanggan_id" data-placeholder="Cari Kode.."
                                                style="width: 100%;" data-select2-id="23" tabindex="-1"
                                                aria-hidden="true" id="pelanggan_id" onchange="getData(0)">
                                                <option value="">- Pilih -</option>
                                                @foreach ($pelanggans as $pelanggan)
                                                    <option value="{{ $pelanggan->id }}"
                                                        {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                                        {{ $pelanggan->kode_pelanggan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nopol">Kode Member</label>
                                            <input type="text" class="form-control" id="kode_pelanggan"
                                                name="kode_pelanggan" readonly
                                                placeholder="masukan kode member"
                                                value="{{ old('id') }}">
                                            <input type="text" hidden class="form-control" id="ids"
                                                name="id_pelanggan" readonly
                                                placeholder=""
                                                value="{{ old('id') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Lanjutkan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.card -->
    <script>
        function getData(id) {
            var pelanggan_id = document.getElementById('pelanggan_id');
            $.ajax({
                url: "{{ url('admin/pelanggan/getpelanggan') }}" + "/" + pelanggan_id.value,
                type: "GET",
                dataType: "json",
                success: function(pelanggan_id) {
                    var kode_pelanggan = document.getElementById('kode_pelanggan');
                    kode_pelanggan.value = pelanggan_id.kode_pelanggan;

                    var ids = document.getElementById('ids');
                    ids.value = pelanggan_id.id;

                },
            });
        }
    </script>


@endsection



