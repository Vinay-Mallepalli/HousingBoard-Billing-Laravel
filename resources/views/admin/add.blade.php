@extends('layouts.admin')
@section('content')
    <div class="container" data-aos="flip-left" >
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h3 class="fw-normal">Add Resident for {{ $housing->housing_name }}</h3>
            </div>
            <div class="col-md-6 text-end">
                <p>Housing ID: {{ $housing->id }}</p>
                <p>Housing Name: {{ $housing->housing_name }}</p>
            </div>
        </div>

        <form action="{{ route('admin.saveResident', ['housingId' => $housing->id]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input style="border: 1px solid rgb(90, 120, 255);" type="text" class="form-control" id="id"
                    name="id" placeholder="Enter Resident Id" required>
            </div>
            <div class="mb-3">
                <label for="mobile_number" class="form-label">Mobile Number</label>
                <input style="border: 1px solid rgb(90, 120, 255);" type="text" class="form-control" id="mobile_number"
                    name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Enter Resident Mobile Number"
                    required>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input style="border: 1px solid rgb(90, 120, 255);" type="text" class="form-control" id="name"
                    name="name" placeholder="Enter Resident Name" required>
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <input style="border: 1px solid rgb(90, 120, 255);" type="text" class="form-control" id="unit"
                    name="unit" placeholder="Enter Resident Unit/Flat number" required>
            </div>
            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route('admin.manageHousing', ['housingId' => $housing->id]) }}"
                    class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Save</button>

            </div>
        </form>
    </div>
@endsection
