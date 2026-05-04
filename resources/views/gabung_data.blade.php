@extends('welcome')
@section('title')
Data Pasien
@endsection

@section('content')
<h2>Data Gabung Pasien</h2>

<div class="flex justify-between items-center bg-amber-200 p-2 rounded-2xl">
    <div>
        <h1>Import Data Pasien</h1>
        <form action="{{ route('import_gabung') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="bg-gray-400 outline-inherit p-1 rounded-lg shadow-2xl">Import Excel</button>
        </form>
    </div>
    <div>
        <h1>Update Data Pasien</h1>
        <form action="{{ route('update_import_gabung') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="bg-gray-400 outline-inherit p-1 rounded-lg shadow-2xl">Import Excel</button>
        </form>
    </div>
</div>

<br>
<div class="mb-3 flex gap-2 items-center">
    Dari: <input type="date" id="from_date" class="border p-1">
    Sampai: <input type="date" id="to_date" class="border p-1">

    <button id="filter" class="bg-blue-500 text-white px-3 py-1 rounded">
        Filter
    </button>

    <button id="export" class="bg-green-500 text-white px-3 py-1 rounded">
        Export Excel
    </button>
</div>
<table id="table-gabungData" class="display" style="width:60%;">
    <thead>
        <tr>
            <th>RM</th>
            <th>Nama</th>
            <th>No SEP</th>
            <th>Kelas</th>
            <th>Naik Kelas</th>
            <th>Tgl Masuk</th>
            <th>Tgl Keluar</th>
            <th>Ruang</th>
            <th>Kamar</th>
            <th>DPJP</th>
            <th>Keterangan</th>
        </tr>
    </thead>
</table>
<script>
var table = $('#table-gabungData').DataTable({
    processing: true,
    ajax: {
        url: '{{ url("/ambil/pasien") }}',
        data: function (d) {
            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();
        }
    },
    scrollY: '60vh',
    scrollCollapse: true,
    paging: true,
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'pdf', 'print'
    ],
    columns: [
        { data: 'rm' },
        { data: 'nama' },
        { data: 'sep' },
        { data: 'kelas' },
        { data: 'naik' },
        { data: 'masuk' },
        { data: 'keluar' },
        { data: 'ruang' },
        { data: 'kamar' },
        { data: 'dpjp' },
        { data: 'ket' },
    ]
});

// 🔥 tombol filter
$('#filter').click(function () {
    table.ajax.reload();
});

// 🔥 tombol export (kirim parameter ke Laravel)
$('#export').click(function () {
    let from = $('#from_date').val();
    let to = $('#to_date').val();

    window.location.href = `/export/pasien?from_date=${from}&to_date=${to}`;
});

</script>
@endsection