@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Buat Pendataan Fasilitas') }}</h1>

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

                <form class="m-4" action="{{ route('inventory.store') }}" method="post">
                    @csrf
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                <option selected value="null">Choose...</option>
                                @foreach ($widget['rooms'] as $room)
                                    <option data-tokens="{{ $room->name }}" value="{{ $room->id }}">
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="room_id" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="form-group col">
                            <label for="category">Kategori</label>
                            <select class="form-control" name="category" id="inlineFormCustomSelect">
                                <option selected>Choose...</option>
                                <option value="Fasilitas Utama Pendidikan">
                                    Fasilitas Utama Pendidikan</option>
                                <option value="Fasilitas Pendukung">Fasilitas Pendukung</option>
                                <option value=3>Lainnya</option>
                            </select>
                            <small id="category" class="form-text text-muted">Help text</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    aria-describedby="name" placeholder="">
                                <small id="name" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" step="0.01" class="form-control" name="quantity" id="quantity"
                                    aria-describedby="quantity" placeholder="">
                                <small id="quantity" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col md-2">
                            <div class="form-group">
                                <label for="quantity_unit">Satuan</label>
                                <select class="form-control" name="quantity_unit" id="inlineFormCustomSelect">
                                    <option value="pcs">pcs</option>
                                    <option value="set">set</option>
                                    <option value="meter">meter</option>
                                    <option value="kg">kg</option>
                                    <option value="liter">liter</option>
                                    <option value="other">lainnya</option>
                                </select>
                                <small id="quantity_unit" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea type="text" class="form-control" name="description" id="description" aria-describedby="description"
                            placeholder=""></textarea>
                        <small id="description" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="form-group">
                        <label for="status">Kondisi</label>
                        <select class="form-control" name="status" id="inlineFormCustomSelect">
                            <option value="Baik">Baik</option>
                            <option value="Kurang">Kurang</option>
                            <option value="Tidak layak">Tidak layak</option>
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
