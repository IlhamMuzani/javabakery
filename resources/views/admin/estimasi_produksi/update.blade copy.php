@extends('layouts.app')

@section('title', 'Inquery Penerimaan Return')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Estimasi Produksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/return_ekspedisi') }}">Estimasi Produksi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
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
            <form action="{{ url('admin/estimasi_produksi/' . $permintaanProduks->id) }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')
                <div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama">Nomor Permintaan</label>
                                <input type="text" class="form-control" id="kode_permintaan" name="kode_permintaan"
                                    readonly value="{{ old('kode_permintaan', $permintaanProduks->kode_permintaan) }}">
                            </div>
                        </div>
                    </div>
                    <div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Produk <span>
                                    </span></h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size:14px" class="text-center">No</th>
                                            <th style="font-size:14px">Kode Barang</th>
                                            <th style="font-size:14px">Nama Barang</th>
                                            <th style="font-size:14px">Jumlah</th>
                                            <th style="font-size:14px; text-align:center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelian">
                                        @foreach ($permintaanProduks->detailpermintaanproduks as $detail)
                                            <tr id="pembelian-{{ $loop->index }}">
                                                <td class="text-center" id="urutan">{{ $loop->index + 1 }}</td>
                                                <td hidden >
                                                    <div class="form-group" >
                                                        <input type="text" class="form-control"
                                                            id="nomor_seri-{{ $loop->index }}" name="detail_ids[]"
                                                            value="{{ $detail['id'] }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            id="produk_id-{{ $loop->index }}" name="produk_id[]"
                                                            value="{{ $detail['produk_id'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" readonly type="text"
                                                            class="form-control" id="kode_lama-{{ $loop->index }}"
                                                            name="kode_lama[]"value="{{ $detail->produk['kode_lama'] }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" readonly type="text"
                                                            class="form-control" id="nama_produk-{{ $loop->index }}"
                                                            name="nama_produk[]"value="{{ $detail->produk['nama_produk'] }}">
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-group">
                                                        <input style="font-size:14px" type="number"
                                                            class="form-control jumlah" id="jumlah-{{ $loop->index }}"
                                                            name="jumlah[]"value="{{ $detail['jumlah'] }}">
                                                    </div>
                                                </td>
                                                <td style="width: 100px">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="Barangs({{ $loop->index }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <button style="margin-left:5px" type="button"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="removeBan({{ $loop->index }}, {{ $detail['id'] }})">
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
                url: "{{ url('admin/estimasi_produksi/deletedetailpermintaan/') }}/" + detailId,
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

@endsection
