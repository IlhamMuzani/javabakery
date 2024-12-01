<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
        }
        .logo img {
            width: 150px;
            height: 77px;
            margin-top: -20px;
            
        }
        .header {
            text-align: center;
            margin-top: -50px ;
        }
        .header span {
            display: block;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 40px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .tanggal1 {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }

            table:last-of-type {
             margin-bottom: 140px; /* Atur jarak antara tabel terakhir dan signature */
            }
            .signature-container {
                margin-top: 60px;
                text-align: center;
            }
            .signature {
                display: inline-block;
                margin: 0 50px;
                text-align: center;
            }
            .signature p {
                margin: 0;
            }

    </style>
</head>
<body>
   
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div class="header">
            <div>
                <span class="title">JAVA BAKERY</span>
                <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span>
                <span class="contact">Telp / Fax, Email :</span>
            </div>
            <hr class="divider">
            <hr class="divider">
        </div>

    <div class="change-header">SURAT PERUBAHAN HARGA</div>

    <div class="tanggal">
        Tanggal : {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </div>
    {{-- <div class="tanggal1">
        No surat : -
    </div> --}}

    @php
        $tokoNames = [
            'tokoslawi' => 'Toko Slawi',
            'tokobenjaran' => 'Toko Benjaran',
            'tokotegal' => 'Toko Tegal',
            'tokopemalang' => 'Toko Pemalang',
            'tokobumiayu' => 'Toko Bumiayu',
            'tokocilacap' => 'Toko Cilacap'
        ];
    @endphp

    @foreach ($tokoNames as $toko => $tokoName)
        @php
            $filteredData = $produk->filter(function ($item) use ($toko) {
                return $item->$toko->isNotEmpty();
            });
            $isTokoChanged = false;
        @endphp

        @if ($filteredData->isNotEmpty())
            @foreach ($filteredData as $index => $item)
                @php
                    $hargaAwal = $item->harga;
                    $diskonAwal = 0; // asumsi diskon awal adalah 0
                    $memberHarga = null;
                    $nonMemberHarga = null;
                    $memberDiskon = null;
                    $nonMemberDiskon = null;

                    if ($toko == 'tokoslawi') {
                        $memberHarga = $item->tokoslawi->first()->member_harga_slw;
                        $nonMemberHarga = $item->tokoslawi->first()->non_harga_slw;
                        $memberDiskon = $item->tokoslawi->first()->member_diskon_slw;
                        $nonMemberDiskon = $item->tokoslawi->first()->non_diskon_slw;
                    } elseif ($toko == 'tokobenjaran') {
                        $memberHarga = $item->tokobenjaran->first()->member_harga_bnjr;
                        $nonMemberHarga = $item->tokobenjaran->first()->non_harga_bnjr;
                        $memberDiskon = $item->tokobenjaran->first()->member_diskon_bnjr;
                        $nonMemberDiskon = $item->tokobenjaran->first()->non_diskon_bnjr;
                    } elseif ($toko == 'tokotegal') {
                        $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                        $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                        $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                        $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                    } elseif ($toko == 'tokopemalang') {
                        $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                        $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                        $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                        $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                    } elseif ($toko == 'tokobumiayu') {
                        $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                        $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                        $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                        $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                    } elseif ($toko == 'tokocilacap') {
                        $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                        $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                        $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                        $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                    }

                    if ($memberHarga != $hargaAwal || $nonMemberHarga != $hargaAwal || $memberDiskon != $diskonAwal || $nonMemberDiskon != $diskonAwal) {
                        $isTokoChanged = true;
                        break;
                    }
                @endphp
            @endforeach

            @if($isTokoChanged)
                <div class="section-title {{ $toko }}">{{ $tokoName }}</div>
                <table class="t1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode produk</th>
                            <th>Nama produk</th>
                            <th colspan="8" style="text-align: center;"></th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="4" style="text-align: center;">Member</th>
                            <th colspan="4" style="text-align: center;">Non Member</th>

                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2" style="text-align: center;">Harga</th>
                            <th colspan="2" style="text-align: center;">Diskon</th>
                            <th colspan="2" style="text-align: center;">Harga</th>
                            <th colspan="2" style="text-align: center;">Diskon</th>
                          
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: center;">Harga lama</th>
                            <th style="text-align: center;">Harga Baru</th>
                            <th style="text-align: center;">diskon lama</th>
                            <th style="text-align: center;">diskon Baru</th>
                            <th style="text-align: center;">Harga lama</th>
                            <th style="text-align: center;">Harga Baru</th>
                            <th style="text-align: center;">diskon lama</th>
                            <th style="text-align: center;">diskon Baru</th>
                        </tr>
                   
                    </thead>
                    <tbody>
                        @foreach ($filteredData as $index => $item)
                            @php
                                $hargaAwal = $item->harga;
                                $diskonAwal = 0; // asumsi diskon awal adalah 0
                                $memberHarga = null;
                                $nonMemberHarga = null;
                                $memberDiskon = null;
                                $nonMemberDiskon = null;

                                if ($toko == 'tokoslawi') {
                                    $memberHarga = $item->tokoslawi->first()->member_harga_slw;
                                    $nonMemberHarga = $item->tokoslawi->first()->non_harga_slw;
                                    $memberDiskon = $item->tokoslawi->first()->member_diskon_slw;
                                    $nonMemberDiskon = $item->tokoslawi->first()->non_diskon_slw;
                                } elseif ($toko == 'tokobenjaran') {
                                    $memberHarga = $item->tokobenjaran->first()->member_harga_bnjr;
                                    $nonMemberHarga = $item->tokobenjaran->first()->non_harga_bnjr;
                                    $memberDiskon = $item->tokobenjaran->first()->member_diskon_bnjr;
                                    $nonMemberDiskon = $item->tokobenjaran->first()->non_diskon_bnjr;
                                } elseif ($toko == 'tokotegal') {
                                    $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                                    $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                                    $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                                    $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                                } elseif ($toko == 'tokopemalang') {
                                    $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                                    $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                                    $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                                    $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                                } elseif ($toko == 'tokobumiayu') {
                                    $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                                    $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                                    $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                                    $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                                } elseif ($toko == 'tokocilacap') {
                                    $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                                    $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                                    $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                                    $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                                }

                                $isChanged = false;

                                if ($memberHarga != $hargaAwal || $nonMemberHarga != $hargaAwal || $memberDiskon != $diskonAwal || $nonMemberDiskon != $diskonAwal) {
                                    $isChanged = true;
                                }
                            @endphp

                            @if($isChanged)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td > {{number_format($item->harga, 0, ',', '.') }}</td>
                                    <td > {{number_format($memberHarga, 0, ',', '.') }}</td>
                                    {{-- <td>
                                        @if($memberHarga != $hargaAwal || $memberDiskon != $diskonAwal)
                                            {{number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td> --}}
                                    <td>{{ $diskonAwal }}</td>
                                    
                                    <td>
                                        @if($memberHarga != $hargaAwal || $memberDiskon != $diskonAwal)
                                            {{ $memberDiskon }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td > {{number_format($item->harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        @if($nonMemberHarga != $hargaAwal || $nonMemberDiskon != $diskonAwal)
                                            {{number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>{{ $diskonAwal }}</td>
                                    <td>
                                        @if($nonMemberHarga != $hargaAwal || $nonMemberDiskon != $diskonAwal)
                                            {{ $nonMemberDiskon }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    @endforeach

    <div class="signature-container">
        <div class="signature">
            <p>Mengetahui</p>
            <p>Pimpinan</p>
            <br><br><br>
            <p>__________________</p>
        </div>
        <div class="signature">
            <p>Mengetahui</p>
            <p>Finance</p>
            <br><br><br>
            <p>__________________</p>
        </div>
        <div class="signature">
            <p>Mengetahui</p>
            <p>Admin</p>
            <br><br><br>
            {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}
            <p>__________________</p>
        </div>
    </div>
</body>
</html>
