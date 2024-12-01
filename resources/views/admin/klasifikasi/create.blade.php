@extends('layouts.app')

@section('title', 'Tambah Departemen')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kategori</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/klasifikasi') }}">Kategori</a></li>
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
                {{-- <div class="col-md-2 mb-3">
                    <select class="select2bs4 select2-hidden-accessible" name="klasifikasi_id"
                        data-placeholder="Cari Kode.." style="width: 100%;" data-select2-id="23" tabindex="-1"
                        aria-hidden="true" id="klasifikasi_id">
                        <option value="">- Pilih -</option>
                        @foreach ($klasifikasis as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}"
                                {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                {{ $klasifikasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    <label for="klasifikasi_id">(Cari Departemen)</label>
                </div>
                <div class="col-md-2 mb-3">
                    <select class="select2bs4 select2-hidden-accessible" name="subklasifikasi_id"
                        data-placeholder="Cari Kode.." style="width: 100%;" data-select2-id="23" tabindex="-1"
                        aria-hidden="true" id="subklasifikasi_id">
                        <option value="">- Pilih -</option>
                        @foreach ($subs as $item)
                            <option value="{{ $item->id }}"
                                {{ Request::get('subklasifikasi_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->sub_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <label for="subklasifikasi_id">(Cari Karyawan)</label>
                </div> --}}
                
                <div class="card-header">
                    <h3 class="card-title">Tambah Kategori</h3>
                </div>
                <form action="{{ url('admin/klasifikasi') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukan nama" value="{{ old('nama') }}">
                        </div>
                        {{-- <div class="form-group">
                            <label for="kode_klasifikasi">kode_klasifikasi</label>
                            <input type="text" class="form-control" id="kode_klasifikasi" name="kode_klasifikasi"
                                placeholder="Masukan kode_klasifikasi" value="{{ old('kode_klasifikasi') }}">
                        </div>
                        <div class="form-group">
                            <label for="qrcode_klasifikasi">qrcode_klasifikasi</label>
                            <input type="text" class="form-control" id="qrcode_klasifikasi" name="qrcode_klasifikasi"
                                placeholder="Masukan qrcode_klasifikasi" value="{{ old('qrcode_klasifikasi') }}">
                        </div> --}}
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>


    {{-- <script>
        $(document).ready(function() {
            $('#kategori-select').on('change', function() {
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: '/klasifikasi/' + id + '/sub',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#subkategori-select').empty();
                            $('#subkategori-select').append('<option value="">Pilih</option>');
                            $.each(data, function(key, value) {
                                $('#subkategori-select').append('<option value="' + value.id + '">' + value.sub_kategori + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subkategori-select').empty();
                    $('#subkategori-select').append('<option value="">Pilih</option>');
                }
            });

            $('#kategori-select').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Kategori",
                width: '100%'
            });

            $('#subkategori-select').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Sub Kategori",
                width: '100%'
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('#klasifikasi_id').on('change', function() {
                var klasifikasiID = $(this).val();
                if (klasifikasiID) {
                    $.ajax({
                        url: "{{ url('admin/klasifikasi/get_subklasifikasi') }}" + '/' + klasifikasiID,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#subklasifikasi_id').empty();
                            $('#subklasifikasi_id').append('<option value="">- Pilih -</option>');
                            $.each(data, function(key, value) {
                                $('#subklasifikasi_id').append('<option value="' + value.id +
                                    '">' + value.nama + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subklasifikasi_id').empty();
                    $('#subklasifikasi_id').append('<option value="">- Pilih -</option>');
                }
            });
        });
    </script>
@endsection
