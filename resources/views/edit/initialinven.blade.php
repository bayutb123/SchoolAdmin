@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Ubah Data Fasilitas') }}</h1>

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

                <form class="m-4" action="{{ route('inventory.update')}}" method="POST">
                    @method('PUT')
                    
                    @csrf
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">
                    <input type="hidden" name="inventory_id" value="{{ $widget['inventory']->id }}">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                @foreach ($widget['rooms'] as $room)
                                    <option
                                        @if ($widget['inventory']->room_id == $room->id) {
                                        selected
                                    } @endif
                                        data-tokens="{{ $room->name }}" value="{{ $room->id }}">
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="room_id" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="form-group col">
                            <label for="category">Kategori</label>
                            <select class="form-control" name="category" id="inlineFormCustomSelect">
                                @foreach ($widget['category'] as $category)
                                    <option
                                        @if ($widget['inventory']->category == $category->category) {
                                        selected
                                    } @endif
                                        value={{ $category->category }}>{{ $category->category }}</option>
                                @endforeach
                            </select>
                            <small id="category" class="form-text text-muted">Help text</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" value="{{ $widget['inventory']->name }}" class="form-control"
                                    name="name" id="name" aria-describedby="name" placeholder="">
                                <small id="name" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" value="{{ $widget['inventory']->quantity }}" step="0.01"
                                    class="form-control" name="quantity" id="quantity" aria-describedby="quantity"
                                    placeholder="">
                                <small id="quantity" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col md-2">
                            <div class="form-group">
                                <label for="quantity_unit">Satuan</label>
                                <select class="form-control" disabled name="quantity_unit" id="inlineFormCustomSelect">
                                        <option
                                            selected
                                            value={{ $widget['inventory']->quantity_unit }}>{{ $widget['inventory']->quantity_unit }}</option>

                                </select>
                                <small id="quantity_unit" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea type="text" class="form-control" name="description" id="description" aria-describedby="description"
                            placeholder="">{{ $widget['inventory']->description }}</textarea>
                        <small id="description" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="form-group">
                        <label for="status">Kondisi</label>
                        <select @if ($widget['inventory']->status == 'Dalam Perbaikan') disabled @endif class="form-control" name="status"
                            id="inlineFormCustomSelect">
                            @if ($widget['inventory']->status == 'Dalam Perbaikan')
                                <option selected value="Dalam Perbaikan">Dalam Perbaikan</option>
                            @else
                                @foreach ($widget['status'] as $status)
                                    <option
                                        @if ($widget['inventory']->status == $status->id) {
                                            selected
                                        } @endif
                                        value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small id="status" class="form-text text-muted">Help text</small>
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

        {{-- <select class="selectpicker" multiple data-live-search="true">
            <option>Mustard</option>
            <option>Ketchup</option>
            <option>Relish</option>
          </select> --}}

    </div>
@endsection
