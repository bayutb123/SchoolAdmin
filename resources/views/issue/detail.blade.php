@extends('layouts.admin')

@section('main-content')
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

                <form class="m-4" action="{{ route('issue.approve') }}" method="post">
                    @method('PUT')
                    @csrf
                    {{-- <input type="hidden" class="form-control" name="author_id" value="{{ Auth::user()->id }}"
                        id="author_id" aria-describedby="author_id" placeholder=""> --}}

                    <input type="hidden" name="issue_id" value="{{ $widget['issue']->id }}">
                    <div class="form-row">
                        <div class="form-group col-2">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                <option selected value="{{ $widget['issue']->room_id }}">{{ $widget['issue']->room_name }}
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
                                <th>Jumlah</th>
                                <th>Kondisi</th>
                                <th>Terakhir Diubah</th>
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
                                    <td>
                                        {{ $inventory->updated_at }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea readonly type="text" class="form-control" name="description" id="description" aria-describedby="description"
                            placeholder="">{{ $widget['issue']->description }}</textarea>
                        <small id="description" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" name="author" value="{{ $widget['issue']->author }}"
                            id="author" disabled aria-describedby="author" placeholder="">
                        <small id="author_id"
                            class="form-text text-muted">{{ $widget['issue']->created_at->toRfc850String() }}</small>
                    </div>
                    
                    @if ( Auth::user()->role_id  == 1 )
                        <button type="submit" class="btn btn-primary">Setujui</button>
                    @endif
                    
                    @if ( $widget['issue']->status > 5 && Auth::user()->role_id  == 2 )
                        <span class="badge badge-{{ $widget['issue']->statusColor }} p-2">{{ $widget['issue']->statusName }}</span>    
                    @endif
                </form>
            </div>
        </div>

    </div>
@endsection
