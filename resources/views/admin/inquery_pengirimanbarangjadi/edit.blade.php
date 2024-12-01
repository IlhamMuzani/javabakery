@extends('layouts.app')

@section('title', 'Inquery Pengiman Barang')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengiriman Barang Jadi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
     
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            @if (session('erorrss'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('erorrss') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error_pelanggans') || session('error_pesanans'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @if (session('error_pelanggans'))
                        @foreach (session('error_pelanggans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                    @if (session('error_pesanans'))
                        @foreach (session('error_pesanans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                </div>
            @endif
            <form action="{{ url('admin/inquery_pengirimanbarangjadi/' . $pengiriman->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama">Nomor Pengiriman</label>
                                <input type="text" class="form-control" id="kode_pengiriman" name="kode_pengiriman"
                                    value="{{ old('kode_pengiriman', $pengiriman->kode_pengiriman) }}" readonly><br>

                                    <label for="nama">Tanggal Pengiriman</label>
                                <input type="text" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                    value="{{ old('tanggal_pengiriman', $pengiriman->tanggal_pengiriman) }}" readonly>

                                <input hidden type="text" class="form-control" id="toko_id" name="toko_id"
                                    value="{{ old('toko_id', $pengiriman->toko_id) }}">
                                <input hidden type="text" class="form-control" id="qrcode_pengiriman" name="qrcode_pengiriman"
                                    value="{{ old('qrcode_pengiriman', $qrcodePengiriman) }}">
                                <input  type="text" class="form-control" id="kode_produksi" name="kode_produksi"
                                    value="{{ old('kode_produksi', $kodeProduksi) }}">

                            </div>
                        </div>

                    </div>
            
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Pengiriman Barang Jadi</h3>
                            <div class="float-right">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="font-size:14px" class="text-center">No</th>
                                        <th style="font-size:14px">Kode Produk</th>
                                        <th style="font-size:14px">Nama Produk</th>
                                        <th style="font-size:14px">Jumlah</th>
                                        <th style="font-size:14px; text-align:center">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel-pembelian">
                                    @foreach ($stokBarangJadi as $index => $detail)
                                        <tr id="pembelian-{{ $index }}">
                                            <td class="text-center" id="urutan">{{ $loop->index + 1 }}</td>
                                            <td hidden>
                                                <div class="form-group" hidden>
                                                    <input type="text" class="form-control" id="nomor_seri-{{ $loop->index }}" name="id[]" value="{{ $detail->id }}">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" id="produk_id-{{ $loop->index }}" name="produk_id[]" value="{{ $detail->produk_id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="font-size:14px" readonly type="text" class="form-control" id="kode_lama-{{ $loop->index }}" name="kode_lama[]" value="{{ $detail->produk->kode_lama }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="font-size:14px" readonly type="text" class="form-control" id="nama_produk-{{ $loop->index }}" name="nama_produk[]" value="{{ $detail->produk->nama_produk }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input style="font-size:14px" type="number" class="form-control jumlah" id="jumlah-{{ $loop->index }}" name="jumlah[]" value="{{ $detail->jumlah }}">
                                                </div>
                                            </td>
                                            <td style="width: 100px">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                onclick="Barangs({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeBan({{ $index }}, {{ $detail->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>  
                                
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
            

            <div class="modal fade" id="tableBarang" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Data Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="m-2">
                                <input type="text" id="searchInputrutes" class="form-control" placeholder="Search...">
                            </div>
                            <table id="tablefaktur" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($uniqueStokBarangjadi as $barang)
                                        <tr data-id="{{ $barang->produk_id }}" 
                                            data-kode_lama="{{ $barang->produk->kode_lama  }}"
                                            data-nama_produk="{{ $barang->produk->nama_produk }}">

                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $barang->produk ? $barang->produk->kode_lama : 'N/A' }}</td>
                                            <td>{{ $barang->produk ? $barang->produk->nama_produk : 'N/A' }}</td>
                                            <td>{{ $barang['stok'] }}</td>

                                            <td class="text-center">
                                                <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getBarang({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script>
        function filterTablefaktur() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInputrutes");
            filter = input.value.toUpperCase();
            table = document.getElementById("tablefaktur");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                var displayRow = false;

                // Loop through columns (td 1, 2, and 3)
                for (j = 1; j <= 4; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break; // Break the loop if a match is found in any column
                        }
                    }
                }

                // Set the display style based on whether a match is found in any column
                tr[i].style.display = displayRow ? "" : "none";
            }
        }
        document.getElementById("searchInputrutes").addEventListener("input", filterTablefaktur);
    </script>


    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;
    
        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }
    
        function updateUrutan() {
            var urutan = document.querySelectorAll('#urutan');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }
    
        var counter = 0;
    
        function addPesanan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;
    
            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            } else {
                var lastRow = $('#tabel-pembelian tr:last');
                var lastRowIndex = lastRow.find('#urutan').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }
    
            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelian(jumlah_ban, jumlah_ban - 1);
            updateUrutan();
        }
    
        function removeBan(identifier, detailId) {
            var row = document.getElementById('pembelian-' + identifier);
            row.remove();
    
            $.ajax({
                url: "{{ url('admin/inquery_pengirimanbarangjadi/deleteprodukpengiriman/') }}/" + detailId,
                type: "POST",
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Data deleted successfully');
                },
                error: function(error) {
                    console.error('Failed to delete data:', error);
                }
            });
    
            updateUrutan();
        }
    
        function itemPembelian(identifier, key, value = null) {
            var produk_id = '';
            var kode_lama = '';
            var nama_produk = '';
            var jumlah = '';
    
            if (value !== null) {
                produk_id = value.produk_id;
                kode_lama = value.kode_lama;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
            }
    
            var item_pembelian = '<tr id="pembelian-' + key + '">';
            item_pembelian += '<td class="text-center" style=" font-size:14px" id="urutan">' + key + '</td>';
    
            // produk_id
            item_pembelian += '<td hidden>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<input type="text" class="form-control" id="produk_id-' + key +
                '" name="produk_id[]" value="' + produk_id + '" />';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
    
            // kode_lama
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-' +
                key + '" name="kode_lama[]" value="' + kode_lama + '" />';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
    
            // nama_produk
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' +
                key + '" name="nama_produk[]" value="' + nama_produk + '" />';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
    
            // jumlah
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian +=
                '<input type="text" class="form-control jumlah" style="font-size:14px"  id="jumlah-' +
                key + '" name="jumlah[]" value="' + jumlah + '" />';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
    
            // Action buttons
            item_pembelian += '<td style="width: 100px">';
            item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="Barangs(' + key + ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian += '<button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';
    
            $('#tabel-pembelian').append(item_pembelian);
        }
    
        var activeSpecificationIndex = 0;

        function Barangs(param) {
            activeSpecificationIndex = param;
            // Tampilkan modal untuk memilih produk
            $('#tableBarang').modal('show');
        }

        function getBarang(rowIndex) {
            // Ambil baris yang dipilih berdasarkan indeks
            var selectedRow = $('#tablefaktur tbody tr:eq(' + rowIndex + ')');

            // Ambil data dari atribut 'data-' pada baris yang dipilih
            var produk_id = selectedRow.data('id');
            var kode_lama = selectedRow.data('kode_lama');
            var nama_produk = selectedRow.data('nama_produk');

            // Debug untuk memastikan data sudah diambil dengan benar
            console.log('Selected row:', selectedRow);
            console.log('Produk ID:', produk_id);
            console.log('Kode Lama:', kode_lama);
            console.log('Nama Produk:', nama_produk);

            // Jika data ada, masukkan ke dalam tabel pembelian
            if (produk_id && kode_lama && nama_produk) {
                $('#produk_id-' + activeSpecificationIndex).val(produk_id);
                $('#kode_lama-' + activeSpecificationIndex).val(kode_lama);
                $('#nama_produk-' + activeSpecificationIndex).val(nama_produk);
            } else {
                console.error('Failed to retrieve product data. Please check the selected row.');
            }

            // Tutup modal setelah memilih produk
            $('#tableBarang').modal('hide');
        }

    </script>
    
  
@endsection
