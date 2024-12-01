@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

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
                    <h1 class="m-0">Inquery Penjualan Toko</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Penjualan Toko</li>
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
                <div class="card-header">
                    <h3 class="card-title">Setoran Penjualan</h3>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="status" name="status">
                                    <option value="">- Semua Status -</option>
                                    <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>
                                        Posting</option>
                                    <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>Unpost
                                    </option>
                                </select>
                                <label for="status">(Pilih Status)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_setoran" name="tanggal_setoran" type="date"
                                    value="{{ Request::get('tanggal_setoran') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_setoran">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>



                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal Setoran</th>
                                <th>Cabang</th>
                                <th>Penjualan Kotor</th>
                                <th>Diskon Penjualan</th>
                                <th>Penjualan Bersih</th>
                                <th>Deposit Keluar</th>
                                <th>Deposit Masuk</th>
                                <th>Total Penjualan</th>
                                <th>Mesin EDC</th>
                                <th>Gobiz</th>
                                <th>Transfer</th>
                                <th>Qris</th>
                                <th>Total Setoran</th>
                                <th>Noiminal Setoran</th>
                                <th>Plus Minus</th>
                                <th class="text-center" width="20">Opsi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($setoranPenjualans as $index => $item)
                                <tr class="dropdown"{{ $item->id }}>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal_setoran ? \Carbon\Carbon::parse($item->tanggal_setoran)->format('d-m-Y') : '-' }}
                                    </td> <!-- Menampilkan Tanggal item -->
                                    <td>{{ $item->toko->nama_toko }}</td>
                                    <td>{{ number_format($item->penjualan_kotor, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->diskon_penjualan, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->penjualan_bersih, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->deposit_keluar, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->deposit_masuk, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->mesin_edc ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->gobiz ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->transfer ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->qris ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->total_setoran, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->nominal_setoran, 0, ',', '.') }}</td>
                                    <td>{{ number_format($item->plusminus, 0, ',', '.') }}</td>

                                    <td class="text-center">
                                        @if ($item->status == 'posting')
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if ($item->status == 'unpost')
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        @if ($item->status == 'approve')
                                            <button type="button" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if ($item->status == 'unpost')
                                                <a class="dropdown-item posting-btn"
                                                    data-memo-id="{{ $item->id }}">Posting</a>
                                                <a class="dropdown-item"
                                                    href="{{ url('admin/inquery_penjualantoko/' . $item->id . '/edit') }}">Update</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('inquery_penjualantoko.print', $item->id) }}"
                                                    target="_blank">Show</a>
                                            @endif

                                            @if ($item->status == 'posting')
                                                <a class="dropdown-item unpost-btn"
                                                    data-memo-id="{{ $item->id }}">Unpost</a>
                                                <a class="dropdown-item"
                                                    href="{{ route('inquery_penjualantoko.print', $item->id) }}"
                                                    target="_blank">Show</a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        var tanggalAwal = document.getElementById('tanggal_setoran');
        var tanggalAkhir = document.getElementById('tanggal_akhir');

        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.value = ""; // Reset tanggal akhir jika tanggal awal kosong
                tanggalAkhir.readOnly = true; // Menonaktifkan tanggal akhir
            } else {
                tanggalAkhir.readOnly = false; // Mengaktifkan tanggal akhir
                var today = new Date().toISOString().split('T')[0];
                tanggalAkhir.setAttribute('min', this.value); // Set min tanggal akhir sesuai tanggal awal
                tanggalAkhir.value = today >= this.value ? today : this.value; // Set nilai tanggal akhir
            }
        });

        var form = document.getElementById('form-action');

        function cari() {
            form.action = "{{ url('admin/inquery_penjualantoko') }}";
            form.submit();
        }
    </script>


    <script>
        $(document).ready(function() {
            $('tbody tr.dropdown').click(function(e) {
                // Memeriksa apakah yang diklik adalah checkbox
                if ($(e.target).is('input[type="checkbox"]')) {
                    return; // Jika ya, hentikan eksekusi
                }

                // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
                $('tr.dropdown').removeClass('selected').css('background-color', '');

                // Menambahkan kelas 'selected' ke baris yang dipilih dan mengubah warna latar belakangnya
                $(this).addClass('selected').css('background-color', '#b0b0b0');

                // Menyembunyikan dropdown pada baris lain yang tidak dipilih
                $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

                // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
                e.stopPropagation();
            });

            $('tbody tr.dropdown').contextmenu(function(e) {
                // Memeriksa apakah baris ini memiliki kelas 'selected'
                if ($(this).hasClass('selected')) {
                    // Menampilkan dropdown saat klik kanan
                    var dropdownMenu = $(this).find('.dropdown-menu');
                    dropdownMenu.show();

                    // Mendapatkan posisi td yang diklik
                    var clickedTd = $(e.target).closest('td');
                    var tdPosition = clickedTd.position();

                    // Menyusun posisi dropdown relatif terhadap td yang di klik
                    dropdownMenu.css({
                        'position': 'absolute',
                        'top': tdPosition.top + clickedTd
                            .height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                        'left': tdPosition
                            .left // Menempatkan dropdown di sebelah kiri td yang di klik
                    });

                    // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
                    e.stopPropagation();
                    e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
                }
            });

            // Menyembunyikan dropdown saat klik di tempat lain
            $(document).click(function() {
                $('.dropdown-menu').hide();
                $('tr.dropdown').removeClass('selected').css('background-color',
                    ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.unpost-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                // Tampilkan modal loading saat permintaan AJAX diproses
                $('#modal-loading').modal('show');

                // Kirim permintaan AJAX untuk melakukan unpost
                $.ajax({
                    url: "{{ url('admin/inquery_penjualantoko/unpost_penjualantoko/') }}/" +
                        memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        console.log(response);

                        // Tutup modal setelah berhasil unpost
                        $('#modal-posting-' + memoId).modal('hide');

                        // Reload the page to refresh the table
                        location.reload();
                    },
                    error: function(error) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                        console.log(error);
                    }
                });
            });
        });
    </script>
    {{-- posting memo --}}
    <script>
        $(document).ready(function() {
            $('.posting-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                // Tampilkan modal loading saat permintaan AJAX diproses
                $('#modal-loading').modal('show');

                // Kirim permintaan AJAX untuk melakukan posting
                $.ajax({
                    url: "{{ url('admin/inquery_penjualantoko/posting_penjualantoko/') }}/" +
                        memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        console.log(response);

                        // Tutup modal setelah berhasil posting
                        $('#modal-posting-' + memoId).modal('hide');

                        // Reload the page to refresh the table
                        location.reload();
                    },
                    error: function(error) {
                        // Sembunyikan modal loading setelah permintaan selesai
                        $('#modal-loading').modal('hide');

                        // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                        console.log(error);
                    }
                });
            });
        });
    </script>
@endsection
