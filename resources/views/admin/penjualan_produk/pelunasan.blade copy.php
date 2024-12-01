@extends('layouts.app')

@section('title', 'Pelunasan Pemesanan')

@section('content')
<style>
    .card {
        min-height: 100%;
    }
    .label-width {
    width: 100px; /* Atur sesuai kebutuhan */
}

.input-width {
    flex: 1;
}

.form-control-full-width {
        width: 100%;
    }
</style>

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pelunasan Pemesanan Produk</h1>
                </div><!-- /.col -->
                
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
            <form action="{{ url('admin/penjualan_produk/pelunasan') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <select class="form-control" id="kategori" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="penjualan" {{ old('kategori') == 'penjualan' ? 'selected' : '' }}>Penjualan Produk</option>
                                <option value="pelunasan" {{ old('kategori') == 'pelunasan' ? 'selected' : '' }}>Pelunasan Pemesanan Produk</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <label style="font-size:14px" class="form-label" for="kode_dppemesanan">Kode Deposit</label>
                        <div class="form-group d-flex">
                            <input class="form-control" hidden id="dppemesanan_id" name="dppemesanan_id" type="text"
                                placeholder="" value="{{ old('dppemesanan_id') }}" readonly
                                style="margin-right: 10px; font-size:14px" />
                            <input class="form-control col-md-4" id="kode_dppemesanan" name="kode_dppemesanan" type="text" placeholder=""
                                value="{{ old('kode_dppemesanan') }}" readonly style="margin-right: 10px; font-size:14px" />
                           
                            <button class="btn btn-primary" type="button" onclick="showCategoryModalPelanggan(this.value)">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                    </div>
                </div>
                <div>
                    <div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Pelanggan</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group" hidden>
                                            <label for="pelanggan_id">Id</label>
                                            <input type="text" class="form-control form-control-full-width" id="pelanggan_id" readonly name="pelanggan_id" placeholder="" value="{{ old('pelanggan_id') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_pelanggan">Nama Pelanggan</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="nama_pelanggan" readonly name="nama_pelanggan" placeholder="" value="{{ old('nama_pelanggan') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp">No. Telp</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="telp" readonly name="telp" placeholder="" value="{{ old('telp') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat">Alamat</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat" readonly name="alamat" placeholder="" value="">{{ old('alamat') }}</textarea>
                                        </div>
                                        <div class="form-check" style="color:white">
                                            <label class="form-check-label">
                                                .
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h3 class="card-title">Detail Pengiriman</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label style="font-size:14px" for="tanggal_kirim">Tanggal Pengiriman</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="tanggal_kirim" readonly name="tanggal_kirim" placeholder="" value="{{ old('tanggal_kirim') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="nama_penerima">Nama Penerima</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="nama_penerima" readonly name="nama_penerima" placeholder="" value="{{ old('nama_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="telp_penerima">Telepon Penerima</label>
                                            <input style="font-size:14px" type="text" class="form-control form-control-full-width" id="telp_penerima" readonly name="telp_penerima" placeholder="" value="{{ old('telp_penerima') }}">
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size:14px" for="alamat_penerima">Alamat Penerima</label>
                                            <textarea style="font-size:14px" type="text" class="form-control form-control-full-width" id="alamat_penerima" readonly name="alamat_penerima" placeholder="" value="">{{ old('alamat_penerima') }}</textarea>
                                        </div>
                                        <div class="form-check" style="color:white">
                                            <label class="form-check-label">
                                                .
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pemsanan --}}
                        <div id="forms-container"></div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="sub_total" class="mr-2 label-width">Sub Total</label>
                                                <input type="text" class="form-control large-font input-width" id="sub_total" name="sub_total" value="{{ old('sub_total') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="dp_pemesanan" class="mr-2 label-width">DP</label>
                                                <input type="text" class="form-control large-font input-width" id="dp_pemesanan" name="dp_pemesanan" readonly value="{{ old('dp_pemesanan') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kekurangan_pemesanan" class="mr-2 label-width">Kekurangan</label>
                                                <input type="text" class="form-control large-font input-width" id="kekurangan_pemesanan" name="kekurangan_pemesanan" value="{{ old('kekurangan_pemesanan') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="pelunasan" class="mr-2 label-width">Bayar</label>
                                                <input type="number" class="form-control large-font input-width" id="pelunasan" name="pelunasan" value="{{ old('pelunasan') }}" oninput="formatAndUpdateKembali()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3 ml-auto d-flex align-items-center">
                                                <label for="kembali" class="mr-2 label-width">Kembalian</label>
                                                <input type="number" class="form-control large-font input-width" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                          
                            <div class="card-footer text-right">
                                <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                                <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                                <div id="loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>

        <div class="modal fade" id="tableDeposit" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Deposit</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
               
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr style="font-size: 13px">
                                    <th class="text-center">No</th>
                                    <th>Kode Deposit</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Total</th>
                                    <th>DP</th>
                                    <th>Kekurangan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($dppemesanans as $return)
                                @if (is_null($return->pelunasan))
                                    <tr style="font-size: 14px">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $return->kode_dppemesanan }}</td>
                                        <td>{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}</td>
                                        <td>{{ 'Rp. ' .number_format($return->pemesananproduk ? $return->pemesananproduk->sub_total : 0, 0, ',', '.') }}</td>
                                        <td>{{ 'Rp. ' .number_format($return->dp_pemesanan, 0, ',', '.') }}</td>
                                        <td>{{ 'Rp. ' .number_format($return->kekurangan_pemesanan, 0, ',', '.') }}</td>
                                        <td   td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm"
                                            onclick="GetReturn(
                                            '{{ $return->id }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->kode_pemesanan : 'No Data' }}',
                                            '{{ $return->dp_pemesanan }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->nama_pelanggan : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->telp : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->alamat : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->tanggal_kirim : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->nama_penerima : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->telp_penerima : '' }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->alamat_penerima : '' }}',
                                            '{{ $return->detailpemesananproduk->pluck('pemesananproduk_id')->implode(', ') }}',
                                            '{{ $return->detailpemesananproduk->pluck('kode_produk')->implode(', ') }}',
                                            '{{ $return->detailpemesananproduk->pluck('nama_produk')->implode(', ') }}',
                                            '{{ $return->detailpemesananproduk->pluck('jumlah')->implode(', ') }}',
                                            '{{ $return->detailpemesananproduk->pluck('total')->implode(', ') }}',
                                            '{{ $return->pemesananproduk ? $return->pemesananproduk->sub_total : 0 }}',
                                            '{{ $return->dp_pemesanan }}',
                                            '{{ $return->kekurangan_pemesanan }}'
                                            )">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        </td>

                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>

    </section>
    <script>
        function formatAndUpdateKembali() {
            // Ambil nilai dari input fields
            var pelunasan = parseFloat(document.getElementById('pelunasan').value) || 0;
            var kekurangan = parseFloat(document.getElementById('kekurangan_pemesanan').value) || 0;
    
            // Hitung kembalian
            var kembali = pelunasan - kekurangan;
    
            // Perbarui nilai input kembalian
            document.getElementById('kembali').value = kembali.toFixed(0);
        }
    
        // Panggil fungsi saat halaman dimuat pertama kali untuk memastikan nilai awal sudah benar
        document.addEventListener('DOMContentLoaded', formatAndUpdateKembali);
    </script>
  
    <script>
        function showCategoryModalPelanggan(selectedCategory) {
            $('#tableDeposit').modal('show');
        }

        $(document).ready(function() {
            // Call updateTotal function for each existing row to initialize totals
            for (var i = 0; i < barangIds.length; i++) {
                updateTotal(i);
                attachInputEventListeners(i);
            }
        });


        function updateTotal(index) {
            var jumlah = parseFloat($('#jumlah_' + index).val()) || 0;
            var harga = parseFloat($('#harga_' + index).val()) || 0;
            var total = jumlah * harga;

            $('#total_' + index).val(formatNumber(total));
            // Update the grand total
            updateGrandTotal();
        }

        function onHargaChange(index) {
            // Update the total based on harga and jumlah
            var harga = parseFloat($('#harga_' + index).val()) || 0;
            var jumlah = parseFloat($('#jumlah_' + index).val()) || 0;
            var total = harga * jumlah;

            // Update the total field
            $('#total_' + index).val(total.toLocaleString('id-ID'));

            // Update the grand total
            updateGrandTotal();
        }

        function attachInputEventListeners(index) {
            // Attach input event listener for both "jumlah" and "harga" fields
            $('#jumlah_' + index + ', #harga_' + index).on('input', function() {
                updateTotal(index);
            });
        }

        function saveFormDataToSessionStorage() {
            var formData = $('#forms-container').html();
            sessionStorage.setItem('formData', formData);
        }

        // Call this function when the page is loaded to retrieve and display the saved form data
        // Call this function when the page is loaded to retrieve and display the saved form data
        function loadFormDataFromSessionStorage() {
            var formData = sessionStorage.getItem('formData');
            var returnId = $('#dppemesanan_id').val(); // Get the value of dppemesanan_id

            // Check if formData exists and dppemesanan_id is not empty
            if (formData && returnId.trim() !== "") {
                $('#forms-container').html(formData);
                attachInputEventListenersAfterLoad();
            } else {
                // If formData doesn't exist or dppemesanan_id is empty, clear forms-container
                $('#forms-container').html('');
            }
        }

        // Call loadFormDataFromSessionStorage() on document ready
        $(document).ready(function() {
            loadFormDataFromSessionStorage();
        });

        $(document).ready(function() {
            loadFormDataFromSessionStorage();
        });
        // Attach input event listeners after loading the form data
        function attachInputEventListenersAfterLoad() {
            for (var i = 0; i < barangIds.length; i++) {
                attachInputEventListeners(i);
            }
        }

        function updateGrandTotal() {
            var grandTotal = 0;
            $('.total').each(function() {
                // Remove dots and parse as float
                var totalValue = parseFloat($(this).val().replace(/\./g, '')) || 0;
                grandTotal += totalValue;
            });

            // Format the grandTotal as currency in Indonesian Rupiah
            var formattedGrandTotal = grandTotal.toLocaleString('id-ID');

            $('#grand_total').val(formattedGrandTotal);
            saveFormDataToSessionStorage(); // Save the form data to sessionStorage
        }

        function formatNumber(value) {
            // Check if the value is an integer or has decimal places
            if (value === parseInt(value, 10)) {
                return value.toFixed(0); // If it's an integer, remove decimal places
            } else {
                return value.toFixed(2); // If it has decimal places, keep two decimal places
            }
        }

    function GetReturn(dppemesanan_id, Kodedp, dp_pemesanan, NamPel, telpPel, alaPel, tglKirim, NamPen, telpPen, alaPen,
    Barang_id, KodeBarang, NamaBarang, Total, Jumlah,subTo, dpPeme,kekur) {

    document.getElementById('dppemesanan_id').value = dppemesanan_id;
    document.getElementById('kode_dppemesanan').value = Kodedp;
    document.getElementById('dp_pemesanan').value = dp_pemesanan;
    document.getElementById('nama_pelanggan').value = NamPel;
    document.getElementById('telp').value = telpPel;
    document.getElementById('alamat').value = alaPel;
    document.getElementById('tanggal_kirim').value = tglKirim;
    document.getElementById('nama_penerima').value = NamPen;
    document.getElementById('telp_penerima').value = telpPen;
    document.getElementById('alamat_penerima').value = alaPen;
    document.getElementById('sub_total').value = subTo;
    document.getElementById('dp_pemesanan').value = dpPeme;
    document.getElementById('kekurangan_pemesanan').value = kekur;

    var barangIds = Barang_id.split(', ');
    var kodeBarangs = KodeBarang.split(', ');
    var namaBarangs = NamaBarang.split(', ');
    var jumlahs = Jumlah.split(', ');
    var totals = Total.split(', ');

    $('#forms-container').html('');

    var formHtml = '<div class="card mb-3">' +
        '<div class="card-header">' +
        '<h3 class="card-title">Detail Pemesanan</h3>' +
        '</div>' +
        '<div class="card-body">' +
        '<table class="table table-bordered table-striped">' +
        '<thead>' +
        '<tr>' +
        '<th style="font-size:14px" class="text-center">No</th>' +
        '<th style="font-size:14px">Kode Produk</th>' +
        '<th style="font-size:14px">Nama Produk</th>' +
        '<th style="font-size:14px">Jumlah</th>' +
        '<th style="font-size:14px">Total</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody id="tabel-pembelian">';

    for (var i = 0; i < barangIds.length; i++) {
        formHtml += '<tr>' +
            '<td style="width: 70px; font-size:14px" class="text-center urutan">' + (i + 1) + '</td>' +
            '<td hidden>' +
            '   <div class="form-group">' +
            '       <input type="text" class="form-control" name="pemesananproduk_id[]" value="' + barangIds[i] + '" readonly>' +
            '   </div>' +
            '</td>' +
            '<td>' +
            '   <div class="form-group">' +
            '       <input style="font-size:14px" readonly type="text" class="form-control kode_produk" name="kode_produk[]" value="' + kodeBarangs[i] + '">' +
            '   </div>' +
            '</td>' +
            '<td>' +
            '   <div class="form-group">' +
            '       <input style="font-size:14px" readonly type="text" class="form-control nama_produk" name="nama_produk[]" value="' + namaBarangs[i] + '">' +
            '   </div>' +
            '</td>' +
            '<td>' +
            '   <div class="form-group">' +
            '       <input style="font-size:14px" type="number" readonly class="form-control jumlah" name="jumlah[]" id="jumlah_' + i + '" value="' + jumlahs[i] + '" onchange="updateTotal(' + i + ')">' +
            '   </div>' +
            '</td>' +
            '<td>' +
            '   <div class="form-group">' +
            '       <input style="font-size:14px" type="number" readonly class="form-control total" name="total[]" id="total_' + i + '" value="' + totals[i] + '" onchange="updateTotal(' + i + ')">' +
            '   </div>' +
            '</td>' +
            '</tr>';
    }

    formHtml += '</tbody>' +
        '</table>' +
        '</div>' +
        '</div>';

    $('#forms-container').append(formHtml);
            updateGrandTotal();

            $('#tableDeposit').modal('hide');
            attachInputEventListenersAfterLoad();

        }
        
        function handleEnterKey(e) {
        if (e.key === 'Enter') {
            const selectedRow = document.querySelector('tr.selected');
            if (selectedRow) {
                const id = selectedRow.id.split('-')[1];
                const rowData = selectedRow.dataset;
                GetReturn(
                    id,
                    rowData.kodeDppemesanan,
                    rowData.dpPemesanan,
                    rowData.namaPelanggan,
                    rowData.telp,
                    rowData.alamat,
                    rowData.tanggalKirim,
                    rowData.namaPenerima,
                    rowData.telpPenerima,
                    rowData.alamatPenerima,
                    rowData.barangId,
                    rowData.kodeBarang,
                    rowData.namaBarang,
                    rowData.jumlah,
                    rowData.total,
                    rowData.subTotal,
                    rowData.dpPemesanan,
                    rowData.kekurangan
                );
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('keydown', handleEnterKey);

        const tableBody = document.querySelector('#datatables4 tbody');
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('tr')) {
                // Hapus kelas "selected" dari baris sebelumnya
                tableBody.querySelectorAll('tr').forEach(row => row.classList.remove('selected'));
                // Tambahkan kelas "selected" ke baris yang diklik
                e.target.closest('tr').classList.add('selected');
            }
        });
    });
    </script>


    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>
<script>
    document.getElementById('kategori').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'penjualan') {
            window.location.href = "{{ route('admin.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
        } else if (selectedValue === 'pelunasan') {
            window.location.href = "{{ route('admin.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
        }
    });
</script>


@endsection
