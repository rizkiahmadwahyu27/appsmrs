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
  $('#table-gabungData').DataTable({
    processing: true,
    ajax: {
        url: '{{ url("/ambil/pasien") }}',
        dataSrc: function(json) {
            console.log(json);
            return json.data;
        }
    },
    scrollY: '60vh',
    scrollCollapse: true,
    paging: true,

    dom: 'Bfrtip', // 🔥 WAJIB

    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
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

</script>
@endsection