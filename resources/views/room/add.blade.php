@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Buat Pendataan Lingkungan') }}</h1>

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

                <form class="m-4" action="{{ route('room.store') }}" method="post">
                    @csrf
                    <input type="hidden" class="form-control" name="last_author_id" value="{{ Auth::user()->id }}"
                        id="last_author_id" aria-describedby="last_author_id" placeholder="">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" required name="name" id="name" aria-describedby="name"
                                placeholder="">
                            <small id="name" class="form-text text-muted">
                                Contoh: X-F, Taman Belakang, Ruang Serba Guna</small>
                        </div>
                        <div class="form-group col">
                            <label for="floor">Lokasi</label>
                            <select class="form-control" name="floor" id="inlineFormCustomSelect">
                                <option value=1>Lt. Dasar</option>
                                <option value=2>Lt. 2</option>
                                <option value=3>Lt. 3</option>
                                <option value=4>Lt. 4</option>
                                <option value=5>Ruang Terbuka Sekolah</option>
                            </select>
                            <small id="floor" class="form-text text-muted">Lokasi di sekolah</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Kategori</label>
                                <select class="form-control" name="type" id="inlineFormCustomSelect">
                                    <option value="Kelas">Kelas</option>
                                    <option value="Administrasi">Administrasi</option>
                                    <option value="Umum">Umum</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <small id="type" class="form-text text-muted">Fungsi ruangan tersebut</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="size">Ukuran Ruangan</label>
                                <input type="number" required step="0.01" class="form-control" name="size" id="size"
                                    aria-describedby="size" placeholder="">
                                <small id="size" class="form-text text-muted">Contoh : 10.51</small>
                            </div>
                        </div>
                        <div class="col md-2">
                            <div class="form-group">
                                <label for="size_unit">Satuan<sup>2</sup></label>
                                <select class="form-control" name="size_unit" id="inlineFormCustomSelect">
                                    <option value="m">Meter</option>
                                    <option value="cm">Sentimeter</option>
                                </select>
                                <small id="size_unit" class="form-text text-muted">satuan dari ukuran ruangan</small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>

                </form>




            </div>

        </div>

    </div>
@endsection
