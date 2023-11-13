@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Buat Laporan Fasilitas') }}</h1>

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

                <form class="m-4" action="{{ route('issue.store') }}" method="post">
                    @csrf
                    <input type="hidden" class="form-control" name="author_id" value="{{ Auth::user()->id }}"
                        id="author_id" aria-describedby="author_id" placeholder="">

                    <div class="form-row">
                        <div class="form-group col-2">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                <option selected value="null">Choose...</option>
                                @foreach ($widget['rooms'] as $room)
                                    <option data-tokens="{{ $room->name }}" value="{{ $room->id }}">
                                        {{ $room->name }} {{ $room->room_id }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="room_id" class="form-text text-muted">Lokasi / Ruangan tempat fasilitas yang rusak</small>
                        </div>
                        <div class="form-group col-10">
                            <label for="category">Fasilitas</label>
                            <select class="selectpicker w-100" data-live-search="true" multiple name="inventories[]"
                                id="inlineFormCustomSelect">
                                @foreach ($widget['inventories'] as $inventory)
                                    <option data-tokens="{{ $inventory->name }}" value="{{ $inventory->id }}">
                                        [{{ $inventory->room_id }}] - {{ $inventory->name }}
                                            ({{ $inventory->statusName }})
                                    </option>
                                @endforeach
                            </select>
                            <small id="category" class="form-text text-muted">Dapat memilih beberapa fasilitas yang rusak<br>(Sangat disarankan untuk memilih hanya fasilitas yang ada di lokasi yang anda sudah tetapkan)</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea type="text" class="form-control" name="description" id="description" aria-describedby="description"
                            placeholder=""></textarea>
                        <small id="description" class="form-text text-muted">Contoh : Barang X yang rusak hanya 1</small>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" name="author" value="{{ Auth::user()->fullName }}"
                            id="author" disabled aria-describedby="author" placeholder="">
                        <small id="author_id"
                            class="form-text text-muted">{{ Carbon\Carbon::now('Asia/Jakarta')->toRfc850String() }}</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>
            </div>
        </div>

    </div>
@endsection
