@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Barang</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/barang') }}">Barang</a></li>
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
                    <h3 class="card-title">Tambah Barang</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('admin/barang') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nama">Kode Barang</label>
                            <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                                placeholder="Masukan kode barang" value="{{ old('kode_barang') }}">
                        </div>
                        <div class="col">
                            <label for="nama">Kelompok Barang</label>
                            <select class="custom-select form-control" aria-label="Default select example">
                                <option selected>-- Pilih --</option>
                                <option value="1">Barang Jadi</option>
                                <option value="2">Barang Mentah</option>
                            </select>
                        </div>
                    </div>

                        <div class="form-group mb-3">
                            <label for="nama">Deskripsi Barang</label>
                            <textarea type="text" class="form-control" id="keterangan" name="keterangan"
                                placeholder="Masukan deskripsi barang" value="{{ old('keterangan') }}"></textarea>
                        </div>

                      
                    </div>
                </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3">
                                <li class="nav-item">
                                  <a class="nav-link active" data-toggle="tab" href="#tab1">Informasi Stok</a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" data-toggle="tab" href="#tab2">Satuan</a>
                                </li> 
                            </ul>
                              
                              <div class="tab-content">
                                <div class="tab-pane fade show active" id="tab1">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nama">Stok</label>
                                            <input type="text" class="form-control" id="jumlah" name="jumlah"
                                                placeholder="Masukan stok" value="{{ old('jumlah') }}">
                                        </div>
                                        <div class="col">
                                            <label for="nama">Harga Beli Satuan</label>
                                            <input type="text" class="form-control" id="jumlah" name="jumlah"
                                                placeholder="" value="{{ old('jumlah') }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="nama">Harga Jual Satuan</label>
                                            <input type="text" class="form-control" id="jumlah" name="jumlah"
                                                placeholder="" value="{{ old('jumlah') }}">
                                        </div>
                                        <div class="col">
                                            <label for="nama">Harga Pokok Satuan</label>
                                            <input type="text" class="form-control" id="jumlah" name="jumlah"
                                                placeholder="" value="{{ old('jumlah') }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <label for="klasifikasi_barang">Klasifikasi Barang</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="klasifikasi_barang" name="klasifikasi_barang" placeholder="" value="{{ old('klasifikasi_barang') }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary btn-sm" type="button" onclick="showCategoryModal(this.value)">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="nama">Target kupon</label>
                                            <input type="text" class="form-control" id="jumlah" name="jumlah"
                                                placeholder="" value="{{ old('jumlah') }}">
                                        </div>
                                    </div>
                                    <div class="modal fade" id="tableMarketing" data-backdrop="static">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Data Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>     
                                                </div>
                                        <div class="container">
                                            <div class="form-group mb-3">
                                                 {{-- <div class="col-md-2 mb-3"> --}}
                                                    <select class="select2bs4 select2-hidden-accessible" name="klasifikasi_id"
                                                        data-placeholder="Cari Kategori.." style="width: 100%;" data-select2-id="23" tabindex="-1"
                                                        aria-hidden="true" id="klasifikasi_id">
                                                        <option value="">- Pilih -</option>
                                                        @foreach ($klasifikasis as $klasifikasi)
                                                            <option value="{{ $klasifikasi->id }}"
                                                                {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>
                                                                {{ $klasifikasi->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                                <div class="form-group mb-3">
                                                    <select class="select2bs4 select2-hidden-accessible" name="subklasifikasi_id"
                                                        data-placeholder="Cari Subkategori.." style="width: 100%;" data-select2-id="23" tabindex="-1"
                                                        aria-hidden="true" id="subklasifikasi_id">
                                                        <option value="">- Pilih -</option>
                                                        @foreach ($subs as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ Request::get('subklasifikasi_id') == $item->id ? 'selected' : '' }}>
                                                                {{ $item->sub_kategori }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                        </div>
                                        </div>
                                    </div>      
                                </div>
                                  
                               <div class="tab-pane fade" id="tab2">
                                    <div class="tab-pane fade show active" id="tab2">
                                        <div class="form-group">
                                            <label for="nama">Harga</label>
                                            <input type="text" class="form-control" id="harga" name="harga"
                                                placeholder="Masukan harga" value="{{ old('harga') }}">
                                        </div>
                                    </div>
                                </div>
                        </div>
                       
                    </div>
                    
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                        <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                        <div id="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                        </div>
                    </div>
                </form>
            
        </div>
    </section>

    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>

    <script>

    function showCategoryModal(selectedCategory) {
        $('#tableMarketing').modal('show');
    }
    </script>
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
