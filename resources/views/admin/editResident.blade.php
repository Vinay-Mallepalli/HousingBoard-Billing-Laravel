@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6" data-aos="zoom-in-up">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center" style="padding-top: 15px;padding-bottom:15px;font-size:20px">Edit Resident</div>
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

                    <form method="POST" action="{{ route('admin.updateResident', ['housingId' => $housingId, 'rowId' => $rowData[0]]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input style="border: 1px solid rgb(90, 120, 255);" type="text" name="mobile_number" id="mobile_number" class="form-control"
                                   value="{{ $rowData[1] }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input style="border: 1px solid rgb(90, 120, 255);" type="text" name="name" id="name" class="form-control"
                                   value="{{ $rowData[2] }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit</label>
                            <input style="border: 1px solid rgb(90, 120, 255);" type="text" name="unit" id="unit" class="form-control"
                                   value="{{ $rowData[3] }}" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Resident</button>
                        </div>
                    </form>
                    <div class="d-grid mt-3">
                        <a href="{{ route('admin.manageHousing', ['housingId' => $housingId]) }}" class="btn btn-outline-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #80bdff;
    }
</style>
@endsection
