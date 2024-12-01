@extends('layouts.app')

@section('title', 'Tambah klasifikasi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Subklasifikasi 1</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/addsub') }}">Subklasifikasi 1</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

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
            <form action="{{ url('admin/addsub') }}" method="POST" enctype="multipart/form-data"
            autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Subklasifikasi 1</h3>
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
                                <th>Kategori</th>
                                <th>Nama Sub Kategori</th>
                                <th>Nama Sub Kategori</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>

                        <tbody id="tabel-pembelian">
                            <tr id="pembelian-0">
                                <td style="width: 70px" class="text-center" id="urutan">1</td>
                                <td style="width: 150px">
                                    <div class="form-group">
                                        <select class="form-control select2bs4" name="klasifikasi_id[]" id="klasifikasi_id-0">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($klasifikasis as $klasifikasi)
                                                <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td style="width: 150px">
                                    <div class="form-group">
                                        <select class="form-control select2bs4" name="subklasifikasi[]" id="subklasifikasi_id-0">
                                            <option value="">Pilih subKategori</option>
                                            @foreach ($subklasifikasis as $subklasifikasi)
                                                <option value="{{ $subklasifikasi->id }}">{{ $subklasifikasi->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nama_barang-0" name="nama_barang[]">
                                    </div>
                                </td>
                                <td style="width: 120px">
                                    <button style="margin-left:5px" type="button" class="btn btn-danger" onclick="removeBan(0)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
           
                <div class="card-footer text-right mt-3">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{-- <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button> --}}
                    <button type="submit" class="btn btn-primary" id="btnSimpan"><i class="fas fa-save"></i></button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            </div>
        </form>
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
            var subklasifikasi_id = '';
            var nama_barang = '';

            if (value !== null) {
                klasifikasi_id = value.klasifikasi_id;
                subklasifikasi_id = value.subklasifikasi_id;
                nama_barang = value.nama_barang;

            }

            // urutan 
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>';

          // klasifikasi 
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

            //subklasifikasi
            item_pembelian += '<td style="width: 150px">';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="subklasifikasi_id-' + key +
                '" name="subklasifikasi_id[]">';
            item_pembelian += '<option value="">Pilih SubKategori</option>';
            item_pembelian += '@foreach ($subklasifikasis as $subklasifikasi_id)';
            item_pembelian +=
                '<option value="{{ $subklasifikasi_id->id }}" {{ $subklasifikasi_id->id == ' + subklasifikasi_id + ' ? 'selected' : '' }}>{{ $subklasifikasi_id->nama }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // nama
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
<script>
    $(document).ready(function() {
        function getSubklasifikasi(klasifikasiID, target) {
            if (klasifikasiID) {
                $.ajax({
                    url: "{{ url('admin/addsub/get_subklasifikasi') }}" + '/' + klasifikasiID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        target.empty();
                        target.append('<option value="">- Pilih -</option>');
                        $.each(data, function(key, value) {
                            target.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                    }
                });
            } else {
                target.empty();
                target.append('<option value="">- Pilih -</option>');
            }
        }
    
        $('#klasifikasi_id-0').on('change', function() {
            var klasifikasiID = $(this).val();
            var target = $('#subklasifikasi_id-0');
            getSubklasifikasi(klasifikasiID, target);
        });
    
        $(document).on('change', '[id^="klasifikasi_id-"]', function() {
            var klasifikasiID = $(this).val();
            var targetID = $(this).attr('id').replace('klasifikasi_id-', 'subklasifikasi_id-');
            var target = $('#' + targetID);
            getSubklasifikasi(klasifikasiID, target);
        });
    });
    </script>
    
@endsection
