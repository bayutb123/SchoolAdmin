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
    <h1 class="h3 text-gray-800">{{ __('Daftar Fasilitas') }} <span
            class="badge badge-secondary">{{ $widget['inventories']->count() }}</span></h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="col-lg-12 mb-4">
        <p>{{ $widget['date'] }}</p>
        <table style="font-size: 0.825em" class="table table-bordered">
            <thead>
                <tr>
                    <th width=15%>Nama</th>
                    <th width=10%>Ruang Lingkup</th>
                    <th width=20%>Kategori</th>
                    <th width=10%>Jumlah</th>
                    <th>Status</th>
                    <th width=12%>Terakhir Diubah</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['inventories'] as $inventory)
                    <tr>
                        <td>{{ $inventory->name }}

                        </td>
                        <td>{{ $inventory->roomName }}</td>
                        <td>{{ $inventory->category }}</td>
                        <td>{{ $inventory->quantity }} {{ $inventory->quantity_unit }}</td>
                        <td>
                            @if ($inventory->isNew)
                                <span class="badge badge-success p-2">Baru</span>
                            @endif
                            <span
                                class="badge badge-{{ $inventory->statusColor }} p-2">{{ $inventory->statusName }}</span>
                            @if ($inventory->issueStatusName)
                                <span
                                    class="badge badge-{{ $inventory->issueStatusColor }} p-2">{{ $inventory->issueStatusName }}</span>
                            @elseif ($inventory->requestStatusName)
                                <span
                                    class="badge badge-{{ $inventory->requestStatusColor }} p-2">{{ $inventory->requestStatusName }}</span>
                            @elseif ($inventory->isPlanned)
                                <span
                                    class="badge badge-{{ $inventory->planStatusColor }} p-2">{{ $inventory->planStatusName }}</span>
                            @elseif ($inventory->isApproved)
                                <span
                                    class="badge badge-{{ $inventory->approveStatusColor }} p-2">{{ $inventory->approveStatusName }}</span>
                            @endif
                        </td>

                        <td>{{ $inventory->updated_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
    @endpush

</body>

</html>
