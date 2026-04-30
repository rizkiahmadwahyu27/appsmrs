<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <!-- Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- Export dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <style>
      #table-pasien {
          font-size: 10px;  /* ukuran font kecil, sesuaikan angkanya */
      }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen">

  <!-- Sidebar -->
  <div id="sidebar"
       class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white transform -translate-x-full md:translate-x-0 md:static md:inset-0 transition duration-200 ease-in-out">

    <div class="p-4 text-xl font-bold border-b border-gray-700">
      My Dashboard
    </div>

    <nav class="p-4 space-y-2">
      <a href="#" class="block p-2 rounded hover:bg-gray-700">Dashboard</a>
      <a href="#" class="block p-2 rounded hover:bg-gray-700">Users</a>
      <a href="{{route('data_pasien')}}" class="block p-2 rounded hover:bg-gray-700">Data Pasien</a>
      <a href="{{route('gabung_data')}}" class="block p-2 rounded hover:bg-gray-700">Gabung Data</a>
      <a href="#" class="block p-2 rounded hover:bg-gray-700">Settings</a>
    </nav>

  </div>

  <!-- Content -->
  <div class="flex-1 flex flex-col">

    <!-- Navbar -->
    <header class="bg-white shadow p-4 flex items-center md:hidden">
      <button onclick="toggleSidebar()" class="text-gray-700">
        ☰
      </button>
      <h1 class="ml-4 font-bold">Dashboard</h1>
    </header>

    <!-- Main -->
    <main class="p-6">
      @yield('content')
    </main>

  </div>

</div>

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('-translate-x-full');
}
</script>

<script>
  $('#table-pasien').DataTable({
    processing: true,
    ajax: {
        url: '{{ url("/pasien/data") }}',
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
        { data: 'no_sep' },
        { data: 'kelas' },
        { data: 'naik_kelas' },
        { data: 'status' },
        { data: 'tgl_masuk' },
        { data: 'tgl_keluar' },
        { data: 'dpjp' },
        { data: 'ruangan' },
        { data: 'keterangan' }
    ]
});

</script>

</body>
</html>