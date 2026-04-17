@extends('welcome')
@section('title')
Data Pasien
@endsection

@section('content')
<h2>Data Pasien</h2>

<form action="{{ url('/pasien/import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit" class="bg-gray-400 outline-inherit p-2 rounded-lg shadow-2xl">Import Excel</button>
</form>

<br>

<table id="table-pasien" class="display" style="width:60%;">
    <thead>
        <tr>
            <th>RM</th>
            <th>Nama</th>
            <th>No SEP</th>
            <th>Kelas</th>
            <th>Naik Kelas</th>
            <th>Status</th>
            <th>Tgl Masuk</th>
            <th>Tgl Keluar</th>
            <th>DPJP</th>
            <th>Ruangan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
</table>

@endsection