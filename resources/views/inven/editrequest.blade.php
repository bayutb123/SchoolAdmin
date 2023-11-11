@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Rencana Pengadaan Fasilitas') }}</h1>

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

                <form class="m-4" action="{{ route('inventory.request.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">

                    <input type="hidden" class="form-control" name="inventory_id" value="{{ $widget['inventory']->id }}">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="room_id">Lokasi</label>
                            <select class="selectpicker w-100" data-live-search="true" name="room_id" id="room">
                                @foreach ($widget['rooms'] as $room)
                                    <option @if ($widget['inventory']->room_id == $room->id) selected @endif
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
                                <option @if ($widget['inventory']->category == 'Fasilitas Utama Pendidikan') selected @endif
                                    value="Fasilitas Utama Pendidikan">
                                    Fasilitas Utama Pendidikan</option>
                                <option @if ($widget['inventory']->category == 'Fasilitas Pendukung') selected @endif 
                                    value="Fasilitas Pendukung">
                                    Fasilitas Pendukung</option>
                                <option @if ($widget['inventory']->category == 'Lainnya') selected @endif 
                                    value="Lainnya">Lainnya</option>
                            </select>
                            <small id="category" class="form-text text-muted">Help text</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    aria-describedby="name" required placeholder=""
                                    value="{{ $widget['inventory']->name }}">
                                <small id="name" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="price">Harga Satuan</label>
                                <input type="number" required class="form-control" name="price" id="price"
                                    aria-describedby="price" placeholder="" value="{{ $widget['inventory']->price }}">
                                <small id="price" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" required step="0.01" class="form-control" name="quantity"
                                    id="quantity" aria-describedby="quantity" placeholder=""
                                    value="{{ $widget['inventory']->quantity }}">
                                <small id="quantity" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col md-2">
                            <div class="form-group">
                                <label for="quantity_unit">Satuan</label>
                                <select class="form-control" required name="quantity_unit" id="inlineFormCustomSelect">
                                    <option @if ($widget['inventory']->quantity_unit == 'pcs') selected @endif value="pcs">pcs</option>
                                    <option @if ($widget['inventory']->quantity_unit == 'set') selected @endif value="set">set</option>
                                    <option @if ($widget['inventory']->quantity_unit == 'meter') selected @endif value="meter">meter</option>
                                    <option @if ($widget['inventory']->quantity_unit == 'kg') selected @endif value="kg">kg</option>
                                    <option @if ($widget['inventory']->quantity_unit == 'liter') selected @endif value="liter">
                                        liter</option>
                                    <option @if ($widget['inventory']->quantity_unit == 'lainnya') selected @endif value="lainnya">lainnya
                                    </option>
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
                        <label for="status">Status</label>
                        <select required class="form-control" name="status" id="inlineFormCustomSelect">
                            <option value="{{ $widget['inventory']->status }}" >
                                {{ $widget['inventory']->statusName }}</option>
                        </select>
                        <small id="status" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" class="form-control" name="author" value="{{ Auth::user()->fullName }}"
                            id="author" disabled aria-describedby="author" placeholder="">
                        <small id="author_id"
                            class="form-text text-muted">{{ 
                                $widget['inventory']->created_at->toRfc850String()
                            }}</small>
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
