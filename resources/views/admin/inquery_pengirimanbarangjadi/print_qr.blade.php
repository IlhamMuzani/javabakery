@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h4>Detail Pengiriman: {{ $pengiriman->kode_pengiriman }}</h4> --}}
    <table id="datatables66" class="table table-bordered" style="font-size: 13px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Pengiriman</th>
                <th>Cabang</th>
                <th>Tanggal Pengiriman</th>
                <th>Tanggal Terima</th>
                <th>Status</th>
              
            </tr>
        </thead>
        <tbody>
            @foreach ($stokBarangJadi as $kodeInput => $stokBarangJadiItems)
            @php
                $firstItem = $stokBarangJadiItems->first();
            @endphp
                <tr class="dropdown" data-permintaan-id="{{ $firstItem->id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $firstItem->kode_pengiriman }}</td>
                <td>{{ $firstItem->toko->nama_toko ?? 'Toko Tidak Ditemukan' }}</td> <!-- Memanggil relasi toko -->
                <td>{{ \Carbon\Carbon::parse($firstItem->tanggal_pengiriman)->format('d/m/Y H:i') }} </td>
                <td>{{ \Carbon\Carbon::parse($firstItem->tanggal_terima)->format('d/m/Y H:i') }} </td>
                  
                <td class="text-center">
                    @if ($firstItem->status == 'posting')
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                    @if ($firstItem->status == 'unpost')
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                 
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @if ($firstItem->status == 'unpost')
                                <a class="dropdown-item"
                                href="{{ url('admin/inquery_pengirimanbarangjadi/' . $firstItem->id . '/edit') }}">Update</a>
                               
                                <a class="dropdown-item"
                                href="{{ url('/admin/inquery_pengirimanbarangjadi/' . $firstItem->id ) }}">Show</a>

                                <a class="dropdown-item"
                                href="{{ route('inquery_pengirimanbarangjadi.print_qr', $firstItem->id) }}">Print QR</a>

                                @endif
                        @if ($firstItem->status == 'posting')
                                <a class="dropdown-item unpost-btn"
                                    data-memo-id="{{ $firstItem->id }}">Unpost</a>
                                <a class="dropdown-item"
                                href="{{ url('admin/inquery_pengirimanbarangjadi/' . $firstItem->id ) }}">Show</a>
                        @endif
                       
                    </div>
                </td>
            </tr>
            <form id="form-cetak-banyak" method="POST" action="{{ route('inquery_pengirimanbarangjadi.cetak_banyak_barcode') }}" target="_blank">
                @csrf
                <tr class="permintaan-details" id="details-{{ $firstItem->id }}" style="display: none;">
                    <td colspan="5">
                        <table class="table table-bordered" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>
                                        No
                                        <input type="checkbox" id="select-all"> <!-- Checkbox untuk menandai semua row -->
                                    </th>
                                    <th>Divisi</th>
                                    <th>Kode Produk</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stokBarangJadiItems as $detail)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                        <input type="checkbox" name="selected_items[]" value="{{ $detail->produk->id }}" class="row-checkbox">
                                    </td>
                                    <td>{{ $detail->produk->klasifikasi->nama }}</td>
                                    <td>{{ $detail->produk->kode_lama }}</td>
                                    <td>{{ $detail->produk->nama_produk }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>
                                        <a href="{{ route('inquery_pengirimanbarangjadi.cetak_barcode', $detail->produk->id) }}" class="btn btn-primary btn-sm" target="_blank" onclick="openPrintDialog(event)">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            
                {{-- <button type="button" class="btn btn-primary" id="cetak-terpilih">Cetak Terpilih</button> --}}
            </form>
            
     
        @endforeach
        </tbody>
    </table> 
</div>
@endsection
