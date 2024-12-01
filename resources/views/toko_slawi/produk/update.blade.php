@extends('layouts.app')

@section('title', 'Tambah produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_slawi/produk') }}">produk</a></li>
                        <li class="breadcrumb-item active">Update</li>
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
                    <h3 class="card-title">Update produk</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('toko_slawi/produk/' . $produk->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">

                        <div class="form-group">
                            <label for="nama">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                placeholder="Masukan nama produk" value="{{ old('nama_produk', $produk->nama_produk) }}">
                        </div>
                     <div class="row">
                        <div class="col mb-3">
                            <label for="nama">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Masukan harga" value="{{ old('harga', $produk->harga) }}">
                        </div>
                    </div>
                        <div class="mb-3">
                            <label class="form-label" for="satuan">Pilih Satuan</label>
                            <select class="form-control" id="satuan" name="satuan">
                                <option value="">- Pilih -</option>
                                <option value="gr" {{ old('satuan', $produk->satuan) == 'gr' ? 'selected' : null }}>
                                    gr</option>
                                <option value="kg" {{ old('satuan', $produk->satuan) == 'kg' ? 'selected' : null }}>
                                    kg</option>
                                <option value="pcs" {{ old('satuan', $produk->satuan) == 'pcs' ? 'selected' : null }}>
                                    pcs</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar <small>(Kosongkan saja jika tidak
                                    ingin menambahkan)</small></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar">Masukkan gambar produk</label>
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
@endsection
