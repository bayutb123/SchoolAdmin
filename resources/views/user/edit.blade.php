@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Role User') }}</h1>

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

                <form class="m-4" action="{{ route('user.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $widget['user']->id }}">
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" value="{{ $widget['user']->fullname }}" disabled
                                name="name" id="name" aria-describedby="name" placeholder="">
                            <small id="name" class="form-text text-muted">Help text</small>
                        </div>
                        <div class="form-group col">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" value="{{ $widget['user']->email }}" disabled
                                name="email" id="email" aria-describedby="email" placeholder="">
                            <small id="email" class="form-text text-muted">Help text</small>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_id">Role</label>
                                <select class="form-control" name="role_id" id="inlineFormCustomSelect">
                                    @foreach ($widget['role'] as $role)
                                        <option @if ($widget['user']->role_id == $role->id) selected @endif
                                            value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <small id="role_id" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
