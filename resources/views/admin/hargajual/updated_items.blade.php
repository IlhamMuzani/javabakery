@extends('layouts.app')

@section('title', 'Updated Items')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Harga Jual yang Diperbarui Hari Ini</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Updated Items</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Harga Jual yang Diperbarui Hari Ini</h3>
                </div>
                <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th colspan="4" style="text-align: center;">Toko Slawi</th>
                                <th colspan="4" style="text-align: center;">Toko Benjaran</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th style="text-align: center;">Member</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Non Member</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Member</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Non Member</th>
                                <th style="text-align: center;">Diskon (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td style="text-align: center;">{{ $item->tokoslawi->first()->member_harga_slw ?? $item->harga }}</td>
                                    <td>{{ $item->tokoslawi->first()->member_diskon_slw ?? $item->diskon }}</td>
                                    <td style="text-align: center;">{{ $item->tokoslawi->first()->non_harga_slw ?? $item->harga }}</td>
                                    <td>{{ $item->tokoslawi->first()->non_diskon_slw ?? $item->diskon }}</td>
                                    <td style="text-align: center;">{{ $item->tokobenjaran->first()->member_harga_bnjr ?? $item->harga }}</td>
                                    <td>{{ $item->tokobenjaran->first()->member_diskon_bnjr ?? $item->diskon }}</td>
                                    <td style="text-align: center;">{{ $item->tokobenjaran->first()->non_harga_bnjr ?? $item->harga }}</td>
                                    <td>{{ $item->tokobenjaran->first()->non_diskon_bnjr ?? $item->diskon }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
