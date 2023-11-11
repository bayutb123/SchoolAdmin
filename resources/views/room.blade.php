@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Lingkungan') }} <span
            class="badge badge-secondary">{{ $widget['rooms']->count() }}</span></h1>

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
        <div class="text-right">
            <a href="{{ route('room.create') }}" class="btn btn-primary mb-2 pull-right">Tambah Ruangan</a>
        </div>

        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Lokasi</th>
                    <th>Jumlah Fasilitas</th>
                    <th>Ukuran Ruangan</th>
                    <th width=10%>Aksi</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['rooms'] as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->type }}</td>
                        <td>{{ $room->floor }}</td>
                        <td>{{ $room->total }}</td>
                        <td>{{ $room->size }} {{ $room->size_unit }}<sup>2</sup></td>
                        <td>
                            <a href="
                                {{ route('room.edit', ['id' => $room->id]) }}
                            " class="btn btn-primary btn-sm w-100">Edit</a>
                        </td>
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
@endsection
