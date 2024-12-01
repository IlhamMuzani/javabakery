@extends('layouts.app')

@section('title', 'Data Deposit')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Data Deposit Global</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Data Deposit Global</li>
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
                <div class="card-header">
                    <div class="float-right">
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="global" {{ old('kategori1') == 'global' ? 'selected' : '' }}>Laporan Deposit Global</option>
                            <option value="rinci" {{ old('kategori1') == 'rinci' ? 'selected' : '' }}>Laporan Deposit Rinci</option>
                            <option value="saldo" {{ old('kategori1') == 'saldo' ? 'selected' : '' }}>Saldo Deposit</option>
                        </select>
                    </div>
   
                <h3 class="card-title">Laporan Deposit </h3>
            </div>
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="filter_tanggal" name="filter_tanggal">
                                    <option value="">- Pilih Filter Tanggal -</option>
                                    <option value="tanggal_pemesanan" {{ Request::get('filter_tanggal') == 'tanggal_pemesanan' ? 'selected' : '' }}>Tanggal Pemesanan</option>
                                    <option value="tanggal_kirim" {{ Request::get('filter_tanggal') == 'tanggal_kirim' ? 'selected' : '' }}>Tanggal Ambil</option>
                                </select>
                                <label for="filter_tanggal">(Filter Tanggal)</label>
                            </div>
                            
                        <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="status_pelunasan" name="status_pelunasan">
                                <option value="">- Semua Pelunasan -</option>
                                <option value="diambil" {{ Request::get('status_pelunasan') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                <option value="belum_diambil" {{ Request::get('status_pelunasan') == 'belum_diambil' ? 'selected' : '' }}>Belum Diambil</option>
                            </select>
                            <label for="status_pelunasan">(Status Pelunasan)</label>
                        </div>
                       
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_kirim" name="tanggal_kirim" type="date"
                                    value="{{ Request::get('tanggal_kirim') }}"  />
                                <label for="tanggal_kirim">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}"  />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                           
                            
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                                
                            </div>
                        </div>
                    </form>
                    <table id="datatables1" class="table table-bordered table-striped table-hover" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Cabang</th>
                                <th>Tanggal</th>
                                <th>Kode Pemesanan</th>
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
                                    <td>{{ $deposit->pemesananproduk->toko->nama_toko ?? 'Tidak Ada Toko' }}</td>
                                    <td>
                                        @if (Request::get('filter_tanggal') == 'tanggal_pemesanan')
                                            {{ $deposit->pemesananproduk->tanggal_pemesanan }}
                                        @elseif (Request::get('filter_tanggal') == 'tanggal_kirim')
                                            {{ $deposit->pemesananproduk->tanggal_kirim }}
                                        @else
                                            {{ 'Tanggal Tidak Ditemukan' }} <!-- Optional: Default message if no filter is selected -->
                                        @endif
                                    </td>
                                    <td>{{ $deposit->pemesananproduk->kode_pemesanan }}</td>
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </section>


    <script>
        var tanggalAwal = document.getElementById('tanggal_kirim');
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
            form.action = "{{ url('toko_bumiayu/laporan_depositbumiayu') }}";
            form.submit();
        }

    </script>

    <script>
        document.getElementById('kategori1').addEventListener('change', function() {
            var selectedValue = this.value;

            if (selectedValue === 'global') {
                window.location.href = "{{ url('toko_bumiayu/laporan_depositbumiayu') }}";
            } else if (selectedValue === 'rinci') {
                window.location.href = "{{ url('toko_bumiayu/indexrinci') }}";
            } else if (selectedValue === 'saldo') {
                window.location.href = "{{ url('toko_bumiayu/indexsaldo') }}";
            }
        });
    </script>

<script>
    function printReport() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('toko_bumiayu/printReportdeposit') }}";
    form.target = "_blank";
    form.submit();
}

</script>
@endsection
