@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .klasifikasi-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .klasifikasi-header:hover {
            background-color: #f0f0f0;
        }
        .klasifikasi-header.active {
            background-color: #e0e0e0;
        }
        .produk-table {
            display: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 10); // Adjust the delay time as needed
        });
    </script>
    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengiriman Barang Jadi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                {{-- <div class="card-header">
                    <div class="float-right">
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="permintaan" {{ old('kategori1') == 'permintaan' ? 'selected' : '' }}>Pengiriman Permintaan</option>
                            <option value="pemesanan" {{ old('kategori1') == 'pemesanan' ? 'selected' : '' }}>Pengiriman Pesanan</option>
                        </select>
                    </div>

                </div> --}}
                <!-- /.card-header -->
                <div class="card-body">
                    
                    {{-- <form action="{{ url('admin/pengiriman_barangjadi') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="toko_id" value="{{ old('toko_id', $toko_id) }}"> <!-- Assuming $toko_id is passed from the controller -->
                    
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <div class="col-md-3 mb-3">
                                            <select class="custom-select form-control" id="toko" name="toko_id">
                                                <option value="">- Pilih Toko -</option>
                                                @foreach ($tokos as $toko)
                                                    <option value="{{ $toko->id }}" {{ old('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="tanggal_estimasi_pengiriman">Tanggal_estimasi Pengiriman:</label>
                                            <input type="date" class="form-control" id="tanggal_estimasi_pengiriman" name="tanggal_estimasi_pengiriman" value="{{ old('tanggal_estimasi_pengiriman') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="kode_produksi">Kode Produksi:</label>
                                            <div>
                                                @foreach (['A', 'B', 'C', 'D', 'E'] as $huruf)
                                                    <input type="checkbox" name="kode_produksi[]" value="{{ $huruf }}"> {{ $huruf }}
                                                @endforeach
                                            </div>
                                            <div>
                                                @for ($i = 1; $i <= 7; $i++)
                                                    <input type="checkbox" name="kode_produksi[]" value="{{ $i }}"> {{ $i }}
                                                @endfor
                                            </div>
                                        </div>
                                    </thead>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><span></span></h3>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                           
                                            <div class="row">
                                                <div class="col">
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
                                                            @if(old('produk_id'))
                                                                @foreach(old('produk_id') as $key => $produkId)
                                                                    <tr id="pembelian-{{ $key }}">
                                                                        <td style="width: 70px; font-size:14px" class="text-center" id="urutan-{{ $key }}">{{ $key + 1 }}</td>
                                                                        <td hidden>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" id="produk_id-{{ $key }}" name="produk_id[]" value="{{ $produkId }}">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-{{ $key }}" name="kode_produk[]" value="{{ old('kode_produk')[$key] ?? '' }}">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-{{ $key }}" name="nama_produk[]" value="{{ old('nama_produk')[$key] ?? '' }}">
                                                                            </div>
                                                                        </td>
                                                                        <td style="width: 150px">
                                                                            <div class="form-group">
                                                                                <input type="number" class="form-control" style="font-size:14px" id="jumlah-{{ $key }}" name="jumlah[]" value="{{ old('jumlah')[$key] ?? '' }}" oninput="hitungTotal({{ $key }})" onkeydown="handleEnter(event, {{ $key }})">
                                                                            </div>
                                                                        </td>
                                                                        <td style="width: 100px">
                                                                            <button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal({{ $key }})"><i class="fas fa-plus"></i></button>
                                                                            <button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan({{ $key }})"><i class="fas fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </form> --}}

                    <form method="GET" id="form-action">
                        <div class="row">
                            
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_estimasi" name="tanggal_estimasi" type="date" value="{{ Request::get('tanggal_estimasi') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_estimasi">(Tanggal_estimasi)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id" onchange="document.getElementById('form-action').submit();">
                                    <option value="">- Semua Toko -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>
                                            {{ $toko->nama_toko }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($inquery->isNotEmpty())
                        @foreach($inquery as $estimasi)
                        <form action="{{ route('admin.pengiriman_barangjadi.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">

                            {{-- <form action="{{ url('admin/pengiriman_barangjadi/' . $estimasi->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off"> --}}
                                @csrf
                                <input type="hidden" id="toko_id" name="toko_id" value="{{ Request::get('toko_id') }}">
                                <div class="col-md-3 mb-3">
                                    <label for="tanggal_pengiriman">Tanggal Pengiriman:</label>
                                    <input type="date" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman') }}">
                                </div>
                                <div class="card">
                                    <div class="card-header">
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
                                                    <th class="text-center">No</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Kategori</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tabel-pembelian">
                                                @foreach ($estimasi->detailestimasiproduksi as $detail)
                                                <tr id="pembelian-{{ $loop->index }}">
                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                    <td hidden>
                                                        <input type="text" name="produk_id[]" value="{{ $detail->produk_id }}">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" name="kode_lama[]" value="{{ $detail->produk->kode_lama }}">
                                                    </td>
                                                    <td>
                                                        <input readonly type="text" class="form-control" name="nama_produk[]" value="{{ $detail->produk->nama_produk }}">
                                                    </td>
                                                   
                                                    <td>
                                                        <input type="number" class="form-control jumlah" name="jumlah[]" value="{{ $detail->jumlah }}">
                                                    </td>
                                                    <td >
                                                        <input readonly type="text" class="form-control" name="kategori[]" value="{{ $detail->kategori }}">
                                                    </td>
                                                    <td style="width: 100px">
                                                        <button type="button" class="btn btn-primary btn-sm" onclick="Barangs({{ $loop->index }})">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeBan({{ $loop->index }}, {{ $detail->id }})">
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
                            </form>
                        @endforeach
                    @endif

                    
                    

                    <div class="modal fade" id="tableBarang" data-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Data Produk</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="float-right ml-3 mt-3">
                                    <button type="button" data-toggle="modal" data-target="#modal-barang"
                                        class="btn btn-primary btn-sm">
                                        Tambah
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="m-2">
                                        <input type="text" id="searchInputrutes" class="form-control"
                                            placeholder="Search...">
                                    </div>
                                    <table id="tablefaktur" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($produks as $barang)
                                                <tr data-id="{{ $barang->id }}" data-kode_lama="{{ $barang->kode_lama }}"
                                                    data-nama_produk="{{ $barang->nama_produk }}"
                                                    data-param="{{ $loop->index }}">
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $barang->kode_lama }}</td>
                                                    <td>{{ $barang->nama_produk }}</td>
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
                        
                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <script>
        var form = document.getElementById('form-action');
    
        function cari() {
            // Mengatur action form untuk mengarahkan ke URL yang tepat
            form.action = "{{ url('admin/pengiriman_barangjadi/create') }}";
            form.submit(); // Mengirimkan form
        }
    </script>


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
            // Find the last row and get its index to continue the numbering
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
            url: "{{ url('admin/pengiriman_barangjadi/deletedetailpermintaan/') }}/" + detailId,
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
        var kategori = '';
        var jumlah = '';

        if (value !== null) {
            produk_id = value.produk_id;
            kode_lama = value.kode_lama;
            nama_produk = value.nama_produk;
            kategori = value.kategori;
            jumlah = value.jumlah;
        }

        // urutan 
        var item_pembelian = '<tr id="pembelian-' + key + '">';
        item_pembelian += '<td class="text-center" style=" font-size:14px" id="urutan">' + key + '</td>';


        // produk_id 
        item_pembelian += '<td hidden>';
        item_pembelian += '<div class="form-group">'
        item_pembelian += '<input type="text" class="form-control" id="produk_id-' + key +
            '" name="produk_id[]" value="' + produk_id + '" ';
        item_pembelian += '</div>';
        item_pembelian += '</td>';

        // kode_lama 
        item_pembelian += '<td>';
        item_pembelian += '<div class="form-group">'
        item_pembelian += '<input type="text" class="form-control" style="font-size:14px" readonly id="kode_lama-' +
            key +
            '" name="kode_lama[]" value="' + kode_lama + '" ';
        item_pembelian += '</div>';
        item_pembelian += '</td>';

        // nama_produk 
        item_pembelian += '<td>';
        item_pembelian += '<div class="form-group">'
        item_pembelian += '<input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' +
            key +
            '" name="nama_produk[]" value="' + nama_produk + '" ';
        item_pembelian += '</div>';
        item_pembelian += '</td>';


        // jumlah 
        item_pembelian += '<td>';
        item_pembelian += '<div class="form-group">'
        item_pembelian +=
            '<input type="text" class="form-control jumlah" style="font-size:14px"  id="jumlah-' +
            key +
            '" name="jumlah[]" value="' + jumlah + '" ';
        item_pembelian += '</div>';
        item_pembelian += '</td>';

        item_pembelian += '<td>';
        item_pembelian += '<div class="form-group">';
        item_pembelian +=
        '<input type="text" class="form-control kategori" style="font-size:14px" readonly  id="kategori-' +
        key +
        '" name="kategori[]" value="permintaan" ';  
        item_pembelian += '</div>';
        item_pembelian += '</td>';


        item_pembelian += '<td style="width: 100px">';
        item_pembelian += '<button type="button" class="btn btn-primary btn-sm" onclick="Barangs(' + key +
            ')">';
        item_pembelian += '<i class="fas fa-plus"></i>';
        item_pembelian += '</button>';
        item_pembelian +=
            '<button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' +
            key + ')">';
        item_pembelian += '<i class="fas fa-trash"></i>';
        item_pembelian += '</button>';
        item_pembelian += '</td>';
        item_pembelian += '</tr>';

        $('#tabel-pembelian').append(item_pembelian);
    }
</script>

<script>
    var activeSpecificationIndex = 0;

    function Barangs(param) {
        activeSpecificationIndex = param;
        // Show the modal and filter rows if necessary
        $('#tableBarang').modal('show');
    }

    function getBarang(rowIndex) {
        var selectedRow = $('#tablefaktur tbody tr:eq(' + rowIndex + ')');
        var produk_id = selectedRow.data('id');
        var kode_lama = selectedRow.data('kode_lama');
        var nama_produk = selectedRow.data('nama_produk');

        // Update the form fields for the active specification
        $('#produk_id-' + activeSpecificationIndex).val(produk_id);
        $('#kode_lama-' + activeSpecificationIndex).val(kode_lama);
        $('#nama_produk-' + activeSpecificationIndex).val(nama_produk);

        $('#tableBarang').modal('hide');
    }
</script>


<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'permintaan') {
            window.location.href = "{{ route('admin.pengiriman_barangjadi.create') }}"; 
        } else if (selectedValue === 'pemesanan') {
            window.location.href = "{{ route('admin.pengiriman_barangjadipesanan.create') }}"; 
        }
    });
</script>
@endsection
