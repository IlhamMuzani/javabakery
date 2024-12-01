@extends('layouts.app')

@section('title', 'Tambah Setoran')

@section('content')

<style>
    .custom-checkbox {
    margin-right: 10px; /* Atur nilai sesuai kebutuhan */
}
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Setoran Pelunasan</h1>
                </div><!-- /.col -->
             
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('setoran_pelunasan.update_status') }}" method="POST" enctype="multipart/form-data" id="myForm">
            @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                        value="{{ Request::get('tanggal_penjualan') }}" onchange="updateModalLink()" />
                                        <label for="tanggal_penjualan">(Tanggal Penjualan)</label>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <select class="custom-select form-control" id="toko" name="toko_id" onchange="updateModalLink()">
                                        <option value="">- Semua Toko -</option>
                                        @foreach ($tokos as $toko)
                                            <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tanggal_penjualan">(Pilih Toko)</label>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="button" id="btnCari" class="btn btn-outline-primary">Cari</button>
                                </div>
                            </div>
                        </div>
                
                        <div class="card-body">

                                <input type="text" id="setoran_id" name="id" class="form-control" hidden/>
                                <input type="text" id="tanggal_penjualan" name="tanggal_penjualan" class="form-control" hidden/>
                          
                            <!-- Tempat untuk menampilkan Penjualan Kotor -->
                            <div class="form-group row mb-3">
                                <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Kotor</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_penjualan_kotor" onchange="toggleGreenCheck('penjualan_kotor')">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" placeholder="" readonly>
                                </div>
                                               
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_penjualan_kotor">
                                        ✓
                                    </button>
                                </div>
                            </div>
                
                            <!-- Tempat untuk menampilkan Diskon Penjualan -->
                            <div class="form-group row mb-3">
                                <label for="diskon_penjualan" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Diskon Penjualan</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_diskon_penjualan" onchange="toggleGreenCheck('diskon_penjualan')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="diskon_penjualan" name="diskon_penjualan" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_diskon_penjualan">
                                        ✓
                                    </button>
                                </div>
                            </div>

                                <div class="col-sm-3 offset-sm-3">
                                    <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                                </div>

                            <div class="form-group row mb-3">
                                <label for="penjualan_bersih" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Bersih</a>
                                </label>
                                <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_penjualan_bersih" onchange="toggleGreenCheck('penjualan_bersih')">
                                </div>                            
                                <div class="col-sm-3">
                                        <input type="text" class="form-control" id="penjualan_bersih" name="penjualan_bersih" readonly>
                                </div>
                                <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_penjualan_bersih">
                                            ✓
                                        </button>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_keluar" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Deposit Keluar</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_deposit_keluar" onchange="toggleGreenCheck('deposit_keluar')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_keluar" name="deposit_keluar" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_deposit_keluar">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_masuk" class="col-sm-3 col-form-label">
                                        <a id="deposit_masuk_link" href="#" data-toggle="modal" data-target="#depositMasukModal" class="text-decoration-none">Deposit Masuk</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_deposit_masuk" onchange="toggleGreenCheck('deposit_masuk')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_masuk" name="deposit_masuk" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_deposit_masuk">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="col-sm-3 offset-sm-3">
                                    <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="total_penjualan" class="col-sm-3 col-form-label">
                                        <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Total Penjualan</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_total_penjualan" onchange="toggleGreenCheck('total_penjualan')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="total_penjualan" name="total_penjualan" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_total_penjualan">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="mesin_edc" class="col-sm-3 col-form-label">
                                        <a id="penjualan_mesinedc_link" href="#" data-toggle="modal" data-target="#penjualanMesinedcModal" class="text-decoration-none">Mesin EDC</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_mesin_edc" onchange="toggleGreenCheck('mesin_edc')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="mesin_edc" name="mesin_edc" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_mesin_edc">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="qris" class="col-sm-3 col-form-label">
                                        <a id="penjualan_qris_link" href="#" data-toggle="modal" data-target="#penjualanQrisModal" class="text-decoration-none">Qris</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_qris" onchange="toggleGreenCheck('qris')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="qris" name="qris" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_qris">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="gobiz" class="col-sm-3 col-form-label">
                                        <a id="penjualan_gobiz_link" href="#" data-toggle="modal" data-target="#penjualanGobizModal" class="text-decoration-none">Gobiz</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_gobiz" onchange="toggleGreenCheck('gobiz')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="gobiz" name="gobiz" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_gobiz">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="transfer" class="col-sm-3 col-form-label">
                                        <a id="penjualan_transfer_link" href="#" data-toggle="modal" data-target="#penjualanTransferModal" class="text-decoration-none">Transfer</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_transfer" onchange="toggleGreenCheck('transfer')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="transfer" name="transfer" readonly>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_transfer">
                                            ✓
                                        </button>
                                    </div>
                            </div>


                            <div class="col-sm-3 offset-sm-3">
                                <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>

                            <div class="form-group row mb-3">
                                <label for="total_setoran" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Total Setoran</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_total_setoran" onchange="toggleGreenCheck('total_setoran')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="total_setoran" name="total_setoran" >
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_total_setoran">
                                        ✓
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="nominal_setoran" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Nominal Setoran</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_nominal_setoran" onchange="toggleGreenCheck('nominal_setoran')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="nominal_setoran" name="nominal_setoran" >
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_nominal_setoran">
                                        ✓
                                    </button>
                                </div>
                            </div>

                            {{-- @if(!is_null($setoranPenjualans->nominal_setoran2))
                            <div class="form-group row mb-3">
                                <label for="nominal_setoran2" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Nominal Setoran 2</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_nominal_setoran2" onchange="toggleGreenCheck('nominal_setoran2')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="nominal_setoran2" name="nominal_setoran2" >
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_nominal_setoran2">
                                        ✓
                                    </button>
                                </div>
                            </div>
                            @endif --}}

                            <div class="form-group row mb-3">
                                <label for="plusminus" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">+/-</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_plusminus" onchange="toggleGreenCheck('plusminus')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="plusminus" name="plusminus" >
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_plusminus">
                                        ✓
                                    </button>
                                </div>
                            </div> 
                        </div>       
                    </div>   
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        </div>
        <div class="modal fade" id="penjualanKotorModal" tabindex="-1" role="dialog" aria-labelledby="penjualanKotorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanKotorModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.penjualantoko.kotor') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a>

                            <a href="{{ route('print.fakturpenjualantoko') }}" 
                            id="faktur_penjualan_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="depositMasukModal" tabindex="-1" role="dialog" aria-labelledby="depositMasukModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositMasukModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            {{-- <a href="{{ route('print.penjualantoko.kotor') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a> --}}

                            <a href="{{ route('print.fakturdepositmasuktoko') }}" 
                            id="faktur_deposit_masuk_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanMesinedcModal" tabindex="-1" role="dialog" aria-labelledby="penjualanMesinedcModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanMesinedcModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanmesinedc') }}" 
                            id="penjualan_mesinedc_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanQrisModal" tabindex="-1" role="dialog" aria-labelledby="penjualanQrisModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanQrisModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanqris') }}" 
                            id="penjualan_qris_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanTransferModal" tabindex="-1" role="dialog" aria-labelledby="penjualanTransferModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanTransferModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualantransfer') }}" 
                            id="penjualan_transfer_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanGobizModal" tabindex="-1" role="dialog" aria-labelledby="penjualanGobizModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanGobizModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualangobiz') }}" 
                            id="penjualan_gobiz_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
            // Cek apakah checkbox penjualan kotor dicentang
            const checkPenjualanKotor = document.getElementById('check_penjualan_kotor');
            const checkDiskonPenjualan = document.getElementById('check_diskon_penjualan');
            const checkPenjualanBersih = document.getElementById('check_penjualan_bersih');
            const checkDepositKeluar = document.getElementById('check_deposit_keluar');
            const checkDepositMasuk = document.getElementById('check_deposit_masuk');
            const checkTotalPenjualan = document.getElementById('check_total_penjualan');
            const checkMesinEdc = document.getElementById('check_mesin_edc');
            const checkQris = document.getElementById('check_qris');
            const checkGobiz = document.getElementById('check_gobiz');
            const checkTransfer = document.getElementById('check_transfer');
            const checkTotalSetoran = document.getElementById('check_total_setoran');
            const checkNominalSetoran = document.getElementById('check_nominal_setoran');
            const checkPlusMinus = document.getElementById('check_plusminus');
    
            // Jika checkbox belum dicentang, mencegah submit dan tampilkan SweetAlert
            if (!checkPenjualanKotor.checked) {
                event.preventDefault(); // Mencegah form disubmit
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Anda harus mencentang checkbox Penjualan Kotor terlebih dahulu.',
                });
                return false;
            }
    
            // Cek apakah checkbox diskon penjualan dicentang
            if (!checkDiskonPenjualan.checked) {
                event.preventDefault(); // Mencegah form disubmit
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Anda harus mencentang checkbox Diskon Penjualan terlebih dahulu.',
                });
                return false;
            }
        });
    
        function toggleGreenCheck(id) {
            const checkbox = document.getElementById('check_' + id);
            const button = document.getElementById('btn_' + id);
    
            if (checkbox.checked) {
                button.classList.remove('d-none');
            } else {
                button.classList.add('d-none');
            }
        }
    </script>

<script>
    // Fungsi untuk memperbarui URL link di dalam modal
    function updateModalLink() {
        const tanggalPenjualan = document.getElementById('tanggal_penjualan').value;
        const tokoId = document.getElementById('toko').value;

        // Base URL untuk Barang Keluar (link di dalam modal)
        const baseUrlBarangKeluar = "{{ route('print.penjualantoko.kotor') }}";
        const baseUrlFakturPenjualan = "{{ route('print.fakturpenjualantoko') }}"; // Perbaikan nama variabel
        const baseUrlFakturDeposit = "{{ route('print.fakturdepositmasuktoko') }}"; // Perbaikan nama variabel
        const baseUrlFakturPenjualanMesinedc = "{{ route('print.fakturpenjualanmesinedc') }}"; // Perbaikan nama variabel
        const baseUrlFakturPenjualanQris = "{{ route('print.fakturpenjualanqris') }}"; // Perbaikan nama variabel
        const baseUrlFakturPenjualanTransfer = "{{ route('print.fakturpenjualantransfer') }}"; // Perbaikan nama variabel
        const baseUrlFakturPenjualanGobiz = "{{ route('print.fakturpenjualangobiz') }}"; // Perbaikan nama variabel


        // Perbarui URL untuk Barang Keluar
        const urlBarangKeluar = new URL(baseUrlBarangKeluar, window.location.origin);
        if (tanggalPenjualan) {
            urlBarangKeluar.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlBarangKeluar.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('penjualan_kotor_link_modal').href = urlBarangKeluar.toString();

        // Perbarui URL untuk Faktur Penjualan
        const urlFakturPenjualan = new URL(baseUrlFakturPenjualan, window.location.origin); // Perbaikan nama variabel
        if (tanggalPenjualan) {
            urlFakturPenjualan.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturPenjualan.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('faktur_penjualan_link_modal').href = urlFakturPenjualan.toString();

        const urlFakturDeposit = new URL(baseUrlFakturDeposit, window.location.origin); // Perbaikan nama variabel
        if (tanggalPenjualan) {
            urlFakturDeposit.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturDeposit.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('faktur_deposit_masuk_link_modal').href = urlFakturDeposit.toString();

        const urlFakturPenjualanMesinedc = new URL(baseUrlFakturPenjualanMesinedc, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanMesinedc.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturPenjualanMesinedc.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('penjualan_mesinedc_link_modal').href = urlFakturPenjualanMesinedc.toString();

        const urlFakturPenjualanQris = new URL(baseUrlFakturPenjualanQris, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanQris.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturPenjualanQris.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('penjualan_qris_link_modal').href = urlFakturPenjualanQris.toString();

        const urlFakturPenjualanTransfer = new URL(baseUrlFakturPenjualanTransfer, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanTransfer.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturPenjualanTransfer.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('penjualan_transfer_link_modal').href = urlFakturPenjualanTransfer.toString();

        const urlFakturPenjualanGobiz = new URL(baseUrlFakturPenjualanGobiz, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanGobiz.searchParams.set('tanggal_penjualan', tanggalPenjualan);
        }
        if (tokoId) {
            urlFakturPenjualanGobiz.searchParams.set('toko_id', tokoId);
        }
        document.getElementById('penjualan_gobiz_link_modal').href = urlFakturPenjualanGobiz.toString();

    }

    // Pastikan modal dipicu dengan tautan yang benar saat ditampilkan
    $('#penjualanKotorModal').on('show.bs.modal', function () {
        updateModalLink(); // Panggil fungsi untuk memperbarui link di dalam modal
    });

    // Inisialisasi pertama
    document.addEventListener("DOMContentLoaded", function () {
        updateModalLink();
    });
</script>


    <script>
        document.getElementById('tambahInputCheckbox').addEventListener('change', function() {
            const extraRowsContainer = document.getElementById('extraRows');
            
            if (this.checked) {
                // Buat elemen input tambahan (dua set input untuk tanggal_penjualan dan nominal_setoran)
                const newRow1 = document.createElement('div');
                newRow1.className = 'form-group row mb-3';
                newRow1.innerHTML = `
                    <div class="col-sm-3">
                        <input class="form-control" name="tanggal_penjualan[]" type="date">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="nominal_setoran[]" oninput="formatNumber(this);">
                    </div>
                `;

                // Tambahkan kedua row ke container
                extraRowsContainer.appendChild(newRow1);
            } else {
                // Hapus semua input tambahan jika checkbox di-uncheck
                extraRowsContainer.innerHTML = '';
            }
        });
    </script>
    
    <script>
        // Fungsi untuk menghapus format angka
        function unformatNumber(number) {
            return parseFloat(number.replace(/\./g, '').replace(',', '.')) || 0;
        }
    
        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatNumber(input) {
            let value = input.value.replace(/\./g, ''); // Hapus titik sebelumnya
            if (!isNaN(value) && value !== "") {
                input.value = new Intl.NumberFormat('id-ID').format(value);
            }
        }
    
        // Fungsi untuk menghitung nilai plus/minus
        function updatePlusMinus() {
            const nominalSetoran = unformatNumber(document.getElementById('nominal_setoran').value);
            const totalSetoran = unformatNumber(document.getElementById('total_setoran').value);
            const plusMinus = nominalSetoran - totalSetoran;
    
            document.getElementById('plusminus').value = new Intl.NumberFormat('id-ID').format(plusMinus);
        }
    </script>
    

    <script>
        document.getElementById('tambahInput').addEventListener('click', function() {
            // Buat elemen div untuk row baru
            const newRow = document.createElement('div');
            newRow.className = 'form-group row mb-3';
    
            // Buat elemen input untuk tanggal_penjualan
            const dateInput = document.createElement('input');
            dateInput.type = 'date';
            dateInput.name = 'tanggal_penjualan[]'; // Ganti menjadi array untuk menampung beberapa input
            dateInput.className = 'form-control col-sm-3';
    
            // Buat elemen input untuk total_setoran
            const totalInput = document.createElement('input');
            totalInput.type = 'text';
            totalInput.name = 'total_setoran[]'; // Ganti menjadi array untuk menampung beberapa input
            totalInput.className = 'form-control col-sm-3';
    
            // Tambahkan input ke dalam row
            newRow.appendChild(dateInput);
            newRow.appendChild(totalInput);
    
            // Temukan elemen +/-
            const plusMinusElement = document.querySelector('.form-group.row.mb-3:last-of-type');
    
            // Tambahkan row baru di atas elemen +/-
            plusMinusElement.parentNode.insertBefore(newRow, plusMinusElement);
        });
    </script>
    

    <!-- Tambahkan script JQuery untuk Ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    {{-- <script>
        $(document).ready(function () {
            $('#tanggal_penjualan').on('change', function () {
                var tanggalPenjualan = $(this).val();

                if (tanggalPenjualan) {
                    $.ajax({
                        url: "{{ route('getdata1') }}",
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tanggal_penjualan: tanggalPenjualan
                        },
                        success: function(response) {
                            // Populate form fields with response data
                            $('#setoran_id').val(response.id); // Menampilkan ID setoran
                            $('#penjualan_kotor').val(response.penjualan_kotor);
                            $('#diskon_penjualan').val(response.diskon_penjualan);
                            $('#penjualan_bersih').val(response.penjualan_bersih);
                            $('#deposit_keluar').val(response.deposit_keluar);
                            $('#deposit_masuk').val(response.deposit_masuk);
                            $('#mesin_edc').val(response.mesin_edc);
                            $('#qris').val(response.qris);
                            $('#gobiz').val(response.gobiz);
                            $('#transfer').val(response.transfer);
                            $('#total_penjualan').val(response.total_penjualan);
                            $('#total_setoran').val(response.total_setoran);
                            $('#nominal_setoran').val(response.nominal_setoran);
                            $('#nominal_setoran2').val(response.nominal_setoran2);
                            $('#plusminus').val(response.plusminus);
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText); // Debugging
                        }
                    });
                }
            });
        });
    </script> --}}

    <script>
        $(document).ready(function () {
    $('#btnCari').on('click', function () {
        var tanggalPenjualan = $('#tanggal_penjualan').val();
        var tokoId = $('#toko').val();

        if (tanggalPenjualan) {
            $.ajax({
                url: "{{ url('admin/get-penjualan1') }}", // Sesuaikan URL sesuai rute Anda
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_penjualan: tanggalPenjualan,
                    toko_id: tokoId // Kirim toko_id
                },
                success: function (response) {
                    // Isi field-form dengan data dari respons
                    $('#setoran_id').val(response.id); 
                    // $('#tanggal_penjualan').val(response.tanggal_penjualan); 
                    $('#penjualan_kotor').val(response.penjualan_kotor);
                    $('#diskon_penjualan').val(response.diskon_penjualan);
                    $('#penjualan_bersih').val(response.penjualan_bersih);
                    $('#deposit_keluar').val(response.deposit_keluar);
                    $('#deposit_masuk').val(response.deposit_masuk);
                    $('#mesin_edc').val(response.mesin_edc);
                    $('#qris').val(response.qris);
                    $('#gobiz').val(response.gobiz);
                    $('#transfer').val(response.transfer);
                    $('#total_penjualan').val(response.total_penjualan);
                    $('#total_setoran').val(response.total_setoran);
                    $('#nominal_setoran').val(response.nominal_setoran);
                    $('#plusminus').val(response.plusminus);
                },
                error: function (xhr) {
                    console.log(xhr.responseText); // Untuk debugging
                }
            });
        } else {
            alert("Silakan pilih tanggal penjualan terlebih dahulu.");
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


@endsection


