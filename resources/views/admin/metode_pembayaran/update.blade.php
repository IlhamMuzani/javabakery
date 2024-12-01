@extends('layouts.app')

@section('title', 'Perbarui Merode Pembayaran')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Metode Pembayaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/metode') }}">Metode Pembayaran</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perbarui Metode Pembayaran</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('admin/metode_pembayaran/' . $metode->id) }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Metode Pembayaran</label>
                            <input type="text" class="form-control" id="nama_metode" name="nama_metode"
                                placeholder=""
                                value="{{ old('nama_metode', $metode->nama_metode) }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Diskon</label>
                            <input type="text" class="form-control" id="diskon" name="diskon"
                                placeholder=""
                                value="{{ old('diskon', $metode->diskon) }}">
                        </div>
                        <div class="form-group">
                            <label for="nama">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                placeholder=""
                                value="{{ old('keterangan', $metode->keterangan) }}">
                        </div>

                    
                        
                        
                    </div>
            </div>
            <div class="card-footer text-right">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
        </div>
    </section>
@endsection
