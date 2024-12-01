
@extends('layouts.app')

@section('title', 'Harga Jual')

@section('content')
<div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            document.getElementById("loadingSpinner").style.display = "none";
            document.getElementById("mainContent").style.display = "block";
            document.getElementById("mainContentSection").style.display = "block";
        }, 10); // Adjust the delay time as needed
    });
</script>

    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquery Perubahan Harga</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Harga Jual</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        {{-- <div class="row">
                            <div class="col-md-3">
                                <label for="toko" class="form-label">Pilih Toko:</label>
                                <select class="form-control" id="toko" name="toko">
                                    <option value="tokobenjaran" @if(request()->input('toko') == 'tokobenjaran') selected @endif>Toko Benjaran</option>
                                    <option value="tokotegal" @if(request()->input('toko') == 'tokotegal') selected @endif>Toko Tegal</option>
                                    <option value="tokoslawi" @if(request()->input('toko', 'tokoslawi') == 'tokoslawi') selected @endif>Toko Slawi</option>
                                    <option value="tokopemalang" @if(request()->input('toko') == 'tokopemalang') selected @endif>Toko Pemalang</option>
                                    <option value="tokobumiayu" @if(request()->input('toko') == 'tokobumiayu') selected @endif>Toko Bumiayu</option>
                                    <option value="tokocilacap" @if(request()->input('toko') == 'tokocilacap') selected @endif>Toko Cilacap</option>
                                </select>
                            </div>
                        
                        </div> --}}
                        {{-- <div class="float-right">
                            <button class="btn btn-primary" onclick="printPdf()">Cetak PDF</button>
                        </div> --}}

                        <form method="GET" id="form-action">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <select class="form-control" id="toko" name="toko">
                                        <option value="tokobanjaran" @if(request()->input('toko') == 'tokobanjaran') selected @endif>Toko Benjaran</option>
                                        <option value="tokotegal" @if(request()->input('toko') == 'tokotegal') selected @endif>Toko Tegal</option>
                                        <option value="tokoslawi" @if(request()->input('toko', 'tokoslawi') == 'tokoslawi') selected @endif>Toko Slawi</option>
                                        <option value="tokopemalang" @if(request()->input('toko') == 'tokopemalang') selected @endif>Toko Pemalang</option>
                                        <option value="tokobumiayu" @if(request()->input('toko') == 'tokobumiayu') selected @endif>Toko Bumiayu</option>
                                        {{-- <option value="tokocilacap" @if(request()->input('toko') == 'tokocilacap') selected @endif>Toko Cilacap</option> --}}
                                    </select>
                                    <label for="toko" class="form-label">Pilih Toko:</label>

                                </div>
                            {{-- <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="status" name="status">
                                    <option value="">- Semua Status -</option>
                                    <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>Posting</option>
                                    <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>Unpost</option>
                                </select>
                                <label for="status">(Pilih Status)</label>
                            </div> --}}
                                <div class="col-md-3 mb-3">
                                    <input class="form-control" id="tanggal_perubahan" name="tanggal_perubahan" type="date"
                                        value="{{ Request::get('tanggal_perubahan') }}" max="{{ date('Y-m-d') }}" />
                                    <label for="tanggal_perubahan">(Dari Tanggal)</label>
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
                    </div>
                
                    {{-- Tampilkan Tabel Tokoslawi --}}
                    <div id="tokoslawiTable" @if(request()->input('toko', 'tokoslawi') != 'tokoslawi') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokoslawi->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Perubahan Harga Toko Slawi</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Slawi</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokoslawi->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokoslawi->first()->member_harga_slw;
                                                        $nonMemberHarga = $item->tokoslawi->first()->non_harga_slw;
                                                        $hargaAwal = $item->tokoslawi->first()->harga_awal;
                                                        $memberDiskon = $item->tokoslawi->first()->member_diskon_slw;
                                                        $nonMemberDiskon = $item->tokoslawi->first()->non_diskon_slw;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr class="dropdown">
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>

                                                            <td class="text-center">
                                                                {{-- @if ($item->status == 'posting') --}}
                                                                    <button type="button" class="btn btn-success btn-sm">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                {{-- @endif --}}
                                                                {{-- @if ($item->status == 'unpost') --}}
                                                              
                                                                {{-- @endif --}}
                                                             
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    {{-- @if ($item->status == 'unpost') --}}
                                                                       
                                                                            <a class="dropdown-item posting-btn"
                                                                                data-memo-id="#">Posting</a>
                                                                     
                                                                            <a class="dropdown-item"
                                                                                href="#">Update</a>
                                                                        
                                                                            <a class="dropdown-item"
                                                                            href="#">Show</a>
                                                                            {{-- @endif --}}
                                                                    {{-- @if ($item->status == 'posting') --}}
                                                                            <a class="dropdown-item unpost-btn"
                                                                                data-memo-id="{{ $item->id }}">Unpost</a>
                                                                            <a class="dropdown-item"
                                                                            href="#">Show</a>
                                                                    {{-- @endif --}}
                                                                   
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                        
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                            </div>
                        @endif
                    </div>

                    {{-- Tampilkan Tabel Tokobenjaran --}}
                    <div id="tokobenjaranTable" @if(request()->input('toko', 'tokobenjaran') != 'tokobenjaran') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokobenjaran->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Perubahan Harga Toko Benjaran</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Benjaran</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokobenjaran->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokobenjaran->first()->member_harga_bnjr;
                                                        $nonMemberHarga = $item->tokobenjaran->first()->non_harga_bnjr;
                                                        $hargaAwal = $item->tokobenjaran->first()->harga_awal;
                                                        $memberDiskon = $item->tokobenjaran->first()->member_diskon_bnjr;
                                                        $nonMemberDiskon = $item->tokobenjaran->first()->non_diskon_bnjr;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                            </div>
                        @endif
                    </div>

                    {{-- Tampilkan Tabel Tokotegal --}}
                    <div id="tokotegalTable" @if(request()->input('toko', 'tokotegal') != 'tokotegal') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokotegal->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Perubahan Harga Toko Tegal</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Tegal</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokotegal->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                                                        $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                                                        $hargaAwal = $item->tokotegal->first()->harga_awal;
                                                        $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                                                        $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                            </div>
                        @endif
                    </div>

                    {{-- Tampilkan Tabel TokoPemlaang --}}
                    <div id="tokopemalangTable" @if(request()->input('toko', 'tokopemalang') != 'tokopemalang') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokopemalang->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Perubahan Harga Toko Pemalang</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Tegal</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokopemalang->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                                                        $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                                                        $hargaAwal = $item->tokopemalang->first()->harga_awal;
                                                        $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                                                        $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Toko Pemalang yang diperbarui hari ini.
                            </div>
                        @endif
                    </div>

                    {{-- Tampilkan Tabel TokoBumiayu --}}
                    <div id="tokobumiayuTable" @if(request()->input('toko', 'tokobumiayu') != 'tokobumiayu') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokobumiayu->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Perubahan Harga Toko Bumiayu</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Bumiayu</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokobumiayu->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                                                        $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                                                        $hargaAwal = $item->tokobumiayu->first()->harga_awal;
                                                        $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                                                        $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Toko Pemalang yang diperbarui hari ini.
                            </div>
                        @endif
                    </div>

                    {{-- Tampilkan Tabel TokoCilacap --}}
                    {{-- <div id="tokocilacapTable" @if(request()->input('toko', 'tokocilacap') != 'tokocilacap') style="display: none;" @endif>
                        @if($produk->filter(function($item) {
                                return $item->tokocilacap->isNotEmpty();
                            })->isNotEmpty())
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Data Harga Jual tokocilacap yang Diperbarui Hari Ini</h3>
                                </div>
                                <div class="card-body">
                            
                                    <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode produk</th>
                                                <th>Nama produk</th>
                                                <th>Harga produk awal</th>
                                                <th colspan="4" style="text-align: center;">Toko Cilacap</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Member</th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;"></th>
                                                <th style="text-align: center;">Non Member</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                                <th style="text-align: center;">Harga</th>
                                                <th style="text-align: center;">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produk as $index => $item)
                                                @if($item->tokocilacap->isNotEmpty())
                                                    @php
                                                        $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                                                        $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                                                        $hargaAwal = $item->tokocilacap->first()->harga_awal;
                                                        $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                                                        $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                                        
                                                        // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                        $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                                    ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                                    @endphp
                                        
                                                    @if($isChanged)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ $item->kode_produk }}</td>
                                                            <td>{{ $item->nama_produk }}</td>
                                                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                            <td style="text-align: center;">
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach 
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Tidak ada data Harga Jual Toko Cilacap yang diperbarui hari ini.
                            </div>
                        @endif
                    </div> --}}

                </div>
            </div>
        </div>
    </section>

    <script>
        // Script untuk menampilkan tabel yang sesuai berdasarkan pilihan pengguna
        document.addEventListener('DOMContentLoaded', function() {
            var toko = document.getElementById('toko').value;
            showTable(toko);
        });

        document.getElementById('toko').addEventListener('change', function() {
            var toko = this.value;
            showTable(toko);
        });

        function showTable(toko) {
            if (toko === 'tokoslawi') {
                document.getElementById('tokoslawiTable').style.display = 'block';
                document.getElementById('tokobenjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            } else if (toko === 'tokobenjaran') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'block';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokotegal') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'block';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokopemalang') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'block';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokobumiayu') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'block';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokocilacap') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'block';
            }
        
        }
        function printPdf() {
        var toko = document.getElementById('toko').value;
        var url = '{{ route("cetak.pdf") }}' + '?toko=' + toko;
        window.open(url, '_blank');
    }
    </script>

<script>
    $(document).ready(function() {
        $('tbody tr.dropdown').click(function(e) {
            // Memeriksa apakah yang diklik adalah checkbox
            if ($(e.target).is('input[type="checkbox"]')) {
                return; // Jika ya, hentikan eksekusi
            }

            // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
            $('tr.dropdown').removeClass('selected').css('background-color', '');

            // Menambahkan kelas 'selected' ke baris yang dipilih dan mengubah warna latar belakangnya
            $(this).addClass('selected').css('background-color', '#b0b0b0');

            // Menyembunyikan dropdown pada baris lain yang tidak dipilih
            $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

            // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
        });

        $('tbody tr.dropdown').contextmenu(function(e) {
            // Memeriksa apakah baris ini memiliki kelas 'selected'
            if ($(this).hasClass('selected')) {
                // Menampilkan dropdown saat klik kanan
                var dropdownMenu = $(this).find('.dropdown-menu');
                dropdownMenu.show();

                // Mendapatkan posisi td yang diklik
                var clickedTd = $(e.target).closest('td');
                var tdPosition = clickedTd.position();

                // Menyusun posisi dropdown relatif terhadap td yang di klik
                dropdownMenu.css({
                    'position': 'absolute',
                    'top': tdPosition.top + clickedTd
                        .height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                    'left': tdPosition
                        .left // Menempatkan dropdown di sebelah kiri td yang di klik
                });

                // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
                e.stopPropagation();
                e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
            }
        });

        // Menyembunyikan dropdown saat klik di tempat lain
        $(document).click(function() {
            $('.dropdown-menu').hide();
            $('tr.dropdown').removeClass('selected').css('background-color',
                ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
        });
    });
</script>
@endsection


