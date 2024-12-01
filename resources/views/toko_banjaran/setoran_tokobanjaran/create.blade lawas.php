    @extends('layouts.app')

@section('title', 'Tambah Setoran')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Setoran Tunai Penjualan</h1>
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
    

            <form id="setoranForm" action="{{ url('toko_banjaran/setoran_tokobanjaran') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-3 mb-3">
                            <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                value="{{ Request::get('tanggal_penjualan') }}" />
                        </div>                    
                    </div>
            
                    <div class="card-body">
                        <!-- Tempat untuk menampilkan Penjualan Kotor -->
                        <div class="form-group row mb-3">
                            <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Penjualan Kotor</a>
                            </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" placeholder="" >
                            </div>
                        </div>

                        <!-- Diskon Penjualan -->
                        <div class="form-group row mb-3">
                            <label for="diskon_penjualan" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Diskon Penjualan</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="diskon_penjualan" name="diskon_penjualan" >
                            </div>
                        </div>

                            <div class="col-sm-3 offset-sm-3">
                                <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>
                    
                        <!-- Penjualan Bersih -->
                        <div class="form-group row mb-3">
                            <label for="penjualan_bersih" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Penjualan Bersih</a>
                            </label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="penjualan_bersih" name="penjualan_bersih" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="deposit_keluar" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Deposit Keluar</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="deposit_keluar" name="deposit_keluar" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="deposit_masuk" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Deposit Masuk</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="deposit_masuk" name="deposit_masuk" >
                            </div>
                        </div>
                        
                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

                        <div class="form-group row mb-3">
                            <label for="total_penjualan" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Total Penjualan</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="total_penjualan" name="total_penjualan" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="mesin_edc" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Mesin EDC</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="mesin_edc" name="mesin_edc" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="qris" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">QRIS</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="qris" name="qris" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="gobiz" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Gobiz</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="gobiz" name="gobiz" >
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="transfer" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">transfer</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="transfer" name="transfer" >
                            </div>
                        </div>

                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

                        <div class="form-group row mb-3">
                            <label for="total_setoran" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Total Setoran</a>
                            </label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="total_setoran" name="total_setoran" >
                            </div>
                        </div>

                        <div class="col-sm-3 offset-sm-3">
                            <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                        </div>

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
                        
                        <div class="form-group row mb-3">
                            <label for="plusminus" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">+/-</a>
                            </label>                             
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="plusminus" name="plusminus"> 
                            </div>
                        </div>
                        
                    </div>       
                    </div>   
                    <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
        
    </section>


    <script>
        document.getElementById('setoranForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah pengiriman form default
        
            let formData = new FormData(this);
        
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.url) {
                    window.open(data.url, '_blank');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
    
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
            // Hapus semua titik dan ganti koma dengan titik
            number = number.replace(/\./g, '').replace(',', '.');
            
            // Cek jika ada lebih dari satu titik setelah dihapus
            const parts = number.split('.');
            if (parts.length > 2) {
                return 0; // Mengembalikan 0 jika terdapat lebih dari satu titik
            }

            return parseFloat(number) || 0;
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
    
    
<!-- Tambahkan script JQuery untuk Ajax -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function () {
        $('#tanggal_penjualan').on('change', function () {
            var tanggalPenjualan = $(this).val();
    
            if (tanggalPenjualan) {
                $.ajax({
    url: "{{ url('toko_banjaran/get-penjualan-kotor') }}", // Gunakan URL yang sesuai
    type: "POST",
    data: {
        _token: '{{ csrf_token() }}',
        tanggal_penjualan: tanggalPenjualan
    },
    success: function(response) {
        // Populate form fields with response data
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


