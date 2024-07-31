@extends('layouts.admin')
@section('content')
    <div class="container" data-aos="fade-up">
        <h1>Manage Residents </h1>
        <div class="mt-3">
            <a href="{{ route('admin.add', ['housingId' => $housingId]) }}" class="btn btn-primary">Add Resident</a>
            <a href="{{ route('admin.deleteHousing', ['housingId' => $housingId]) }}" data-confirm-delete="true"
                class="btn btn-danger">Delete Housing</a>
        </div>
        <hr>
        <div id="housingDashboard">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-top: 0px;" id="myTable">
                    <thead>
                        <tr>
                            <th>Sl.No</th>
                            <th>Mobile Number</th>
                            <th>Name</th>
                            <th>Unit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paginatedItems as $index => $row)
                            <tr>
                                <td>{{ $row[0] }}</td>
                                <td>{{ $row[1] }}</td>
                                <td>{{ $row[2] }}</td>
                                <td>{{ $row[3] }}</td>
                                <td>
                                    <a href="{{ route('admin.editResident', ['housingId' => $housingId, 'rowId' => $row[0]]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('admin.deleteResident', ['housingId' => $housingId, 'rowId' => $row[0]]) }}"
                                        data-confirm-delete="true" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        
            <!-- Pagination Controls -->
            <div id="pagination-controls">
                {{ $paginatedItems->links('pagination::bootstrap-4') }}
            </div>
        
        </div>
    </div>
@endsection

<style>
    .housing-name {
        font-size: 2rem;
        /* Decreased size */
    }

    .btn-primary,
    .btn-danger {
        margin-right: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    .table thead th {
        background-color: #007bff;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tbody tr:hover {
        background-color: #ddd;
    }

    #pagination-controls {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }

    #pagination-controls button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 0 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    #pagination-controls button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    #page-info {
        font-size: 16px;
        margin: 0 10px;
    }

    @media (max-width:768px){
        .mt-3{
            display: flex;
            
        }
    }
</style>
