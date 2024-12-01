@extends('layouts.app')

@section('title', 'Data Deposit')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquery Data Deposit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Data Deposit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
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

                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                        {{-- <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="status" name="status">
                                <option value="">- Semua Status -</option>
                                <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>Posting</option>
                                <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>Unpost</option>
                            </select>
                            <label for="status">(Pilih Status)</label>
                        </div> --}}
                        <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="status_pelunasan" name="status_pelunasan">
                                <option value="">- Semua Pelunasan -</option>
                                <option value="diambil" {{ Request::get('status_pelunasan') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                <option value="belum_diambil" {{ Request::get('status_pelunasan') == 'belum_diambil' ? 'selected' : '' }}>Belum Diambil</option>
                            </select>
                            <label for="status_pelunasan">(Status Pelunasan)</label>
                        </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_pemesanan" name="tanggal_pemesanan" type="date"
                                    value="{{ Request::get('tanggal_pemesanan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_pemesanan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                           
                            
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                
                            </div>
                        </div>
                    </form>
                    <table id="datatables1" class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Cabang</th>
                                <th>Kode Deposit</th>
                                <th>Nama Pelanggan</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Nominal</th>
                                <th>Status</th> <!-- Tambahkan kolom Status -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inquery as $deposit)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $deposit->pemesananproduk->toko->nama_toko ?? 'Tidak Ada Toko' }}</td> <!-- Akses nama_toko -->
                                    <td>{{ $deposit->kode_dppemesanan }}</td>
                                    <td>{{ $deposit->pemesananproduk->nama_pelanggan ?? 'Tidak Ada Nama' }}</td> 
                                    <td>{{ $deposit->pemesananproduk->telp ?? 'Tidak Ada No HP' }}</td> 
                                    <td>{{ $deposit->pemesananproduk->alamat ?? 'Tidak Ada Alamat' }}</td> 
                                    <td>{{ 'Rp ' . number_format($deposit->dp_pemesanan, 0, ',', '.') }}</td>
                                    <td>
                                        @if($deposit->pelunasan)
                                            <span class="badge badge-success">Diambil</span>
                                        @else
                                            <span class="badge badge-warning">Belum Diambil</span>
                                        @endif
                                    </td> <!-- Tampilkan status diambil/belum diambil -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
               
                </div>
            </div>
        </div>
    </section>


    <script>
        var tanggalAwal = document.getElementById('tanggal_pemesanan');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            };
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });
        var form = document.getElementById('form-action')

        function cari() {
            form.action = "{{ url('admin/inquery_deposit') }}";
            form.submit();
        }

    </script>
@endsection
