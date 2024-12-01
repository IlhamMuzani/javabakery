@extends('layouts.app')

@section('title', 'Perbarui Karyawan')

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
                    <h3 class="card-title">Perbarui Barang</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('admin/barang/' . $barang->id) }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                       <div class="row">
                            <div class="col">
                                <label for="kode_barang">Kode Barang</label>
                                <input type="text" readonly class="form-control" id="kode_barang" name="kode_barang"
                                    placeholder="Masukan kode" value="{{ old('kode_barang', $barang->kode_barang) }}">
                            </div>
                            <div class="col mb-3">
                                <label for="nama">Nama Barang</label>
                                <input type="text" readonly class="form-control" id="nama_lengkap" name="nama_lengkap"
                                    placeholder="Masukan nama lengkap"
                                    value="{{ old('nama_lengkap', $barang->subsub->nama) }}">
                            </div>
                        </div>   
                        <div class="form-group">
                            <label for="nama">Stok Barang</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                value="{{ old('jumlah', $barang->jumlah) }}">
                        </div> 
                        <div class="form-group">
                            <label for="nama">Harga Barang</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                value="{{ old('harga', $barang->harga) }}">
                        </div> 
                        <div class="form-group">
                            <label for="nama">Diskon Barang</label>
                            <input type="number" class="form-control" id="diskon" name="diskon"
                                value="{{ old('diskon', $barang->diskon) }}">
                        </div> 

                    {{-- <div class="row">
                        <div class="col">
                            <label for="nama">No KTP</label>
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                placeholder="Masukan no KTP" value="{{ old('no_ktp', $karyawan->no_ktp) }}">
                        </div>
                        <div class="col">
                            <label for="nama">No SIM</label>
                            <input type="text" class="form-control" id="no_sim" name="no_sim"
                                placeholder="Masukan no SIM" value="{{ old('no_sim', $karyawan->no_sim) }}">
                        </div>
                    </div> --}}


                        {{-- <div class="mb-3">
                            <label class="form-label" for="gender">Pilih Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">- Pilih -</option>
                                <option value="L" {{ old('gender', $karyawan->gender) == 'L' ? 'selected' : null }}>
                                    Laki-laki</option>
                                <option value="P" {{ old('gender', $karyawan->gender) == 'P' ? 'selected' : null }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir:</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}"
                                    class="form-control datetimepicker-input" data-target="#reservationdatetime">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Gabung:</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal_gabung" name="tanggal_gabung"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal_gabung', $karyawan->tanggal_gabung) }}"
                                    class="form-control datetimepicker-input" data-target="#reservationdatetime">
                            </div>
                        </div> --}}

                        {{-- <div class="mb-3">
                            <label class="form-label" for="jabatan">Jabatan</label>
                            <select class="form-control" id="jabatan" name="jabatan">
                                <option value="">- Pilih Jabatan -</option>
                                <option value="STAFF"
                                    {{ old('jabatan', $karyawan->jabatan) == 'STAFF' ? 'selected' : null }}>
                                    STAFF</option>
                            </select>
                        </div> --}}

{{--                         
                        <div class="form-group">
                            <label for="telp">No. Telepon</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+62</span>
                                </div>
                                <input type="text" id="telp" name="telp" class="form-control"
                                    placeholder="Masukan nomor telepon" value="{{ old('telp', $karyawan->telp) }}">
                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan alamat"
                                value="">{{ old('alamat', $karyawan->alamat) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="gambar">
                                Gambar
                                <small>(kosongkan saja jika tidak ingin diubah)</small>
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar">Pilih Gambar</label>
                            </div>
                        </div> --}}
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
