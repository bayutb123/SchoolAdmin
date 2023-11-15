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

    @if (session('error'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="col-lg-12 mb-4">

        <div class="row">
            <div class="col-sm-4">
                {{-- print button --}}
                <a href="{{ route('inventory.print') }}" class="btn btn-primary mb-2 pull-right"><i class="fa fa-print"
                        aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-sm-8">
                <div class="text-right">
                    @if (Auth::user()->role_id == 1)
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary mb-2 pull-right">Input Pendataan
                            Fasilitas</a>
                    @endif
                    <a href="{{ route('inventory.request') }}" class="btn btn-primary mb-2 pull-right">Tambah Permintaan
                        Fasilitas</a>
                </div>
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
                                    <a href="#" class="btn btn-danger btn-sm w-100 mt-2"
                                        data-toggle="modal" data-target="#delete-{{ $inventory->id }}">Hapus</a>
                                @elseif ($inventory->isRequested)
                                    <a href=" {{ route('request.detail', ['id' => $inventory->request_id]) }}"
                                        class="btn btn-primary btn-sm w-100">Lihat permintaan</a>
                                @else
                                    <a href="{{ route('inventory.edit', ['id' => $inventory->id]) }}"
                                        class="btn btn-primary btn-sm w-100">Edit Fasilitas</a>
                                    <a href="#" class="btn btn-danger btn-sm w-100 mt-2"
                                        data-toggle="modal" data-target="#delete-{{ $inventory->id }}">Hapus</a>
                                @endif
                                @if ($inventory->isApproved)
                                    <a href="{{ route('inventory.request.status', ['id' => $inventory->id]) }}"
                                        class="btn btn-primary btn-sm w-100 mt-2">Update Status</a>
                                @endif
                            </div>
                            <!-- Confirmation Modal-->
                            <div class="modal fade" id="delete-{{ $inventory->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                {{ __('Yakin ingin menyetujui pengajuan ini?') }}
                                            </h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">Aksi ini tidak dapat diurungkan</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-link" type="button"
                                                data-dismiss="modal">{{ __('Cancel') }}</button>
                                            <form id="delete-form" action="{{ route('inventory.item.destroy') }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id"
                                                    value="{{ $inventory->id }}">
                                            </form>
                                            <a class="btn btn-success" href="{{ route('inventory.item.destroy') }}"
                                                onclick="event.preventDefault(); document.getElementById('delete-form').submit();">{{ __('Delete') }}</a>
                                        </div>
                                    </div>
                                </div>
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
