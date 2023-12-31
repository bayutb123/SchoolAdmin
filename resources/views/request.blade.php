@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Pengadaan Barang') }} <span
            class="badge badge-secondary">{{ $widget['request']->count() }}</span></h1>

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
        

        <div class="row">
            <div class="col-sm-4">
                {{-- print button --}}
                <a href="{{ route('request.printAll') }}" class="btn btn-primary mb-2 pull-right"><i class="fa fa-print"
                        aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-sm-8">
                <div class="text-right">
                    <a href="{{ route('request.create') }}" class="btn btn-primary mb-2 pull-right">Tambah Laporan</a>
                </div>
            </div>
        </div>
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width=15%>Penanggungjawab</th>
                    <th width=10%>Ruang Lingkup</th>
                    <th>Deskripsi</th>
                    <th width=10%>Total Biaya</th>
                    <th width=15%>Status</th>
                    <th width=10%>Terakhir Diubah</th>
                    <th width=10%>Aksi</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['request'] as $request)
                    <tr>
                        <td>{{ $request->author_id }}</td>
                        <td>{{ $request->room }}</td>
                        <td>{{ $request->description }}</td>
                        <td style="font-weight: bold">
                            Rp. {{ $request->total_price }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $request->statusColor }} p-2">{{ $request->statusName }}</span>
                        <td>
                            @if ($request->isNew)
                                <span class="badge badge-success p-2">Hari ini</span>
                            @else
                                {{ $request->updated_at }}
                            @endif
                        </td>
                        <td>
                            @if ($request->isProcessed || Auth::user()->role_id == 1)
                                {{-- {{ route('request.show', $request->id) }} --}}
                                <a href="{{ route('request.detail', $request->id) }}"
                                    class="btn btn-primary btn-sm w-100">Lihat laporan</a>
                            @else
                                <a href=" {{ route('request.edit', $request->id) }}"
                                    class="btn btn-primary btn-sm w-100">Edit</a>
                            @endif
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
