@extends('layouts.admin')
@section('content')
    <div class="container mt-5" data-aos="fade-down">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Import Excel</h1>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('convertjson') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group-1">
                        <label for="housing_name" class="form-label">Housing Name</label>
                        <input type="text" id="housing_name" name="housing_name" class="form-control border-primary"
                            placeholder="Enter your housing name" required>
                    </div>
                    <div class="form-group-1">
                        <label for="file" class="form-label">Select Excel File</label>
                        <input type="file" id="file" name="file" accept=".xls,.xlsx"
                            class="form-control border-primary" required>
                    </div>
                    <div class="form-group-1 mt-2">
                        <p class="text-muted">Note: Please ensure the Excel file does not contain duplicate mobile numbers or spaces in them.</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row justify-content-between mt-3">
                        <button class="btn btn-primary mb-2 mb-sm-0 px-4" type="submit">Upload</button>
                        <a href="{{ asset('samples/sample_housing_file.xlsx') }}"
                            class="btn btn-outline-primary px-4">Download Sample Excel File</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    @media (max-width: 768px) {
        .btn {
            width: 100%;
        }

        .btn-outline-primary {
            margin-top: 10px;
        }

        .form-control {
            width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    #housing_name {
        border-radius: 10px;
    }

    #file {
        border-radius: 10px;
    }

    .form-control {
        padding: 10px;
        border-radius: 10px;
    }

    label {
        font-weight: bold;
    }

    .form-group-1 {
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        margin-left: 70px;
    }
</style>
