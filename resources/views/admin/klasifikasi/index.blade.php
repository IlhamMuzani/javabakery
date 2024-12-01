@extends('layouts.app')

@section('title', 'Data klasifikasi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Kalsifikasi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Kalsifikasi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            {{-- <div class="card">
                <div class="card-header mb-3" >
                    <h3 class="card-title">Data Kategori</h3>
                    <div class="float-right">
                        <a href="{{ url('admin/addkategori/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah
                        </a>
                    </div>
                </div> --}}

      
                <!-- /.card-header -->
                <div class="card-body">
                    {{-- <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Kategori</th>
                                <th>Nama Kategori</th>
                                <th>Nama Sub Kategori</th>
                                <th class="text-center" width="150">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($klasifikasis as $klasifikasi)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $klasifikasi->kode_klasifikasi }}</td>
                                <td>{{ $klasifikasi->nama }}</td>
                                <td>@if ($klasifikasi->subklasifikasi)
                                    @foreach($klasifikasi->subklasifikasi as $item)
                                    {{ $item->nama }}, <br>
                                    @endforeach
                                @else
                                    tidak
                                @endif</td>
                                <td data-toggle="modal" data-target="#modal-qrcode-{{ $klasifikasi->id }}"
                                    style="text-align: center;">
                                    <div style="display: inline-block;">
                                        {!! DNS2D::getBarcodeHTML("$klasifikasi->qrcode_klasifikasi", 'QRCODE', 1, 1) !!}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('admin/klasifikasi/' . $klasifikasi->id . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ url('admin/klasifikasi/' . $klasifikasi->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-hapus-{{ $klasifikasi->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                                <div class="modal fade" id="modal-hapus-{{ $klasifikasi->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus klasifikasi</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus klasifikasi <strong>{{ $klasifikasi->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/klasifikasi/' . $klasifikasi->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal-qrcode-{{ $klasifikasi->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$klasifikasi->qrcode_klasifikasi", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/klasifikasi/cetak-pdf/' . $klasifikasi->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table> --}}

                    <div class="row">
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h3></h3>
              
                              <p><strong>DATA KATEGORI</strong></p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('admin/addkategori') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h3></h3>
              
                              <p>DATA SUB KATEGORI</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('admin/subklasifikasi') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h3></h3>
              
                              <p>DATA SUB KATEGORI 1</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('admin/addsub') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                    </div>
                </div>
               
                <!-- /.card-body -->
            </div>
        </div>
    </section>

<script>
$(document).ready(function() {
    function onChangeSelect(url, id, name) {
  // send ajax request to get the cities of the selected province and append to the select tag
  $.ajax({
    url: url,
    type: 'GET',
    data: {
      id: id
    },
    success: function (data) {
      $('#' + name).empty();
      $('#' + name).append('<option>==Pilih Salah Satu==</option>');
      $.each(data, function (key, value) {
        $('#' + name).append('<option value="' + key + '">' + value + '</option>');
      });
    }
  });
}

$("#kategori").change(function(){
    var id = $(this).val();
    var url = "{{URL::to('kategori-dropdown')}}";
    var name = "sub";
    onChangeSelect(url, id, name);

});


$('#kategori').select2({
        theme: "bootstrap-5",
        width: function() {
            return $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style';
        },
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });
    $('#sub').select2({
        theme: "bootstrap-5",
        width: function() {
            return $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style';
        },
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

});

</script>

@endsection