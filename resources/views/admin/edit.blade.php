@extends('layouts.admin')
@section('content')
<div class="container" >
    <div class="row justify-content-center">
        <div class="col-md-6" data-aos="zoom-in-up">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center " style="padding-top: 15px;padding-bottom:15px; font-size:20px" >Edit Admin</div>
                <div class="card-body p-4">
                        @if (session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: '{{ session('success') }}',
                                    timer: 5000,
                                    showConfirmButton: false
                                });
                            </script>
                        @endif

                        @if ($errors->any())
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Errors',
                                    html: '<ul class="text-start">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                                    timer: 10000,
                                    showConfirmButton: true
                                });
                            </script>
                        @endif

                        <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input style="border: 1px solid rgb(90, 120, 255);" type="text" name="name" id="name" class="form-control"
                                    value="{{ $admin->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input style="border: 1px solid rgb(90, 120, 255); padding:10px" type="email" name="email" id="email" class="form-control"
                                    value="{{ $admin->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input style="border: 1px solid rgb(90, 120, 255); padding:10px" type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter new password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input style="border: 1px solid rgb(90, 120, 255); padding:10px" type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="Confirm new password">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Admin</button>
                            </div>
                        </form>
                        <div class="d-grid mt-3">
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-primary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
