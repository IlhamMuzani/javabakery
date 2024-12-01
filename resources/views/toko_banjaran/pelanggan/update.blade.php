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
                        <li class="breadcrumb-item"><a href="{{ url('toko_banjaran/pelanggan') }}">Pelanggan</a></li>
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
            <form action="{{ url('toko_banjaran/pelanggan/' . $pelangganfirst->id) }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Pelanggan</h3>
                    </div>
                    <div class="card-body">
             
                        {{-- <div class="mb-3 d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm" type="button" onclick="showCategoryModalmarketing(this.value)">
                                Pilih Pelanggan
                            </button>
                        </div>     --}}
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
                                                    <th>Kode Lama</th>
                                                    <th>Nama Pelanggan</th>                                           
                                                    <th>email</th>
                                                    {{-- <th>pekerjaan</th> --}}
                                                    <th>Telepon</th>                
                                                    <th>Alamat</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pelanggans as $pelanggan)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $pelanggan->kode_pelangganlama }}</td>
                                                    <td>{{ $pelanggan->nama_pelanggan }}</td>
                                                    <td>{{ $pelanggan->email }}</td>
                                                    {{-- <td>{{ $pelanggan->pekerjaan }}</td> --}}
                                                    <td>{{ $pelanggan->telp }}</td>
                                                    <td>{{ $pelanggan->alamat }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            onclick="getSelectedDatamarketing('{{ $pelanggan->id }}', '{{ $pelanggan->kode_pelangganlama }}', '{{ $pelanggan->nama_pelanggan }}', '{{ $pelanggan->gender }}','{{ $pelanggan->email }}', '{{ $pelanggan->telp }}'
                                                            ,'{{ $pelanggan->tanggal_lahir }}', '{{ $pelanggan->alamat }}' , '{{ $pelanggan->tanggal_awal }}', '{{ $pelanggan->tanggal_akhir }}')">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                        
                      {{-- //inpputan --}}
                        <div class="row">

                             
                                <input type="text" class="form-control" id="pelanggan_id" name="pelanggan_id"
                                     value="{{ old('pelanggan_id') }}" hidden>
                           
                            <div class="col  mb-3">
                                <label for="kode_pelanggan">No. Member Baru</label>
                                <input type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan"
                                     value="{{ old('kode_pelanggan', $pelangganfirst->kode_pelanggan) }}" readonly>
                            </div>
                            <div class="col  mb-3">
                                <label for="kode_pelangganlama">No.Member Lama</label>
                                <input type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama"
                                    placeholder="Masukan Kode Memnber" value="{{ old('kode_pelangganlama', $pelangganfirst->kode_pelangganlama) }}">
                            </div>
                            <div class="col  mb-3">
                                <label for="kode_pelangganlama">qr code</label>
                                {!! DNS2D::getBarcodeHTML("$pelangganfirst->qrcode_pelanggan", 'QRCODE', 1, 1) !!}
                            
                            </div>
                        </div>

                        <div class="row mb-3">
                        <div class="col">
                            <label for="nama_pelanggan">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                placeholder="Masukan nama pelangganfirst"
                                value="{{ old('nama_pelanggan', $pelangganfirst->nama_pelanggan)}}">
                        </div>
                    </div>
                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label" for="gender">Pilih Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">- Pilih -</option>
                            <option value="L" {{ old('gender', $pelangganfirst->gender) == 'L' ? 'selected' : null }}>
                                Laki-laki</option>
                            <option value="P" {{ old('gender', $pelangganfirst->gender) == 'P' ? 'selected' : null }}>
                                Perempuan</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="nama">Email</label>
                        <input type="text" class="form-control" id="email" name="email"
                            placeholder="Masukan email"
                            value="{{ old('email', $pelangganfirst->email) }}">
                    </div>
                    <div class="col">
                        <label for="telp">No. Telepon</label>
                        <div class="input-group mb-3 ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+62</span>
                            </div>
                            <input type="number" id="telp" name="telp" class="form-control"
                                placeholder="Masukan nomor telepon" value="{{ old('telp', $pelangganfirst->telp) }}">
                        </div>
                    </div>
                    
                </div>
            <div class="row">
                <div class="col mb-3">
                    <label>Tanggal Lahir:</label>
                    <div class="input-group date" id="reservationdatetime">
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                            placeholder="d M Y sampai d M Y"
                            data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                            value="{{ old('tanggal_lahir', $pelangganfirst->tanggal_lahir) }}"
                            class="form-control datetimepicker-input" data-target="#reservationdatetime">
                    </div>
                </div>
                <div class="col">
                    <label for="nama">pekerjaan</label>
                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan"
                        placeholder="Masukan pekerjaan"
                        value="{{ old('pekerjaan', $pelangganfirst->pekerjaan) }}">
                </div>
            </div>

               
                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan alamat">{{ old('alamat', $pelangganfirst->alamat) }}</textarea>
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
                                        value="{{ old('tanggal_awal', $pelangganfirst->tanggal_awal) }}"
                                        class="form-control datetimepicker-input" data-target="#reservationdatetime">
                                </div>
                            </div>
                            <div class="col">
                                <label>Tanggal Expired:</label>
                                <div class="input-group date" id="reservationdatetime">
                                    <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                                        placeholder="d M Y sampai d M Y"
                                        data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                        value="{{ old('tanggal_akhir', $pelangganfirst->tanggal_akhir) }}"
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
                    url: "{{ url('toko_banjaran/pelanggan/getpelanggan') }}" + "/" + pelanggan_id.value,
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

    function getSelectedDatamarketing(pelanggan_id, kode_pelangganlama,  nama_pelanggan, gender, email, telp, tanggal_lahir, alamat, tanggal_awal, tanggal_akhir) {
        // Set the values in the form fields
        document.getElementById('pelanggan_id').value = pelanggan_id;
        document.getElementById('kode_pelangganlama').value = kode_pelangganlama;
        document.getElementById('nama_pelanggan').value = nama_pelanggan;
        document.getElementById('gender').value = gender;
        document.getElementById('email').value = email;
        document.getElementById('pekerjaan').value = pekerjaan;
        document.getElementById('telp').value = telp;
        document.getElementById('tanggal_lahir').value = tanggal_lahir;
        document.getElementById('alamat').value = alamat;
        document.getElementById('tanggal_awal').value = tanggal_awal;
        document.getElementById('tanggal_akhir').value = tanggal_akhir;

        // Close the modal (if needed)
        $('#tableMarketing').modal('hide');
    }
</script>
    </section>
@endsection
