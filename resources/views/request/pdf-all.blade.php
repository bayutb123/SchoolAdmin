<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
</head>
<style>
    table {
        border-collapse: collapse;
    }
</style>

<body>
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Pengadaan Fasilitas') }} <span
            class="badge badge-secondary">{{ $widget['requests']->count() }}</span></h1>

    <div class="col-lg-12 mb-4">
        <p>{{ $widget['date'] }}</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width=15%>Penanggungjawab</th>
                    <th width=10%>Ruang Lingkup</th>
                    <th>Deskripsi</th>
                    <th width=15%>Kondisi</th>
                    <th width=15%>Total Biaya</th>
                    <th width=12%>Terakhir Diubah</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['requests'] as $request)
                    <tr>
                        <td>{{ $request->authorName }}</td>
                        <td>{{ $request->roomName }}</td>
                        <td>{{ $request->description }}</td>
                        <td>
                            <span class="badge badge-{{ $request->statusColor }} p-2">{{ $request->statusName }}</span>
                        </td>
                        <td align="right">
                            {{ $request->totalPrice }}
                        </td>
                        <td>
                            {{ $request->updated_at->format('d M Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
