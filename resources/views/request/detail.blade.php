@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Detail Pengadaan Fasilitas') }}</h1>

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


                <form class="m-4" id="approve-form" action="{{ route('request.approve') }}" method="post">
                    @if ($widget['request']->status > 10 && Auth::user()->role_id == 2)
                        <span
                            class="badge badge-{{ $widget['request']->statusColor }} mb-2 p-2">{{ $widget['request']->statusName }}</span>
                    @endif
                    @method('PUT')
                    @csrf
                    {{-- <input type="hidden" class="form-control" name="author_id" value="{{ Auth::user()->id }}"
                        id="author_id" aria-describedby="author_id" placeholder=""> --}}

                    <input type="hidden" name="request_id" value="{{ $widget['request']->id }}">
                    <div class="form-row">
                        <div class="form-group col-2">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                <option selected value="{{ $widget['request']->roomName }}">{{ $widget['request']->roomName }}
                                </option>
                            </select>
                            <small id="room_id" class="form-text text-muted">Help text</small>
                        </div>

                    </div>

                    {{-- Create table with inventory inside --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                                <th width=5%>Jumlah</th>
                                <th width=10%>Harga Satuan</th>
                                <th width=10%>Total Biaya</th>
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
                                        {{ $inventory->quantity }} {{ $inventory->quantity_unit }}
                                    </td>
                                    <td>
                                        {{ $inventory->price }}
                                    </td>
                                    <td>
                                        {{ $inventory->total_price }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold">
                                <td colspan="5" class="text-right">Total Biaya (Rupiah)</td>
                                <td>{{ $widget['request']->total_price }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea readonly type="text" class="form-control" name="description" id="description"
                            aria-describedby="description" placeholder="">{{ $widget['request']->description }}</textarea>
                        <small id="description" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" name="author" value="{{ $widget['request']->author }}"
                            id="author" disabled aria-describedby="author" placeholder="">
                        <small id="author_id"
                            class="form-text text-muted">{{ $widget['request']->created_at->toRfc850String() }}</small>
                    </div>

                    @if (Auth::user()->role_id == 1)
                        <a href="#" data-toggle="modal" data-target="#approveModal"
                            class="btn btn-primary">Setujui</a>
                    @endif
                    <a href="{{ route('request.print', $widget['request']->id) }}" class="btn btn-primary">
                        <i class="fas fa-print"></i>
                    </a>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal-->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Yakin ingin menyetujui pengajuan ini?') }}
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Aksi ini tidak dapat diurungkan</div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-success" href="{{ route('request.approve') }}"
                            onclick="event.preventDefault(); document.getElementById('approve-form').submit();">{{ __('Approve') }}</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
