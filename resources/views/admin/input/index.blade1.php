@extends('layouts.app')

@section('title', 'Tambah klasifikasi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Input Stok Barang Jadi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Barang Jadi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        .large-font {
            font-size: 1.5em; /* Atur ukuran font sesuai kebutuhan */
            font-weight: bold;

        }
    </style>
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
        <form action="{{ url('admin/input') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="no_faktur">No Faktur</label>
                            <input type="text"  class="form-control" id="no_faktur" name="no_faktur"
                               value="{{ old('no_faktur') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Tanggal :</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal" name="tanggal"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label" for="cabang">Pilih Cabang</label>
                            <select class="form-control" id="cabang" name="cabang">
                                <option value="">- Pilih -</option>
                                <option value="procot" {{ old('cabang') == 'L' ? 'selected' : null }}>
                                    Procot</option>
                                <option value="benjaran" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Benjaran</option>
                                <option value="tegal" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Tegal</option>
                                <option value="bumiayu" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Bumaiayu</option>
                                <option value="pekalongan" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Pekalongan</option>
                            </select>
                        </div>
                    </div>
                </div>
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
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-pembelian">
                            <!-- Data dari JavaScript akan dimasukkan di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3 ml-auto">
                            <label for="sub_total">Sub Total</label>
                            <input type="text" class="form-control large-font" id="sub_total" name="sub_total"
                                value="{{ old('sub_total') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="catatan">Catatan</label>
                            <textarea type="text"  class="form-control" id="catatan" name="catatan"
                               value="{{ old('catatan') }}"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Tanggal Pengiriman:</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal_pengiriman') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            
        </form>

        {{-- Modal untuk memilih barang --}}
        <div class="modal fade" id="tableMarketing" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->subsub->nama }}</td>
                                    <td>{{number_format($item->total, 0, ',', '.') }}</td> <!-- Format harga -->
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="getSelectedData('{{ $item->kode_barang }}', '{{ $item->subsub->nama }}', '{{ $item->total }}')">
                                            <i class="fas fa-plus"></i> Pilih
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

        </div>

     <script>

         

        var jumlah_ban = 0; // Variabel untuk menghitung jumlah baris pembelian
    
        // Fungsi untuk menambah baris pembelian baru
        function addPesanan() {
            jumlah_ban++; // Increment jumlah baris
    
            var urutan = jumlah_ban;
    
            // Template baris baru
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td class="text-center" style="width: 70px">' + urutan + '</td>';
            item_pembelian += '<td><input type="text" class="form-control" readonly id="kode_barang-' + urutan + '" name="kode_barang[]"></td>';
            item_pembelian += '<td><input type="text" class="form-control" readonly id="subsub_id-' + urutan + '" name="subsub_id[]"></td>';
            item_pembelian += '<td><input type="number" class="form-control jumlah" id="jumlah-' + urutan + '" name="jumlah[]" oninput="hitungTotal(' + urutan + ')"></td>';
            item_pembelian += '<td><input type="text" class="form-control harga" readonly id="harga-' + urutan + '" name="harga[]"></td>';
            item_pembelian += '<td><input type="text" class="form-control total" readonly id="total-' + urutan + '" name="total[]"></td>';
            item_pembelian += '<td style="width: 120px">';
            item_pembelian += '<button type="button" class="btn btn-primary" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button>';
            item_pembelian += '<button style="margin-left:5px" type="button" class="btn btn-danger" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';
    
            $('#tabel-pembelian').append(item_pembelian);
        }
    
        // Fungsi untuk menghapus baris pembelian
        function removeBan(urutan) {
            $('#pembelian-' + urutan).remove();
    
            // Update nomor urut setelah menghapus baris
            updateUrutan();
    
            // Hitung subtotal setelah menghapus baris
            hitungSubTotal();
        }
    
        // Fungsi untuk memperbarui nomor urut pada tabel
        function updateUrutan() {
            var urutanElements = document.querySelectorAll('#tabel-pembelian tr');
            urutanElements.forEach(function(element, index) {
                element.querySelector('.text-center').innerText = index + 1;
            });
        }
    
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


        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            $('#tableMarketing').modal('show');
    
            // Simpan urutan untuk menyimpan data ke baris yang sesuai
            $('#tableMarketing').attr('data-urutan', urutan);
        }
    
        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(kode_barang, subsub_id, harga) {
            var urutan = $('#tableMarketing').attr('data-urutan');
    
            // Set nilai input pada baris yang sesuai
            $('#kode_barang-' + urutan).val(kode_barang);
            $('#subsub_id-' + urutan).val(subsub_id);
            $('#harga-' + urutan).val(harga);
    
            // Hitung total
            hitungTotal(urutan);
    
            // Tutup modal
            $('#tableMarketing').modal('hide');
        }
    
        // Fungsi untuk menghitung total berdasarkan harga dan jumlah
        function hitungTotal(urutan) {
            var harga = parseFloat($('#harga-' + urutan).val()) || 0;
            var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;
            var total = harga * jumlah;
    
            // Format harga dan total ke dalam format rupiah
            $('#total-' + urutan).val(formatRupiah(total.toString()));
    
            // Hitung subtotal setiap kali total di baris berubah
            hitungSubTotal();
        }
    
        // Fungsi untuk menghitung subtotal
        function hitungSubTotal() {
            var subtotal = 0;
            $('.total').each(function() {
                var total = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                subtotal += total;
            });
            $('#sub_total').val(formatRupiah(subtotal.toString()));
        }
    
        // Fungsi untuk memformat angka ke dalam format rupiah
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
            // Tambahkan titik jika angka lebih dari 3 digit
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    
        // Event listener untuk input harga dan jumlah
        $(document).on("input", ".harga, .jumlah", function() {
            var currentRow = $(this).closest('tr');
            var harga = parseFloat(currentRow.find(".harga").val()) || 0;
            var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;
            var total = harga * jumlah;
            currentRow.find(".total").val(formatRupiah(total.toString()));
    
            // Hitung subtotal setiap kali input harga atau jumlah berubah
            hitungSubTotal();
        });
    </script>
    
@endsection


@extends('layouts.app')

@section('title', 'Input Barang Jadi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Input Stok Barang Jadi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Barang Jadi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        .large-font {
            font-size: 1.5em; /* Atur ukuran font sesuai kebutuhan */
            font-weight: bold;

        }
    </style>
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
        <form action="{{ url('admin/input') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="no_faktur">No Faktur</label>
                            <input type="text"  class="form-control" id="no_faktur" name="no_faktur"
                               value="{{ old('no_faktur') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Tanggal :</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal" name="tanggal"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label" for="cabang">Pilih Cabang</label>
                            <select class="form-control" id="cabang" name="cabang">
                                <option value="">- Pilih -</option>
                                <option value="procot" {{ old('cabang') == 'L' ? 'selected' : null }}>
                                    Procot</option>
                                <option value="benjaran" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Benjaran</option>
                                <option value="tegal" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Tegal</option>
                                <option value="bumiayu" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Bumaiayu</option>
                                <option value="pekalongan" {{ old('cabang') == 'P' ? 'selected' : null }}>
                                    Pekalongan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><span>
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
                                <th style="font-size:14px">Harga</th>
                                <th style="font-size:14px">Total</th>
                                <th style="font-size:14px; text-align:center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-pembelian">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3 ml-auto">
                            <label for="sub_total">Sub Total</label>
                            <input type="text" class="form-control large-font" id="sub_total" name="sub_total"
                                value="{{ old('sub_total') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="catatan">Catatan</label>
                            <textarea type="text"  class="form-control" id="catatan" name="catatan">{{ old('catatan') }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Tanggal Pengiriman:</label>
                            <div class="input-group date" id="reservationdatetime">
                                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                    placeholder="d M Y sampai d M Y"
                                    data-options='{"mode":"range","dateFormat":"d M Y","disableMobile":true}'
                                    value="{{ old('tanggal_pengiriman') }}" class="form-control datetimepicker-input"
                                    data-target="#reservationdatetime">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right mt-3">
                <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                <div id="loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                </div>
            </div>
            
        </form>

        {{-- Modal untuk memilih barang --}}
        <div class="modal fade" id="tableMarketing" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Barang</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangs as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->subsub->nama }}</td>
                                    <td>{{ number_format($item->total, 0, ',', '.') }}</td> <!-- Format harga -->
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="getSelectedData('{{ $item->id }}','{{ $item->kode_barang }}', '{{ $item->subsub->nama }}', '{{ $item->total }}')">
                                            <i class="fas fa-plus"></i> Pilih
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

        </div>

    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var selectedBarang = [];

        function addPesanan() {
            $('#tableMarketing').modal('show');
        }

        function formatRupiah(angka, prefix = 'Rp') {
            var number_string = angka.toString().replace(/[^,\d]/g, '');
            var split = number_string.split(',');
            var sisa = split[0].length % 3;
            var rupiah = split[0].substr(0, sisa);
            var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                var separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix + rupiah;
        }

        function getSelectedData(id, kode_barang, nama_barang, harga) {
            $('#tableMarketing').modal('hide');

            if (selectedBarang.includes(id)) {
                alert('Barang sudah dipilih');
                return;
            }
            selectedBarang.push(id);
            
            let newRow = `
                <tr id="tr-${id}">
                    <td class="text-center">${selectedBarang.length}</td>
                    <td>
                        <input type="hidden" name="id_barang[]" value="${id}">
                        ${kode_barang}
                    </td>
                    <td>${nama_barang}</td>
                    <td>
                        <input type="number" class="form-control" name="jumlah[]" id="jumlah-${id}" oninput="calculateTotal(${id})">
                    </td>
                    <td>
                        <input type="text" class="form-control" value="${formatRupiah(harga)}" readonly>
                        <input type="hidden" class="form-control" name="harga[]" id="harga-${id}" value="${harga}" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control total-harga" name="total[]" id="total-${id}" value="${formatRupiah(0)}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removePesanan(${id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tabel-pembelian').append(newRow);
        }

        function removePesanan(id) {
            $('#tr-' + id).remove();
            selectedBarang = selectedBarang.filter(item => item !== id);
            calculateTotal(); // Recalculate the totals after removing an item
        }

        function calculateTotal(id = null) {
            let subTotal = 0;
            if (id) {
                let jumlah = $('#jumlah-' + id).val();
                let harga = $('#harga-' + id).val();
                let total = jumlah * harga;
                $('#total-' + id).val(formatRupiah(total));
            }

            $('.total-harga').each(function () {
                let total = parseFloat($(this).val().replace(/[^,\d]/g, ''));
                subTotal += total;
            });

            $('#sub_total').val(formatRupiah(subTotal));
        }

    </script>
    </section>
@endsection
