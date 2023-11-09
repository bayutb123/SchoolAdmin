@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Laporan') }} <span
            class="badge badge-secondary">{{ $widget['issues']->count() }}</span></h1>

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
            <a href="{{ route('issue.create') }}" class="btn btn-primary mb-2 pull-right">Tambah Laporan</a>
        </div>
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Lokasi</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                    <th>Terakhir Diubah</th>
                </tr>
            </thead>
            <tbody id="myTable">
                {{-- @foreach ($widget['inventory'] as $inventory)
                    <tr>
                        <td>{{ $inventory->name }}</td>
                        <td>{{ $inventory->room }}</td>
                        <td>{{ $inventory->category }}</td>
                        <td>{{ $inventory->description }}</td>
                        <td>{{ $inventory->quantity }}</td>
                        <td>{{ $inventory->quantity_unit }}</td>
                        <td>{!! $inventory->status !!}</td>
                        <td>{{ $inventory->last_author_id }}</td>
                    </tr>
                @endforeach --}}
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
