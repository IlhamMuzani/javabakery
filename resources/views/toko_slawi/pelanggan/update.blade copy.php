@extends('layouts.app')

@section('title', 'Perbaru Pelanggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pelanggan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/pelanggan') }}">Pelanggan</a></li>
                        <li class="breadcrumb-item active">Perbarui</li>
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
            <form action="{{ url('admin/pelanggan/' . $pelanggans->id) }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-4">
                            <button class="btn btn-primary btn-sm" type="button" onclick="showCategoryModal(this.value)">
                                Pilih Pelanggan
                            </button>
                        </div>
                        <div class="row">
                            <div class="col  mb-3">
                                <label for="kode_pelanggan">No. Member Baru</label>
                                <input type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan"
                                     value="{{ old('kode_pelanggan', $pelanggans->kode_pelanggan) }}">
                            </div>
                            <div class="col  mb-3">
                                <label for="kode_lama">No.Member Lama</label>
                                <input type="text" class="form-control" id="kode_lama" name="kode_lama"
                                    placeholder="Masukan Kode Memnber" value="{{ old('kode_lama', $pelanggans->kode_lama) }}">
                            </div>
                            <div class="col  mb-3">
                                <label for="kode_lama">qr code</label>
                                {!! DNS2D::getBarcodeHTML("$pelanggans->qrcode_pelanggan", 'QRCODE', 1, 1) !!}
                            
                            </div>
                        </div>

                        <div class="row mb-3">
                        <div class="col">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                placeholder="Masukan nama pelanggan"
                                value="{{ old('nama_pelanggan', $pelanggans->nama_pelanggan) }}">
                        </div>
                    </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label" for="gender">Pilih Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">- Pilih -</option>
                            <option value="L" {{ old('gender', $pelanggans->gender) == 'L' ? 'selected' : null }}>
                                Laki-laki</option>
                            <option value="P" {{ old('gender', $pelanggans->gender) == 'P' ? 'selected' : null }}>
                                Perempuan</option>
                        </select>
                    </div>

                    <div class="col">
                        <label for="nama">Email</label>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Masukan email"
                            value="{{ old('email', $pelanggans->email) }}">
                    </div>
                    <div class="col">
                        <label for="telp">No. Telepon</label>
                        <div class="input-group mb-3 ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="number" id="telp" name="telp" class="form-control"
                                placeholder="Masukan nomor telepon" value="{{ old('telp', $pelanggans->telp) }}">
                        </div>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir:</label>
                    <div class="input-group date" id="reservationdatetime">
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                            placeholder="d M Y sampai d M Y"
                            data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                            value="{{ old('tanggal_lahir', $pelanggans->tanggal_lahir) }}"
                            class="form-control datetimepicker-input" data-target="#reservationdatetime">
                    </div>
                </div>

               
                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan alamat">{{ old('alamat', $pelanggans->alamat) }}</textarea>
                </div>
            </div>
        </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label>Tanggal Gabung:</label>
                                <div class="input-group date" id="reservationdatetime">
                                    <input type="date" id="tanggal_awal" name="tanggal_awal"
                                        placeholder="d M Y sampai d M Y"
                                        data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                        value="{{ old('tanggal_awal', $pelanggans->tanggal_awal) }}"
                                        class="form-control datetimepicker-input" data-target="#reservationdatetime">
                                </div>
                            </div>
                            <div class="col">
                                <label>Tanggal Expired:</label>
                                <div class="input-group date" id="reservationdatetime">
                                    <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                                        placeholder="d M Y sampai d M Y"
                                        data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                        value="{{ old('tanggal_akhir', $pelanggans->tanggal_akhir) }}"
                                        class="form-control datetimepicker-input" data-target="#reservationdatetime">
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
                    </div>   
            </form>
            {{-- </div> --}}
        </div>


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

    function getSelectedDatamarketing(marketing_id, kodeMarketing, namaMarketing, Telp, Umur, Alamat) {
        // Set the values in the form fields
        document.getElementById('marketing_id').value = marketing_id;
        document.getElementById('kode_marketing').value = kodeMarketing;
        document.getElementById('nama_marketing').value = namaMarketing;
        document.getElementById('telp_marketing').value = Telp;
        document.getElementById('umur_marketing').value = Umur;
        document.getElementById('alamat_marketing').value = Alamat;

        // Close the modal (if needed)
        $('#tableMarketing').modal('hide');
    }
</script>
    </section>
@endsection
