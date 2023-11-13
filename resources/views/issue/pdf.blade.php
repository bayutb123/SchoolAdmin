<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Detail Laporan Fasilitas') }}</h1>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Notice</strong>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card shadow mb-4">
                <form class="m-4" action="" method="">
                    @if ($widget['issue']->status > 5 && Auth::user()->role_id == 2)
                        <span
                            class="badge badge-{{ $widget['issue']->statusColor }} my-2 p-2">{{ $widget['issue']->statusName }}</span>
                    @endif
                    <input type="hidden" name="issue_id" value="{{ $widget['issue']->id }}">

                    <div class="form-group">
                        Ruangan <span style="font-weight: bold">{{ $widget['issue']->room_name }}</span>
                        <small id="author_id"
                            class="form-text text-muted">{{ $widget['issue']->created_at->toRfc850String() }}</small>
                    </div>

                    {{-- Create table with inventory inside --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            @foreach ($widget['inventories'] as $inventory)
                                <tr>
                                    <td>
                                        {{ $inventory->name }}
                                    </td>
                                    <td>
                                        {{ $inventory->room_name }}
                                    </td>
                                    <td>
                                        {{ $inventory->category }}
                                    </td>
                                    <td>
                                        {{ $inventory->quantity }}
                                    </td>
                                    <td>
                                        {{ $inventory->condition }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                        <label for="description">Deskripsi</label>
                        <textarea class="form-input" type="text" name="description" id="description" aria-describedby="description" placeholder="">{{ $widget['issue']->description }}</textarea>

                    <div class="input-group mt-3">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Author :
                                    {{ $widget['issue']->author }}</span>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>
