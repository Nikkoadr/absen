@extends('layouts.main')
@section('link')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="konten-utama">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">Data Karyawan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Admin</a></li>
            <li class="breadcrumb-item active">Data Karyawan</li>
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
            <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
            <button type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#modal_import"><i class="fa-solid fa-file-import"></i> Import</button>
            @include('layouts.component.modal_import')
            <a href="{{ route('exportuser') }}" class="btn btn-info m-1" target="_blank"><i class="fa-solid fa-file-export" aria-hidden="true"></i> Export</a>
            <button type="button" class="btn btn-success m-1" data-toggle="modal" data-target="#modal_tambah_user"><i class="fa-solid fa-user-plus"></i> Tambah</button>
            @include('layouts.component.modal_tambah_user')
            <button type="button" id="tombolHapusBanyak" class="btn btn-danger m-1 d-none" title="Hapus yang dipilih" aria-label="Hapus karyawan terpilih"><i class="far fa-trash-alt" aria-hidden="true"></i> <span id="jumlahTerpilih" class="badge badge-light">0</span></button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="table_user" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th data-orderable="false" data-searchable="false"><input type="checkbox" id="pilihSemua" aria-label="Pilih semua karyawan"></th>
                        <th>Karyawan</th>
                        <th>Identitas</th>
                        <th>Jam Kerja</th>
                        <th data-orderable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="modalWadah"></div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('script')
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
<script>
$(function () {
$("#table_user").DataTable({
    "processing": true,
    "serverSide": true,
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "pageLength": 25,
    "aLengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ],
    "ajax": {
        "url": "{{ route('data_user.data') }}",
        "dataSrc": function (json) {
            document.getElementById('modalWadah').innerHTML = json.modals || '';
            return json.data;
        },
        "error": function () {
            Presensi.galat('Gagal memuat data karyawan.');
        }
    },
    "columns": [
        { "data": "centang", "orderable": false, "searchable": false },
        { "data": "karyawan", "orderable": true },
        { "data": "identitas", "orderable": false },
        { "data": "jam", "orderable": true },
        { "data": "aksi", "orderable": false, "searchable": false }
    ],
    "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
}).buttons().container().appendTo('#table_user_wrapper .col-md-6:eq(0)');
});
</script>
<script>
@if (session()->has('success'))
Presensi.sukses(@json(session('success')));
@endif
</script>
<script>
Presensi.konfirmasiForm(document.getElementById('table_user'), 'Anda yakin ingin menghapus data ini?');
</script>
<script>
(function () {
    var tombol = document.getElementById('tombolHapusBanyak');
    var jumlah = document.getElementById('jumlahTerpilih');
    var pilihSemua = document.getElementById('pilihSemua');
    var tabel = document.getElementById('table_user');
    var dipilih = new Set(); /* Pilihan bertahan saat pindah halaman tabel. */

    function hitung() {
        var tampil = tabel.querySelectorAll('.pilih-user');
        var nTampilTercentang = 0;
        tampil.forEach(function (c) { if (c.checked) nTampilTercentang++; });
        jumlah.textContent = dipilih.size;
        tombol.classList.toggle('d-none', dipilih.size === 0);
        pilihSemua.checked = tampil.length > 0 && nTampilTercentang === tampil.length;
    }

    tabel.addEventListener('change', function (e) {
        if (e.target.id === 'pilihSemua') {
            tabel.querySelectorAll('.pilih-user').forEach(function (c) {
                c.checked = pilihSemua.checked;
                if (c.checked) { dipilih.add(c.value); } else { dipilih.delete(c.value); }
            });
        } else if (e.target.classList.contains('pilih-user')) {
            if (e.target.checked) { dipilih.add(e.target.value); } else { dipilih.delete(e.target.value); }
        }
        hitung();
    });

    $('#table_user').on('draw.dt', function () {
        tabel.querySelectorAll('.pilih-user').forEach(function (c) { c.checked = dipilih.has(c.value); });
        hitung();
    });

    tombol.addEventListener('click', function () {
        if (!dipilih.size) return;
        Presensi.konfirmasi("Hapus " + dipilih.size + " karyawan terpilih beserta presensinya?").then(function (ya) {
            if (!ya) return;
            Presensi.aman(function () {
                return fetch("{{ route('hapusBanyakUser') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ids: Array.from(dipilih) }),
                }).then(function (r) { return r.json(); }).then(function (j) {
                    if (j.status !== 'sukses') throw new Error(j.message || 'Gagal menghapus.');
                    var dt = $('#table_user').DataTable();
                    (j.ids || []).forEach(function (id) {
                        dipilih.delete(String(id));
                        var input = tabel.querySelector('.pilih-user[value="' + id + '"]');
                        if (input) dt.row(input.closest('tr')).remove();
                    });
                    dt.draw(false);
                    hitung();
                    Presensi.sukses(j.message);
                });
            }, 'Gagal menghapus data terpilih.');
        });
    });

    hitung();
})();
</script>
@endsection
