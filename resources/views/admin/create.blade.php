@extends('layouts.admin')
@section('content')
    <div class="container py-5" >
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="flip-left">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center " style="padding-top: 15px;padding-bottom:15px;font-size:20px">Create Admin</div>
                    <div class="card-body  p-4" >
                        <form method="POST" action="{{ route('admin.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input style="border: 1px solid rgb(90, 120, 255);" type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Admin Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input style="border: 1px solid rgb(90, 120, 255); padding:10px" type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter Admin's Email " required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input style="border: 1px solid rgb(90, 120, 255); padding:10px" type="password" class="form-control" id="password" name="password"
                                    placeholder="Create a password for Admin" required>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                        <div class="d-grid">
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-primary px-4">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
