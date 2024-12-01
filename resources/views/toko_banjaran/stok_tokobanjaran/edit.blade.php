                                    <td>{{ $produk->kode_lama }}</td>
                                    @extends('layouts.app')

                                    @section('title', 'Data Stok Toko')
                                    
                                    @section('content')
                                        <!-- Content Header (Page header) -->
                                        <div class="content-header">
                                            <div class="container-fluid">
                                                <div class="row mb-2">
                                                    <div class="col-sm-6">
                                                        <h1 class="m-0">Data Stok Toko Banjaran</h1>
                                                    </div><!-- /.col -->
                                                    <div class="col-sm-6">
                                                        <ol class="breadcrumb float-sm-right">
                                                            <li class="breadcrumb-item active">Data Stok Banjaran</li>
                                                        </ol>
                                                    </div><!-- /.col -->
                                                </div><!-- /.row -->
                                            </div><!-- /.container-fluid -->
                                        </div>
                                        <!-- /.content-header -->
                                    
                                        <!-- Main content -->
                                        <section class="content">
                                            <div class="container-fluid">
                                                @if (session('success'))
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Success!',
                                                                text: '{{ session('success') }}',
                                                                timer: 1000,
                                                                showConfirmButton: false
                                                            });
                                                        });
                                                    </script>
                                                @endif
                                                <div class="card-body">
                                                    
                                                    <form action="{{ route('stok_tokobanjaran.update', $produk->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                        
                                                        <div class="form-group">
                                                            <label for="nama_produk">Nama Produk</label>
                                                            <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="form-control" disabled>
                                                        </div>
                                        
                                                        <div class="form-group">
                                                            <label for="jumlah">Jumlah Stok</label>
                                                            <input type="number" name="jumlah" value="{{ $produk->jumlah }}" class="form-control" required>
                                                        </div>
                                        
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </section>
                                        
                                        <script>
                                            function filterSubKlasifikasi() {
                                                var klasifikasiId = document.getElementById('klasifikasi').value;
                                                var subKlasifikasiSelect = document.getElementById('subklasifikasi');
                                                var subKlasifikasiOptions = subKlasifikasiSelect.options;
                                        
                                                // Show all options initially
                                                for (var i = 0; i < subKlasifikasiOptions.length; i++) {
                                                    var option = subKlasifikasiOptions[i];
                                                    if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                                                        option.style.display = "block";
                                                    } else {
                                                        option.style.display = "none";
                                                    }
                                                }
                                        
                                                // Automatically select the first valid option if any
                                                var foundValidOption = false;
                                                for (var i = 1; i < subKlasifikasiOptions.length; i++) { // Skip the first option (default)
                                                    var option = subKlasifikasiOptions[i];
                                                    if (option.style.display === "block") {
                                                        subKlasifikasiSelect.selectedIndex = i;
                                                        foundValidOption = true;
                                                        break;
                                                    }
                                                }
                                                if (!foundValidOption) {
                                                    subKlasifikasiSelect.selectedIndex = 0; // Select default if no valid option found
                                                }
                                            }
                                        
                                            // Initialize the filter on page load to show the correct subklasifikasi options
                                            document.addEventListener('DOMContentLoaded', function() {
                                                filterSubKlasifikasi();
                                            });
                                        </script>
                                    @endsection
                                    