@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Data Lingkungan') }}</h1>

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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Notice!</strong>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow mb-4">

                <form class="m-4" action="{{ route('room.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">
                    <input type="hidden" name="room_id" value="{{ $widget['room']->id }}">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" value="{{ $widget['room']->name }}" required name="name" id="name" aria-describedby="name"
                                placeholder="">
                            <small id="name" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="form-group col">
                            <label for="floor">Lokasi</label>
                            <select class="form-control" name="floor" id="inlineFormCustomSelect">
                                <option @if ($widget['room']->floor == 1)
                                    selected
                                @endif value=1>Lt. Dasar</option>
                                <option @if ($widget['room']->floor == 2)
                                    selected @endif
                                 value=2>Lt. 2</option>
                                <option @if ($widget['room']->floor == 3)
                                    selected @endif
                                 value=3>Lt. 3</option>
                                <option @if ($widget['room']->floor == 4)
                                    selected @endif
                                 value=4>Lt. 4</option>
                                <option @if ($widget['room']->floor == 5)
                                    selected  @endif
                                value=5>Ruang Terbuka Sekolah</option>
                            </select>
                            <small id="floor" class="form-text text-muted">Help text</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Kategori</label>
                                <select class="form-control" name="type" id="inlineFormCustomSelect">
                                    <option @if ($widget['room']->type == 'Kelas')
                                        selected @endif
                                        value="Kelas">Kelas</option> 
                                    <option @if ($widget['room']->type == 'Administrasi')
                                        selected @endif
                                        value="Administrasi">Administrasi</option>
                                    <option @if ($widget['room']->type == 'Umum')
                                        selected @endif
                                        value="Umum">Umum</option>
                                    <option @if ($widget['room']->type == 'Lainnya')
                                        selected @endif
                                        value="Lainnya">Lainnya</option>
                                </select>
                                <small id="type" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="size">Ukuran Ruangan</label>
                                <input type="number" value="{{ $widget['room']->size }}" required step="0.01" class="form-control" name="size" id="size"
                                    aria-describedby="size" placeholder="">
                                <small id="size" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                        <div class="col md-2">
                            <div class="form-group">
                                <label for="size_unit">Satuan<sup>2</sup></label>
                                <select class="form-control" name="size_unit" id="inlineFormCustomSelect">
                                    <option @if ($widget['room']->size_unit == 'm')
                                        selected @endif
                                        value="m">Meter</option>
                                    <option @if ($widget['room']->size_unit == 'cm')
                                        selected @endif
                                        value="cm">Sentimeter</option>
                                </select>
                                <small id="size_unit" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>




            </div>

        </div>

    </div>
@endsection
