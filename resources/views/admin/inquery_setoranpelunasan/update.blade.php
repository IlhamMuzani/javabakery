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
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('toko_banjaran/pelanggan') }}">Pelanggan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
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
        <form action="{{ route('inquery_setoranpelunasan.update_status') }}" method="POST" enctype="multipart/form-data">
            @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                 value="{{ old('tanggal_penjualan', Request::get('tanggal_penjualan')) }}" onchange="updateLink()" />
                            </div>                    
                        </div>
                
                        <div class="card-body">

                                <input type="text" id="setoran_id" name="id" class="form-control" hidden/>
                                <div id="alert-message" class="alert alert-danger d-none" role="alert">
                                    Ceklis semua terlebih dahulu sebelum menyimpan data.
                                </div>
                            <!-- Tempat untuk menampilkan Penjualan Kotor -->
                            <div class="form-group row mb-3">
                                <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="{{ route('print.penjualan.kotor') }}" target="_blank" class="text-decoration-none">Penjualan Kotor</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_penjualan_kotor" onchange="toggleGreenCheck('penjualan_kotor')">
                                </div>
                                <div class="col-sm-3">
                                    <input readonly type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" 
                                    value="{{ old('penjualan_kotor', number_format($setoranPenjualan->penjualan_kotor, 0, ',', '.')) }}"
>
                                </div>
                               
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_penjualan_kotor">
                                        ✓
                                    </button>
                                </div>
                            </div>
                            <!-- Diskon Penjualan -->
                            <div class="form-group row mb-3">
                                <label for="diskon_penjualan" class="col-sm-3 col-form-label">
                                    <a id="diskon_penjualan_link" href="{{ route('print.diskon.penjualan') }}" target="_blank" class="text-decoration-none">Diskon Penjualan</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_diskon_penjualan" onchange="toggleGreenCheck('diskon_penjualan')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input readonly type="text" class="form-control" id="diskon_penjualan" name="diskon_penjualan"
                                    value="{{ old('diskon_penjualan', number_format($setoranPenjualan->diskon_penjualan, 0, ',', '.')) }}" >
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
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Penjualan Bersih</a>
                                </label>
                                <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_penjualan_bersih" onchange="toggleGreenCheck('penjualan_bersih')">
                                </div>                            
                                <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="penjualan_bersih" name="penjualan_bersih"
                                        value="{{ old('penjualan_bersih', number_format($setoranPenjualan->penjualan_bersih, 0, ',', '.')) }}" >
                                </div>
                                <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_penjualan_bersih">
                                            ✓
                                        </button>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_keluar" class="col-sm-3 col-form-label">
                                        <a id="deposit_keluar_link" href="{{ route('print.deposit.keluar') }}" target="_blank" class="text-decoration-none">Deposit Keluar</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_deposit_keluar" onchange="toggleGreenCheck('deposit_keluar')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="deposit_keluar" name="deposit_keluar"    
                                        value="{{ old('deposit_keluar', number_format($setoranPenjualan->deposit_keluar, 0, ',', '.')) }}">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_deposit_keluar">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_masuk" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Deposit Masuk</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_deposit_masuk" onchange="toggleGreenCheck('deposit_masuk')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="deposit_masuk" name="deposit_masuk"
                                        value="{{ old('deposit_masuk', number_format($setoranPenjualan->deposit_masuk, 0, ',', '.')) }}" >
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
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Total Penjualan</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_total_penjualan" onchange="toggleGreenCheck('total_penjualan')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="total_penjualan" name="total_penjualan"
                                        value="{{ old('total_penjualan', number_format($setoranPenjualan->total_penjualan, 0, ',', '.')) }}" >
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_total_penjualan">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="mesin_edc" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Mesin EDC</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_mesin_edc" onchange="toggleGreenCheck('mesin_edc')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="mesin_edc" name="mesin_edc"
                                        value="{{ old('mesin_edc', number_format($setoranPenjualan->mesin_edc, 0, ',', '.')) }}" >
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_mesin_edc">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="qris" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">QRIS</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_qris" onchange="toggleGreenCheck('qris')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="qris" name="qris"
                                        
                                        value="{{ old('qris', number_format($setoranPenjualan->qris, 0, ',', '.')) }}" >
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_qris">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="gobiz" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Gobiz</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_gobiz" onchange="toggleGreenCheck('gobiz')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="gobiz" name="gobiz"
                                        value="{{ old('gobiz', number_format($setoranPenjualan->gobiz, 0, ',', '.')) }}">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success d-none" id="btn_gobiz">
                                            ✓
                                        </button>
                                    </div>
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="transfer" class="col-sm-3 col-form-label">
                                        <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Transfer</a>
                                    </label>
                                    <div>
                                        <input type="checkbox" class="form-check-input custom-checkbox" id="check_transfer" onchange="toggleGreenCheck('transfer')">
                                    </div>                            
                                    <div class="col-sm-3">
                                        <input readonly type="text" class="form-control" id="transfer" name="transfer"
                                        value="{{ old('transfer', number_format($setoranPenjualan->transfer, 0, ',', '.')) }}" >
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
                                    <input readonly type="text" class="form-control" id="total_setoran" name="total_setoran"
                                    value="{{ old('total_setoran', number_format($setoranPenjualan->total_setoran, 0, ',', '.')) }}">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_total_setoran">
                                        ✓
                                    </button>
                                </div>
                            </div>

                            @if(is_null($setoranPenjualan->nominal_setoran))
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="tambahInputCheckbox">
                                <label class="form-check-label" for="tambahInputCheckbox">2 x setoran</label>
                                </div>
                            <div class="form-group row mb-3" id="row1">
                                <div class="col-sm-3">
                                    <input class="form-control" id="tanggal_setoran" name="tanggal_setoran" type="date" value="{{ Request::get('tanggal_setoran') }}" />
                                </div>  
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="nominal_setoran" name="nominal_setoran" oninput="formatNumber(this); updatePlusMinus();">
                                </div>
                            </div>
                
                            <!-- Tempat tambahan input ketika checkbox di centang -->
                            <div id="extraRows"></div>
                         
                            @else

                            <div class="form-group row mb-3">
                                <label for="nominal_setoran" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Nominal Setoran</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_nominal_setoran" onchange="toggleGreenCheck('nominal_setoran')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="nominal_setoran" name="nominal_setoran" 
                                    value="{{ old('nominal_setoran', number_format($setoranPenjualan->nominal_setoran, 0, ',', '.')) }}">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_nominal_setoran">
                                        ✓
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <div class="col-sm-3">
                                    <input class="form-control" id="tanggal_setoran" name="tanggal_setoran" type="date" value="{{ Request::get('tanggal_setoran') }}" />
                                </div>  
                            
                            </div>
                            
                            @if($setoranPenjualan->nominal_setoran2 !== null) <!-- Kondisi untuk memeriksa nilai nominal_setoran2 -->
                            <div class="form-group row mb-3">
                                <label for="nominal_setoran2" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Nominal Setoran 2</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_nominal_setoran2" onchange="toggleGreenCheck('nominal_setoran2')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="nominal_setoran2" name="nominal_setoran2"
                                    value="{{ old('nominal_setoran2', number_format($setoranPenjualan->nominal_setoran2, 0, ',', '.')) }}">
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_nominal_setoran2">
                                        ✓
                                    </button>
                                </div>
                            </div>
                            @endif
                            @endif
                            
                            <div class="form-group row mb-3">
                                <label for="plusminus" class="col-sm-3 col-form-label">
                                    <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">+/-</a>
                                </label>
                                <div>
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="check_plusminus" onchange="toggleGreenCheck('plusminus')">
                                </div>                            
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="plusminus" name="plusminus"
                                    value="{{ old('plusminus', number_format($setoranPenjualan->plusminus, 0, ',', '.')) }}" >
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-success d-none" id="btn_plusminus">
                                        ✓
                                    </button>
                                </div>
                            </div>
                            

                            <div class="col-sm-3 offset-sm-3">
                                <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>
                            <!-- Checkbox untuk memunculkan 2 input tambahan -->
                            
                        </div>       
                        </div>   
                        <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

        </div>
        
    </section>
    <script>
        function validateForm(event) {
            // Ambil status checkbox
            const checkPenjualanKotor = document.getElementById('check_penjualan_kotor').checked;
            const checkDiskonPenjualan = document.getElementById('check_diskon_penjualan').checked;
            const checkPenjualanBersih = document.getElementById('check_penjualan_bersih').checked;
            const checkDeposiMasuk = document.getElementById('check_deposit_masuk').checked;
            const checkDepositKeluar = document.getElementById('check_deposit_keluar').checked;
            const checkTotalPenjualan = document.getElementById('check_total_penjualan').checked;
            const checkMesinEdc = document.getElementById('check_mesin_edc').checked;
            const checkGobiz = document.getElementById('check_gobiz').checked;
            const checkTransfer= document.getElementById('check_transfer').checked;
            const checkTotalSetoran = document.getElementById('check_total_setoran').checked;
        
            // Cek apakah semua checkbox dicentang
            if (!checkPenjualanKotor || !checkDiskonPenjualan || !checkPenjualanBersih || !checkDeposiMasuk || !checkDepositKeluar ||
                !checkTotalPenjualan || !checkMesinEdc || !checkGobiz || !checkTransfer || !checkTotalSetoran
            ) {
                event.preventDefault(); // Mencegah pengiriman formulir
                document.getElementById('alert-message').classList.remove('d-none'); // Tampilkan pesan alert
            } else {
                document.getElementById('alert-message').classList.add('d-none'); // Sembunyikan pesan alert jika semua dicentang
            }
        }
        
        // Tambahkan event listener pada form
        document.querySelector('form').addEventListener('submit', validateForm);
        </script>
        
  
    {{-- <script>
        function updateLink() {
            const tanggalPenjualan = document.getElementById('tanggal_penjualan').value;
            const baseUrl = "{{ route('print.penjualan.kotor') }}"; // Menggunakan route Laravel
            const url = new URL(baseUrl);
    
            // Tambahkan parameter tanggal_penjualan ke URL
            if (tanggalPenjualan) {
                url.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            } else {
                url.searchParams.delete('tanggal_penjualan'); // Hapus jika tidak ada tanggal
            }
    
            // Update href link
            document.getElementById('penjualan_kotor_link').href = url.toString();
        }
    </script> --}}

    <script>
        function updateLink() {
            const tanggalPenjualan = document.getElementById('tanggal_penjualan').value;
            const baseUrlKotor = "{{ route('print.penjualan.kotor') }}"; // Menggunakan route Laravel
            const urlKotor = new URL(baseUrlKotor);
            
            // Tambahkan parameter tanggal_penjualan ke URL Penjualan Kotor
            if (tanggalPenjualan) {
                urlKotor.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            } else {
                urlKotor.searchParams.delete('tanggal_penjualan'); // Hapus jika tidak ada tanggal
            }
            
            // Update href link Penjualan Kotor
            document.getElementById('penjualan_kotor_link').href = urlKotor.toString();
            
            // Update href link Diskon Penjualan
            const baseUrlDiskon = "{{ route('print.diskon.penjualan') }}"; // Menggunakan route Laravel
            const urlDiskon = new URL(baseUrlDiskon);
            
            // Tambahkan parameter tanggal_penjualan ke URL Diskon Penjualan
            if (tanggalPenjualan) {
                urlDiskon.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            } else {
                urlDiskon.searchParams.delete('tanggal_penjualan'); // Hapus jika tidak ada tanggal
            }
            
            // Update href link Diskon Penjualan
            document.getElementById('diskon_penjualan_link').href = urlDiskon.toString();
        }
    </script>

    <script>
        function toggleGreenCheck(inputId) {
            // Mendapatkan checkbox, input, dan tombol centang hijau berdasarkan ID input
            var checkbox = document.getElementById('check_' + inputId);
            var button = document.getElementById('btn_' + inputId);
            
            // Jika checkbox diceklis, tampilkan tombol centang hijau, jika tidak sembunyikan
            if (checkbox.checked) {
                button.classList.remove('d-none');
            } else {
                button.classList.add('d-none');
            }
        }
    </script>
    
    {{-- <script>
        document.getElementById('tambahInputCheckbox').addEventListener('change', function() {
            const extraRowsContainer = document.getElementById('extraRows');
            
            if (this.checked) {
                // Buat elemen input tambahan (dua set input untuk tanggal_setoran dan nominal_setoran)
                const newRow1 = document.createElement('div');
                newRow1.className = 'form-group row mb-3';
                newRow1.innerHTML = `
                    <div class="col-sm-3">
                        <input class="form-control" name="tanggal_setoran2" type="date">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="nominal_setoran2" oninput="formatNumber(this);">
                    </div>
                `;

                // Tambahkan kedua row ke container
                extraRowsContainer.appendChild(newRow1);
            } else {
                // Hapus semua input tambahan jika checkbox di-uncheck
                extraRowsContainer.innerHTML = '';
            }
        });
    </script> --}}
    
     <script>
        document.getElementById('tambahInputCheckbox').addEventListener('change', function() {
            const extraRowsContainer = document.getElementById('extraRows');
            
            if (this.checked) {
                // Buat elemen input tambahan
                const newRow1 = document.createElement('div');
                newRow1.className = 'form-group row mb-3';
                newRow1.innerHTML = `
                    <div class="col-sm-3">
                        <input class="form-control" name="tanggal_setoran2" type="date">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="nominal_setoran2" oninput="formatNumber(this); updatePlusMinus();">
                    </div>
                `;
    
                // Tambahkan kedua row ke container
                extraRowsContainer.appendChild(newRow1);
            } else {
                // Hapus semua input tambahan jika checkbox di-uncheck
                extraRowsContainer.innerHTML = '';
            }
        });
    
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
            const totalSetoran = unformatNumber(document.getElementById('total_setoran').value);
            let totalNominalSetoran = 0;
    
            // Ambil semua input nominal_setoran dan hitung totalnya
            const nominalInputs = document.querySelectorAll('input[name^="nominal_setoran"]');
            nominalInputs.forEach(input => {
                totalNominalSetoran += unformatNumber(input.value);
            });
    
            // Hitung selisih antara total setoran dan total nominal setoran
            const plusMinus = totalNominalSetoran - totalSetoran;
    
            // Update nilai plusminus dengan format yang benar
            document.getElementById('plusminus').value = new Intl.NumberFormat('id-ID').format(plusMinus);
        }
    </script>
    

    <script>
        document.getElementById('tambahInput').addEventListener('click', function() {
            // Buat elemen div untuk row baru
            const newRow = document.createElement('div');
            newRow.className = 'form-group row mb-3';
    
            // Buat elemen input untuk tanggal_setoran
            const dateInput = document.createElement('input');
            dateInput.type = 'date';
            dateInput.name = 'tanggal_setoran[]'; // Ganti menjadi array untuk menampung beberapa input
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

<script>
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
                        $('#plusminus').val(response.plusminus);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // Debugging
                    }
                });
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


