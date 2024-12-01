@extends('layouts.app')

@section('title', 'Tambah klasifikasi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Subklasifikasi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Subklasifikasi</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif --}}
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
            <form action="{{ url('admin/tambahkategori') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Subklasifikasi</h3>
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
                                <th class="text-center">No</th>
                                <th>Klasifikasi</th>
                                <th>Nama Sub Klasifikasi</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-pembelian">
                            <tr id="pembelian-0">
                                <td style="width: 70px" class="text-center" id="urutan">1</td>
                                <td style="width: 150px">
                                    <div class="form-group">
                                        <select class="select2bs4 select2-hidden-accessible" name="klasifikasi_id[]"
                                            data-placeholder="Pilih Klasifikasi.." style="width: 100%;" data-select2-id="23"
                                            tabindex="-1" aria-hidden="true" id="klasifikasi_id-0">
                                            <option value="">Pilih Klasifikasi</option>
                                            @foreach ($klasifikasis as $ukuran)
                                                <option value="{{ $ukuran->id }}">{{ $ukuran->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nama_barang-0" 
                                            name="nama_barang[]">
                                    </div>
                                </td>
                              
                               
                 
                                <td style="width: 120px">
                                    {{-- <button type="button" class="btn btn-primary" onclick="barang(0)">
                                        <i class="fas fa-plus"></i>
                                    </button> --}}
                                    <button style="margin-left:5px" type="button" class="btn btn-danger"
                                        onclick="removeBan(0)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
           
                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            </div>
        </form>


            {{-- <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Kategori</h3>
                </div>
                <form action="{{ url('admin/addkategori') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukan nama kategori" value="{{ old('nama') }}">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div> --}}
        </div>
    </section>

<script>

function getData(id) {
            var supplier_id = document.getElementById('supplier_id');
            $.ajax({
                url: "{{ url('admin/pembelian/supplier') }}" + "/" + supplier_id.value,
                type: "GET",
                dataType: "json",
                success: function(supplier_id) {
                    var alamat = document.getElementById('alamat');
                    alamat.value = supplier_id.alamat;
                },
            });
        }
        // Event listener untuk input harga dan jumlah
        $(document).on("input", ".harga, .jumlah", function() {
            var currentRow = $(this).closest('tr');
            var harga = parseFloat(currentRow.find(".harga").val()) || 0;
            var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;
            var total = harga * jumlah;
            currentRow.find(".total").val(total);
        });


        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, false, value);
            });
        }

        function addPesanan() {
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            }

            itemPembelian(jumlah_ban, jumlah_ban - 1, true);
        }

        function removeBan(params) {
            jumlah_ban = jumlah_ban - 1;

            console.log(jumlah_ban);

            var tabel_pesanan = document.getElementById('tabel-pembelian');
            var pembelian = document.getElementById('pembelian-' + params);

            tabel_pesanan.removeChild(pembelian);

            if (jumlah_ban === 0) {
                var item_pembelian = '<tr>';
                item_pembelian += '<td class="text-center" colspan="5">- Barang belum ditambahkan -</td>';
                item_pembelian += '</tr>';
                $('#tabel-pembelian').html(item_pembelian);
            } else {
                var urutan = document.querySelectorAll('#urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
        }

        function itemPembelian(urutan, key, style, value = null) {
            var klasifikasi_id = '';
            var nama_barang = '';

            if (value !== null) {
                klasifikasi_id = value.klasifikasi_id;
                nama_barang = value.nama_barang;

            }

            // urutan 
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';

          // ukuran 
          item_pembelian += '<td style="width: 150px">';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="klasifikasi_id-' + key +
                '" name="klasifikasi_id[]">';
            item_pembelian += '<option value="">Pilih Kategori</option>';
            item_pembelian += '@foreach ($klasifikasis as $klasifikasi_id)';
            item_pembelian +=
                '<option value="{{ $klasifikasi_id->id }}" {{ $klasifikasi_id->id == ' + klasifikasi_id + ' ? 'selected' : '' }}>{{ $klasifikasi_id->nama }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            // nama_barang 
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
                item_pembelian += '<input type="text" class="form-control"  id="nama_barang-' + urutan +'" name="nama_barang[]" value="' + nama_barang + '" ';
                item_pembelian += '</div>';
                item_pembelian += '</td>';              
            
            item_pembelian += '<td style="width: 120px">';
            item_pembelian += '</button>';
            item_pembelian += '<button style="margin-left:5px" type="button" class="btn btn-danger" onclick="removeBan(' +
                urutan + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

  
        

        if (style) {
                select2(key);
            }

            $('#tabel-pembelian').append(item_pembelian);

            $('#klasifikasi_id-' + key + '').val(klasifikasi_id).attr('selected', true);
          
        }

        function select2(id) {
            $(function() {
                $('#klasifikasi_id-' + id).select2({
                    theme: 'bootstrap4'
                });
           
            });
        }
</script>


@endsection
