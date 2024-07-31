@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="text-center mb-0 flex-grow-1" style="margin-left: 60px;">Housing Reports</h1>
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-success mt-2 mt-md-0">Back to Dashboard</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Sl.No.</th>
                                    <th style="width: 50%;">Year</th>
                                    <th style="width: 20%;">Mobile Number</th>
                                    <th style="width: 20%;">Resident Name</th>
                                    <th style="width: 10%;">Flat Number</th>
                                    <th style="width: 10%;">Amount Paid</th>
                                    <th style="width: 15%;">Payment Mode</th>
                                    <th style="width: 10%;">Receipt Status</th>
                                    <th style="width: 15%;">Receipt Sent At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->year }}</td>
                                        <td>{{ $report->mobile_number }}</td>
                                        <td>{{ $report->resident_name }}</td>
                                        <td>{{ $report->flat_number }}</td>
                                        <td>{{ number_format($report->amount_paid, 2) }}</td>
                                        <td>{{ $report->payment_mode }}</td>
                                        <td>
                                            @if ($report->receipt_status === 'Delivered')
                                                <span class="badge badge-success">{{ $report->receipt_status }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $report->receipt_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($report->receipt_sent_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $reports->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-outline-success {
        color: #28a745;
        border-color: #28a745;
    }

    .btn-outline-success:hover {
        color: white;
        background-color: #28a745;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    .table-responsive {
        margin-top: 20px;
    }

    @media (max-width: 576px) {
        .card-header {
            flex-direction: column;
        }

        .card-header h1 {
            font-size: 1.5rem;
            margin-left: 0;
            order: 1;
        }

        .btn-outline-success {
            width: 100%;
            margin-top: 10px;
            
            
        }

        .table th, .table td {
            font-size: 12px;
            padding: 5px;
        }

        .table th {
            white-space: nowrap;
        }
    }
</style>
@endpush
