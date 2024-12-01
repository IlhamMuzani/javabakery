@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data produk</li>
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
                <div class="card-header">
                    <h3 class="card-title">Data produk</h3>
            
                </div>
                <!-- /.card-header -->
                <div class="card-body mb-3">
                    <form method="GET" action="{{ url('toko_cilacap/produk') }}" class="form-inline float-right">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Cari produk...">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form><br><br>
                    <table id="data" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Qrcode</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produks as $produk)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + $produks->firstItem() - 1 }}</td>
                                    <td>{{ $produk->kode_lama }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ 'Rp. ' . number_format($produk->harga, 0, ',', '.') }}</td> <!-- Format harga -->
                                    <td>{{ $produk->satuan }}</td>
                                    
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $produk->id }}" style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML("$produk->qrcode_produk", 'QRCODE', 2, 2) !!}
                                        </div>
                                    </td>
                            
                                </tr>
                                <!-- Modal Hapus -->
                                <div class="modal fade" id="modal-hapus-{{ $produk->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus produk</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin hapus produk <strong> {{ $produk->nama_produk }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                <form action="{{ url('/produk/' . $produk->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal QR Code -->
                                <div class="modal fade" id="modal-qrcode-{{ $produk->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML("$produk->qrcode_produk", 'QRCODE', 15, 15) !!}
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                    <a href="{{ route('produk.print', $produk->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- <!-- Pagination Links -->
                    <div class="pagination">
                        {{ $produks->appends(['search' => $search])->links() }}
                    </div>  --}}
                </div>
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
