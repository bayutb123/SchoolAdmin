@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 text-gray-800">{{ __('Daftar User') }} <span
            class="badge badge-secondary">{{ $widget['userCount'] }}</span></h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-left-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif


    <div class="col-lg-12 mb-4">

        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width=15%>Nama</th>
                    <th width=10%>Role</th>
                    <th>Email</th>
                    <th width=10%>Aksi</th>
                </tr>
            </thead>
            <tbody id="myTable">
                @foreach ($widget['users'] as $user)
                    <tr>
                        <td>{{ $user->name }} {{ $user->last_name }}</td>
                        <td>{{ $user->roleName }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="
                                {{ route('user.edit', ['id' => $user->id]) }}
                            " class="btn btn-primary btn-sm w-100">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });
        </script>
    @endpush
@endsection
