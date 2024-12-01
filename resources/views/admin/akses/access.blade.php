@extends('layouts.app')

@section('title', 'Hak Akses')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Hak Akses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/akses') }}">Hak akses</a>
                        </li>
                        <li class="breadcrumb-item active">Lihat</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">

            @if($level == 'toko_banjaran')
                <!-- Card untuk Toko Banjaran -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Toko Banjaran</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ url('admin/akses-access/' . $akses->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <input type="checkbox" id="option-all" onchange="checkAll(this)">
                            <label for="option-all">Select All</label>
                            <br>
                            @foreach ($menus as $menu)
                                @if(in_array($menu, ['stok tokobanjaran',
                                                     'produk',
                                                     'pelanggan',
                                                     'pemesanan banjaran',
                                                     'penjualan banjaran',
                                                     'pelunasan banjaran',]))

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="menu[]" value="{{ $menu }}" {{ in_array($menu, $akses->menu) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ ucfirst($menu) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>

            @elseif($level == 'toko_bumiayu')
                <!-- Card untuk Admin -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Admin</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ url('admin/akses-access/' . $akses->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <input type="checkbox" id="option-all" onchange="checkAll(this)">
                            <label for="option-all">Select All</label>
                            <br>
                            @foreach ($menus as $menu)
                                @if(!in_array($menu, ['stok tokobanjaran',
                                                     'pemesanan banjaran',
                                                     'penjualan banjaran',
                                                     'pelunasan banjaran',]))
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="menu[]" value="{{ $menu }}" {{ in_array($menu, $akses->menu) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ ucfirst($menu) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
        
            @elseif($level == 'admin')
                <!-- Card untuk Admin -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Admin</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ url('admin/akses-access/' . $akses->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <input type="checkbox" id="option-all" onchange="checkAll(this)">
                            <label for="option-all">Select All</label>
                            <br>
                            @foreach ($menus as $menu)
                                @if(!in_array($menu, ['stok tokobanjaran',
                                                     'pemesanan banjaran',
                                                     'penjualan banjaran',
                                                     'pelunasan banjaran',]))
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="menu[]" value="{{ $menu }}" {{ in_array($menu, $akses->menu) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ ucfirst($menu) }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </section>
    

    
    <script>
        function checkAll(source) {
            checkboxes = document.getElementsByName('menu[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
    

    <script>
        var checkboxes = document.querySelectorAll("input[type = 'checkbox']");

        function checkAll(myCheckbox) {
            if (myCheckbox.checked == true) {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        }
    </script>
@endsection
