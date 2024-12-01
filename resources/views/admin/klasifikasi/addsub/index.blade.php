@extends('layouts.app')

@section('title', 'Data klasifikasi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Subklassifikasi 1</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/klasifikasi') }}">Klaisifikasi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            </script>
            @endif
            <div class="card">
                <div class="card-header mb-3" >
                    <h3 class="card-title">Data Subklasifikasi 1</h3>
                    <div class="float-right">
                        <a href="{{ url('admin/addsub/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                   
                    </div>
                   
                </div>

                
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Kategori</th>
                                <th>Nama Sub Kategori</th>
                                <th>Nama Sub Kategori 1</th>
                                {{-- <th>Qrcode Kategori</th> --}}
                                <th class="text-center" width="150">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subsubs as $subsub)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    @if ($subsub->subklasifikasi->klasifikasi)
                                        {{ $subsub->subklasifikasi->klasifikasi->nama }}
                                    @else
                                        tidak
                                    @endif
                                </td>
                                <td>
                                    @if ($subsub->subklasifikasi)
                                        {{ $subsub->subklasifikasi->nama }}
                                    @else
                                        tidak
                                    @endif
                                </td>
                                <td>{{ $subsub->nama }}</td>
                       
                                <td class="text-center">
                                    <a href="{{ url('admin/subklasifikasi/' . $subsub->id . '/edit') }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                               

                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-hapus-{{ $subsub->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                                <div class="modal fade" id="modal-hapus-{{ $subsub->id }}">
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
                                                <p>Yakin hapus klasifikasi <strong>{{ $subsub->nama }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ url('admin/addsub/' . $subsub->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal-qrcode-{{ $subsub->id }}">
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
                                               
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/klasifikasi/cetak-pdf/' . $subsub->id) }}"
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
                    </table>
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
