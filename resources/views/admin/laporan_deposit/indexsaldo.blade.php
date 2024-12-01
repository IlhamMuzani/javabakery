@extends('layouts.app')

@section('title', 'Data Deposit')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Saldo Deposit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Saldo Deposit</li>
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
                <h3 class="card-title">Saldo Deposit</h3>
            </div>

            <div class="card-body">
                <form method="GET" id="form-action">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="toko" name="toko_id">
                                <option value="">- Semua Toko -</option>
                                @foreach ($tokos as $toko)
                                    <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                @endforeach
                            </select>
                            <label for="toko">(Pilih Toko)</label>
                        </div>

                        <div class="col-md-3 mb-3">
                            <button type="submit" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <button type="button" class="btn btn-primary btn-block" onclick="printReport()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Saldo Deposit Toko</h3>
            </div>
            <div class="card-body">

                @foreach ($saldoPerToko as $tokoId => $saldo)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            @php
                                $toko = $tokos->find($tokoId);
                            @endphp

                            @if ($toko)
                                <strong>Cabang:</strong> {{ $toko->nama_toko }}
                            @else
                                <strong>Cabang:</strong> Toko tidak ditemukan
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Saldo:</strong> {{ formatRupiah($saldo) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <em>Terbilang: {{ terbilang($saldo) }} Rupiah</em>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'global') {
            window.location.href = "{{ url('admin/laporan_deposit') }}";
        } else if (selectedValue === 'rinci') {
            window.location.href = "{{ url('admin/indexrinci') }}";
        } else if (selectedValue === 'saldo') {
            window.location.href = "{{ url('admin/saldo') }}";
        }
    });

    function printReport() {
        const form = document.getElementById('form-action');
        form.action = "{{ url('admin/printReportsaldo') }}";
        form.target = "_blank";
        form.submit();
    }
</script>

@endsection
