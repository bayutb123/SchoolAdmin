@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar Fasilitas') }} <span
            class="badge badge-secondary">{{ $widget['inventory']->count() }}</span></h1>

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
            <div class="row-lg-6">
                @if (Auth::user()->role_id == 1)
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-2 pull-right">Input Pendataan
                        Fasilitas</a>
                @endif
                <a href="{{ route('inventory.request') }}" class="btn btn-primary mb-2 pull-right">Tambah Permintaan
                    Fasilitas</a>
            </div>
        </div>
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width=15%>Nama</th>
                    <th width=10%>Ruang Lingkup</th>
                    <th width=15%>Kategori</th>
                    <th width=5%>Jumlah</th>
                    <th>Status</th>
                    <th width=10%>Terakhir Diubah</th>
                    <th width=10%>Aksi</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['inventory'] as $inventory)
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
                        <td>{{ $inventory->updated_at }}</td>
                        <td>
                            <div class="row mx-2">
                                @if ($inventory->isIssued)
                                <a href="{{ route('issue.detail', ['id' => $inventory->issue_id]) }}"
                                    class="btn btn-primary btn-sm w-100">Lihat laporan</a>
                            @elseif ($inventory->isPlanned)
                                <a href="{{ route('inventory.request.edit', ['id' => $inventory->id]) }}"
                                    class="btn btn-primary btn-sm w-100">Edit Permintaan</a>
                            @elseif ($inventory->isRequested)
                                <a href=" {{ route('request.detail', ['id' => $inventory->request_id]) }}"
                                    class="btn btn-primary btn-sm w-100">Lihat permintaan</a>
                            @else
                                <a href="{{ route('inventory.edit', ['id' => $inventory->id]) }}"
                                    class="btn btn-primary btn-sm w-100">Edit Fasilitas</a>
                            @endif
                            @if ($inventory->isApproved) 
                                <a href="{{ route('inventory.request.status', ['id' => $inventory->id]) }}"
                                    class="btn btn-primary btn-sm w-100 mt-2">Update Status</a>
                            @endif
                            </div>
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
