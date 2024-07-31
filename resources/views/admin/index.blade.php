@extends('layouts.admin')
@section('content')
    <div class="container" data-aos="fade-up">
        <h1 class="text-start">Manage Admins</h1>
        <hr>
        <div class="d-flex justify-content-start mb-3">
            <a href="{{ route('admin.create') }}" class="btn btn-primary">Add New Admin</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <a href="{{ route('admin.destroy', $admin->id) }}" class="btn btn-danger btn-sm"
                                    data-confirm-delete="true">Delete</a>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
