<!-- resources/views/toko_banjaran/partials/produk_list.blade.php -->
@foreach ($produks as $index => $item)
@php
    $tokobanjaran = $item->tokobanjaran->first();
    $stok = $item->stok_tokobanjaran->sum('jumlah');
@endphp
<tr class="pilih-btn hidden"
    data-id="{{ $item->id }}"
    data-kode="{{ $item->kode_produk }}"
    data-kodel="{{ $item->kode_lama }}"
    data-catatan="{{ $item->catatanproduk }}"
    data-nama="{{ $item->nama_produk }}"
    data-member="{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}"
    data-diskonmember="{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}"
    data-nonmember="{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}"
    data-diskonnonmember="{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}"
    data-stok = "{{ $stok }}">

    <td class="text-center">{{ $index + 1 }}</td>
    <td hidden>{{ $item->kode_produk }}</td>
    <td>{{ $item->kode_lama }}</td>
    <td>{{ $item->nama_produk }}</td>
    <td><span class="member_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}</span></td>
    <td><span class="member_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}</span></td>
    <td><span class="non_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}</span></td>
    <td><span class="non_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}</span></td>
    <td>{{ $stok }}</td>   
    <td hidden>{{ $item->qrcode_produk }}</td>
</tr>
@endforeach
