@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/barang') }}">Barang</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Barang</h3>
                </div>
                <form action="{{ url('admin/barang') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto mt-2">
                                <div class="form-group mb-3">
                                    <label for="nama">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_input" name="nama_input" value="{{ old('nama') }}" readonly>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" type="button" onclick="showCategoryModalmarketing(this.value)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        
                        <div class="form-group mb-3">
                            <label for="keterangan">Deskripsi Barang</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Masukan deskripsi barang">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jumlah">Stok</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Masukan stok" value="{{ old('jumlah') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="diskon">Diskon</label>
                            <input type="text" class="form-control" id="diskon" name="diskon" value="{{ old('diskon') }}">
                        </div>
              

                        <input type="hidden" id="subsub_id" name="subsub_id" value="{{ old('subsub_id') }}">
                        <input type="hidden" id="nama" name="nama" value="{{ old('nama') }}">

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
                                <h4 class="modal-title">Data Pelanggan</h4>
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
                                                                                                <button type="button" class="btn btn-primary btn-sm"
                                                                                                    onclick="getSelectedDatamarketing('{{ $item->id }}', '{{ $item->nama }}')">
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
    function showCategoryModal(selectedCategory) {
        $('#tableKategori').modal('show');
    }

    function getSelectedData(merek_id, namaMerek, namaModel, namaTipe) {
        // Set the values in the form fields
        document.getElementById('merek_id').value = merek_id;
        document.getElementById('nama_merek').value = namaMerek;
        document.getElementById('model').value = namaModel;
        document.getElementById('tipe').value = namaTipe;

        // Close the modal (if needed)
        $('#tableKategori').modal('hide');
    }

    document.getElementById('btn-tambah-tipe').addEventListener('click', function() {
        var modalTipe = new bootstrap.Modal(document.getElementById('modal-tipe'));
        modalTipe.show();
    });

    document.getElementById('btn-tambah-model').addEventListener('click', function() {
        var modalTipe = new bootstrap.Modal(document.getElementById('modal-model'));
        modalTipe.show();
    });

    function showCategoryModalmarketing(selectedCategory) {
        $('#tableMarketing').modal('show');
    }

    function getSelectedDatamarketing(subsub_id, nama) {
    // Mengatur nilai pada input field tersembunyi
    document.getElementById('subsub_id').value = subsub_id;
    document.getElementById('nama_input').value = nama;

    // Memperbarui field form yang terlihat
    document.getElementById('nama_input').value = nama;

    // Menutup modal
    $('#tableMarketing').modal('hide');
}

</script>
@endsection
