@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Laporan Fasilitas') }}</h1>

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

                <form class="m-4" action="{{ route('issue.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="form-control" name="author_id" value="{{ Auth::user()->id }}"
                        id="author_id" aria-describedby="author_id" placeholder="">
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
                        <div class="form-group col-10">
                            <label for="category">Fasilitas</label>
                            <select required class="selectpicker w-100" data-live-search="true" multiple name="inventories[]"
                                id="inlineFormCustomSelect">
                                @foreach ($widget['allInventories'] as $inventory)
                                    <option @if (in_array($inventory->id, $widget['inventories']->pluck('id')->toArray())) 
                                        selected
                                    @endif data-tokens="{{ $inventory->name }}" value="{{ $inventory->id }}">
                                        {{ $inventory->name }} - <span
                                            style="font-weight: 100">{{ $inventory->room_id }}</span>
                                    </option>
                                @endforeach
                            </select>
                            <small id="category" class="form-text text-muted">Help text</small>
                        </div>
                    </div>

                    

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea type="text" class="form-control" name="description" id="description" aria-describedby="description"
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
                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>
            </div>
        </div>

    </div>
@endsection
