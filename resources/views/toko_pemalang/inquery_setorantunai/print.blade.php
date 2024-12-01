<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak penjualan Produk</title>
    <style>
        html,
            body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            /* margin: 0; */
            margin-left: -5;
            margin-top: 0;
            /* padding: 0; */
            padding-right: 450px;
            font-size: 10px;
            background-color: #fff;
        }
            .container {
            width: 70mm; /* Adjusted width */
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
        }

        .header {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px; /* Sesuaikan tinggi header sesuai kebutuhan */
         }

        .header .text {
            display: flex;
            flex-direction: column;
            align-items: center; /* Mengatur konten di dalam .text agar berada di tengah */
            text-align: center; /* Mengatur teks di dalam .text agar berada di tengah */
        }

        .header .text h1 {
            margin-top: 10px;
            margin-bottom: 0px;
            padding: 0;
            font-size: 16px;
            color: #0c0c0c;
            text-transform: uppercase;
        }

        .header .text p {
            margin: 2px ;
            font-size: 8px;
            margin-bottom: 2px;
        }
        .section {
            margin-bottom: 10px;
        }
        .section h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .section table th, .section table td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 8px;
        }
        
        .float-right {
            text-align: right;
            margin-top: 10px;
        }
        .float-right button {
            padding: 5px 10px;
            font-size: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            box-shadow: 0px 1px 3px rgba(0,0,0,0.1);
        }
        .float-right button:hover {
            background-color: #0056b3;
        }

    
        .divider {
            border: 0.5px solid;
            margin-top: -10px;
            margin-bottom: 2px;
            border-bottom: 2px solid #0f0e0e;
        }

        table.no-border, 
        table.no-border tr, 
        table.no-border td {
            border: none !important;
        }
        @media print {
    body {
        font-size: 10px;
        background-color: #fff;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 70mm; /* Sesuaikan dengan lebar kertas thermal */
        margin: 0 auto;
        border: none;
        padding: 0;
        box-shadow: none;
    }
    .header .logo img {
        max-width: 80px; /* Sesuaikan jika perlu */
        height: auto;
    }
    .section table {
        width: 100%;
        margin-top: 5px;
    }
    .section table th, .section table td {
        border: 1px solid #ccc;
        padding: 5px;
        font-size: 8px;
    }


    .float-right button {
        font-size: 10px;
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 3px;
        box-shadow: 0px 1px 3px rgba(0,0,0,0.1);
    }
    .float-right button:hover {
        background-color: #0056b3;
    }
 
    .divider {
        border: 0.5px solid;
        margin-top: 3px;
        margin-bottom: 1px;
        border-bottom: 1px solid #0f0e0e;
        
    }
    
    @page {
        size: 70mm auto; /* Sesuaikan dengan ukuran kertas thermal */
        margin: 0mm; /* Set margin ke 0 untuk semua sisi */
    }
}

    </style>

    
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="text">
                <h1>PT JAVA BAKERY FACTORY</h1>
                <p>Cabang : PEMALANG</p>
                <p>Jl. Hayam Wuruk No.7, Bendan, Kec. Pekalongan Bar., Kota Pekalongan, Jawa Tengah 51119</p>
            </div>
        </div>
        <hr class="divider">
        <hr class="divider">
        <div class="section">
            <h2>STRUK PENJUALAN TOKO</h2>
            {{-- <strong>Kasir:</strong> {{ $kasir ? $kasir : 'Semua Kasir' }} --}}
            <p style="text-align: right; font-size: 8px;">
            </p>
            
            <table class="no-border mb-1">
                <tr>
                    <td class="text-left"><strong>PENJUALAN KOTOR</strong></td>
                    
                    <td style="text-align: right">{{ number_format($setoranPenjualans->penjualan_kotor, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>DISKON PENJUALAN</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->diskon_penjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="border-top: 0.5px solid #000;"></td>
                </tr>
                <tr>
                    <td class="text-left"><strong>PENJUALAN BERSIH</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->penjualan_bersih, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>DEPOSIT KELUAR</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->deposit_keluar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>DEPOSIT MASUK</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->deposit_masuk, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="border-top: 0.5px solid #000;"></td>
                </tr>
                <tr>
                    <td class="text-left"><strong>TOTAL PENJUALAN</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->total_penjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>GO-BIZ</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->gobiz, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>EDC</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->mesin_edc, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>TRANSFER</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->transfer, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>QRIS</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->qris, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="border-top: 0.5px solid #000;"></td>
                </tr>
                <tr>
                    <td class="text-left"><strong>TOTAL SETORAN</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->total_setoran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-left"><strong>NOMINAL SETORAN</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->nominal_setoran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><hr style="border-top: 0.5px solid #000;"></td>
                </tr>
                <tr>
                    <td class="text-left"><strong>PLUS MINUS</strong></td>
                    <td style="text-align: right">{{ number_format($setoranPenjualans->plusminus, 0, ',', '.') }}</td>
                </tr>
            </table>
            
        </div>
        
        
    </body>
    </html>
    