@extends('layouts.admin')
@section('content')
    <div class="container" data-aos="zoom-in-up">
        <h1>Edit Resident</h1>
        <hr>
        <form action="{{ route('housing.update', $housing->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ $housing->mobile_number }}">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $housing->name }}">
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label>
                <input type="text" class="form-control" id="unit" name="unit" value="{{ $housing->unit }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
