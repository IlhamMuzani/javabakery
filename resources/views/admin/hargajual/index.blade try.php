@extends('layouts.app')

@section('title', 'Data produk')

<style>
    .btn-xs {
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    .btn {
        padding: 10px 20px;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-danger {
        color: white;
        background-color: red;
    }
    .btn-success {
        color: white;
        background-color: green;
    }
    .icon-separator {
        margin: 0 10px;
    }
</style>

@section('content')
<div class="content-header">
    
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Harga Jual Banjaran</h3>
           
                <div class="float-right">
                    <select class="form-control" id="kategori1" name="kategori1">
                        <option value="">- Pilih -</option>
                        <option value="banjaran" {{ old('kategori1') == 'banjaran' ? 'selected' : '' }}>BANJARAN</option>
                        <option value="tegal" {{ old('kategori1') == 'tegal' ? 'selected' : '' }}>TEGAL</option>
                        <option value="slawi" {{ old('kategori1') == 'slawi' ? 'selected' : '' }}>SLAWI</option>
                        <option value="pemalang" {{ old('kategori1') == 'pemalang' ? 'selected' : '' }}>PEMALANG</option>
                        <option value="bumiayu" {{ old('kategori1') == 'bumiayu' ? 'selected' : '' }}>BUMIAYU</option>
                        <option value="cilacap" {{ old('kategori1') == 'cilacap' ? 'selected' : '' }}>CILACAP</option>
                    </select>
                </div>
            </div>
   
            <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align: center;">No</th>
                                <th rowspan="3" style="text-align: center;">Kode Produk</th>
                                <th rowspan="3" style="text-align: center;">Nama Produk</th>
                                <th rowspan="3" style="text-align: center;">Harga Awal</th>
                                <th rowspan="3" style="text-align: center;">+</th>
                                <th colspan="4" style="text-align: center;">Toko Banjaran</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">Member</th>
                                <th colspan="2" style="text-align: center;">Non Member</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($produk as $index => $item)
                            <tr id="row-{{ $loop->index }}">
                                <form id="update-harga-form-{{ $index }}" method="POST" action="{{ route('update.harga') }}">
                                    @csrf
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_lama }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" id="update-button-{{ $loop->index }}" onclick="updateHarga({{ $loop->index }}, {{ $item->id }})">
                                            <i class="fa fa-save" id="icon-{{ $loop->index }}"></i>
                                        </button>
                                    </td>
   
                                     {{-- Banjaran --}}
                                     <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_bnjr" id="member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->member_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_bnjr" id="diskon-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->member_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_bnjr" id="non-member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->non_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_bnjr" id="diskon-non-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->non_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
</section>
@endsection

{{-- <script>
    // Variabel untuk menyimpan DataTable instance
    let tableBanjaran;
    let tableTegal;
    let tablePemalang;
    let tableSlawi;
    let tableBumiayu;
    let tableCilacap;

    // Fungsi untuk menampilkan tabel dan menginisialisasi DataTable
    function showTable(toko) {
        // Sembunyikan semua tabel toko terlebih dahulu
        let tables = document.querySelectorAll('[id^="tabel"]');
        tables.forEach(table => table.style.display = 'none');

        // Hapus DataTable jika sebelumnya sudah diinisialisasi
        if ($.fn.DataTable.isDataTable('#datatables1')) {
            $('#datatables1').DataTable().destroy();
        }

        // Tampilkan dan inisialisasi DataTable sesuai toko yang dipilih
        if (toko === 'banjaran') {
            document.getElementById('tabelBanjaran').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian aktif
            tableBanjaran = $('#datatables1').DataTable({
                searching: true,
                paging: true,
                info: true,
            });

        } else if (toko === 'tegal') {
            document.getElementById('tabelTegal').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian non-aktif
            tableTegal = $('#datatables1').DataTable({
                searching: false,
                paging: true,
                info: true,
            });
        }else if (toko === 'pemalang') {
            document.getElementById('tabelPemalang').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian non-aktif
            tableTegal = $('#datatables1').DataTable({
                searching: false,
                paging: true,
                info: true,
            });
        }else if (toko === 'slawi') {
            document.getElementById('tabelSlawi').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian non-aktif
            tableTegal = $('#datatables1').DataTable({
                searching: false,
                paging: true,
                info: true,
            });
        }else if (toko === 'bumiayu') {
            document.getElementById('tabelBumiayu').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian non-aktif
            tableTegal = $('#datatables1').DataTable({
                searching: false,
                paging: true,
                info: true,
            });
        }else if (toko === 'cilacap') {
            document.getElementById('tabelCilacap').style.display = 'block';

            // Inisialisasi DataTable dengan fitur pencarian non-aktif
            tableTegal = $('#datatables1').DataTable({
                searching: false,
                paging: true,
                info: true,
            });
        }
    }

    // Inisialisasi tampilan tabel berdasarkan pilihan default
    document.addEventListener('DOMContentLoaded', () => {
        showTable(document.getElementById('tokoSelect').value);
    });

</script> --}}


<script>
        function updateHarga(index, id) {
        const form = document.getElementById(`update-harga-form-${index}`);
        const formData = new FormData(form);
        formData.append('id', id);

        fetch('{{ route('update.harga') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Jika dibutuhkan untuk keamanan
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Harga berhasil diperbarui.',
                    timer: 1000,
                    showConfirmButton: false
                });

                // Ubah kembali button dan icon setelah sukses update
                const updateButton = document.getElementById(`update-button-${index}`);
                const icon = document.getElementById(`icon-${index}`);

                // Ubah tombol menjadi success dan ubah ikon menjadi check
                updateButton.classList.remove('btn-danger');
                updateButton.classList.add('btn-success');
                icon.classList.remove('fa-edit');
                icon.classList.add('fa-check');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengupdate harga.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    function markAsChanged(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        // Ubah tombol dan icon menjadi tampilan "perubahan" (edit) saat ada input yang diubah
        updateButton.classList.remove('btn-success');
        updateButton.classList.add('btn-danger');
        icon.classList.remove('fa-check');
        icon.classList.add('fa-edit');
    }

</script>


<script>
   document.getElementById('kategori1').addEventListener('change', function() {
    var selectedValue = this.value;

    if (selectedValue === 'banjaran') {
        window.location.href = "{{ url('admin/hargajual') }}";
    } else if (selectedValue === 'tegal') {
        window.location.href = "{{ route('indextegal') }}";
    }
});

</script>


<script>
    $(document).ready(function() {
        // Detect the change event on the 'status' dropdown
        $('#statusx').on('change', function() {
            // Get the selected value
            var selectedValue = $(this).val();

            // Check the selected value and redirect accordingly
            switch (selectedValue) {
                case 'laporandetail':
                    window.location.href = "{{ url('admin/laporan_mobillogistik') }}";
                    break;
                case 'laporanglobal':
                    window.location.href = "{{ url('admin/laporan_mobillogistikglobal') }}";
                    break;
                    // case 'akun':
                    //     window.location.href = "{{ url('admin/laporan_pengeluarankaskecilakun') }}";
                    //     break;
                    // case 'memo_tambahan':
                    //     window.location.href = "{{ url('admin/laporan_saldokas') }}";
                    //     break;
                default:
                    // Handle other cases or do nothing
                    break;
            }
        });
    });
</script>



