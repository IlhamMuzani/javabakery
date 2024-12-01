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
                        <li class="breadcrumb-item"><a href="{{ url('admin/produk') }}">Produk</a></li>
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
                <div class="card-body">
                    <form action="{{ route('produk.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file_excel">Unggah File Excel</label>
                            <input type="file" class="form-control-file" id="file_excel" name="file_excel" accept=".xlsx">
                        </div>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </form>
                    
                </div>
      
                <!-- /.card-header -->
                <form action="{{ url('admin/produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto mt-2">
                                <label for="nama">Nama Produk</label>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                        placeholder="Masukan nama produk" value="{{ old('nama_produk') }}">
                                    <button class="btn btn-primary ml-2" type="button" onclick="showCategoryModalmarketing()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan input field tersembunyi untuk menyimpan subsub_id dan subklasifikasi_id -->
                        <input type="hidden" id="subsub_id" name="subsub_id">
                        <input type="hidden" id="subklasifikasi_id" name="subklasifikasi_id"> 
                        <input type="hidden" id="klasifikasi_id" name="klasifikasi_id"> 
                        <input type="hidden" id="nama_input" name="nama_input">

                        <div class="row">
                            <div class="col mb-3">
                                <label for="harga">Harga</label>
                                <input type="number" class="form-control" id="harga" name="harga"
                                    placeholder="Masukan harga" value="{{ old('harga') }}">
                            </div>
                            <div class="col mb-3" hidden>
                                <label for="diskon">Diskon</label>
                                <input type="number" class="form-control" id="diskon" name="diskon"
                                    placeholder="Masukan diskon" value="0">
                            </div>
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

                <div class="modal fade" id="tableMarketing" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Klasifikasi</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table id="datatables4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Kategori</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($klasifikasis as $index => $faktur)
                                            <tr data-toggle="collapse" data-target="#faktur-{{ $index }}" class="accordion-toggle" style="background: rgb(248, 246, 246)">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $faktur->nama }}</td>
                                                <td>
                                                    <button class="btn btn-primary" data-toggle="collapse" data-target="#faktur-{{ $index }}"><i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div id="faktur-{{ $index }}" class="collapse">
                                                        <table class="table table-sm" style="margin: 0;">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sub Kategori</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($faktur->subklasifikasi as $subIndex => $memo)
                                                                    <tr data-toggle="collapse" data-target="#subklasifikasi-{{ $index }}-{{ $subIndex }}" class="accordion-toggle" style="background: rgb(235, 235, 235)">
                                                                        <td>{{ $memo->nama }}</td>
                                                                        <td>
                                                                            <button class="btn btn-primary" data-toggle="collapse" data-target="#subklasifikasi-{{ $index }}-{{ $subIndex }}"><i class="fas fa-plus"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div id="subklasifikasi-{{ $index }}-{{ $subIndex }}" class="collapse">
                                                                                <table class="table table-sm" style="margin: 0;">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Sub Sub Kategori</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($memo->subsub as $itemIndex => $item)
                                                                                            <tr>
                                                                                                <td>{{ $item->nama }}</td>
                                                                                                <td>
                                                                                                    <button type="button" class="btn btn-primary btn-sm" onclick="getSelectedDatamarketing('{{ $item->id }}', '{{ $memo->id }}', '{{ $faktur->id }}', '{{ $item->nama }}')">
                                                                                                        <i class="fas fa-plus"></i>
                                                                                                    </button>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </section>

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
                                $('#subklasifikasi_id').append('<option value="' + value.id + '">' + value.nama + '</option>');
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
