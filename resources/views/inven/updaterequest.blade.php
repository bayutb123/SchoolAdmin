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

                <form class="m-4" action="{{ route('inventory.request.applyUpdateStatus') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">

                    <input type="hidden" class="form-control" name="inventory_id" value="{{ $widget['inventory']->id }}">
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
                            @foreach ($widget['applicableStatus'] as $item)
                                <option @if ($widget['inventory']->status == $item) selected @endif value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
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
