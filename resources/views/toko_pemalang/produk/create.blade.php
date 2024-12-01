@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_pemalang/produk') }}">Produk</a></li>
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

                <!-- /.card-header -->
                <form action="{{ url('toko_pemalang/produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        
                        <div class="col-md-3 mb-3">
                            <label for="status">Divisi</label>
                            <select class="custom-select form-control" id="klasifikasi" name="klasifikasi_id" onchange="filterProduk()">
                                <option value="">- Semua Divisi -</option>
                                @foreach ($klasifikasis as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}" {{ Request::get('klasifikasi_id') == $klasifikasi->id ? 'selected' : '' }}>{{ $klasifikasi->nama }}</option>
                                @endforeach
                            </select>

                            <label style="margin-top:7px" for="status">Kategori</label>
                             <select class="custom-select form-control" id="subklasifikasi" name="subklasifikasi_id">
                                <option value="">- Semua Kategori -</option>
                                @foreach ($subklasifikasis as $subklasifkasi)
                                    <option value="{{ $subklasifkasi->id }}" data-klasifikasi="{{ $subklasifkasi->klasifikasi_id }}" {{ Request::get('subklasifkasi') == $subklasifkasi->id ? 'selected' : '' }}>{{ $subklasifkasi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto mt-2">
                                <label for="nama">Nama Produk</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                        placeholder="Masukan nama produk" value="{{ old('nama_produk') }}">
                                
                                </div>
                            </div>
                            <div class="col-auto mt-2">
                                <label for="nama">Kode Produk</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" id="kode_lama" name="kode_lama"
                                        placeholder="Masukan kode produk" value="{{ old('kode_lama') }}">
                                
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col mb-3">
                                <label for="harga">Harga Member</label>
                                <input type="number" class="form-control" id="harga" name="harga"
                                    placeholder="Masukan harga member" value="{{ old('harga') }}">
                            </div>
                            <div class="col mb-3" >
                                <label for="diskon">Diskon Member</label>
                                <input type="number" class="form-control" id="diskon" name="diskon"
                                    placeholder="Masukan diskon" value="0">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="harga">Harga non Member</label>
                                <input type="number" class="form-control" id="harga" name="harga"
                                    placeholder="Masukan harga member" value="{{ old('harga') }}">
                            </div>
                            <div class="col mb-3" >
                                <label for="diskon">Diskon non Member</label>
                                <input type="number" class="form-control" id="diskon" name="diskon"
                                    placeholder="Masukan diskon" value="0">
                            </div>
                            {{-- <div class="col mb-3">
                                <label for="satuan">Pilih Satuan</label>
                                <select class="form-control" id="satuan" name="satuan">
                                    <option value="">- Pilih -</option>
                                    <option value="gr" {{ old('satuan') == 'gr' ? 'selected' : '' }}>gr</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                                </select>
                            </div> --}}
                        </div>
                        <div class="row">
                           
                            <div class="col mb-3">
                                <label for="satuan">Pilih Satuan</label>
                                <select class="form-control" id="satuan" name="satuan">
                                    <option value="">- Pilih -</option>
                                    <option value="gr" {{ old('satuan') == 'gr' ? 'selected' : '' }}>gr</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar <small>(Kosongkan saja jika tidak ingin menambahkan)</small></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
    <script>
        function filterProduk() {
            var klasifikasiId = document.getElementById('klasifikasi').value;
            var produkSelect = document.getElementById('subklasifikasi');
            var produkOptions = produkSelect.options;

            for (var i = 0; i < produkOptions.length; i++) {
                var option = produkOptions[i];
                if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }

            // Reset the selected value of the product select box
            produkSelect.selectedIndex = 0;
        }

    </script>

    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide();
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>

    <script>
        function showCategoryModalmarketing() {
            $('#tableMarketing').modal('show');
        }

        function getSelectedDatamarketing(subsub_id, subklasifikasi_id,klasifikasi_id, nama) {
            // Mengatur nilai pada input field tersembunyi
            document.getElementById('subsub_id').value = subsub_id;
            document.getElementById('subklasifikasi_id').value = subklasifikasi_id;
            document.getElementById('klasifikasi_id').value = klasifikasi_id;
            document.getElementById('nama_input').value = nama;

            // Memperbarui field form yang terlihat
            document.getElementById('nama_produk').value = nama;

            // Menutup modal
            $('#tableMarketing').modal('hide');
        }
    </script>

@endsection
