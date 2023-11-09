@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Buat Laporan Kerusakan Fasilitas') }}</h1>

    <div class="row justify-content-center">

        <div class="col-lg-12">

            <div class="card shadow mb-4">

                <form class="m-4" action="" method="post">
                
                    <div class="form-group">
                      <label for="helpId">Nama</label>
                      <input type="text" class="form-control" name="" id="helpId" aria-describedby="helpId" placeholder="">
                      <small id="helpId" class="form-text text-muted">Help text</small>
                    </div>

                </form>
                
            </div>

        </div>

    </div>
@endsection
