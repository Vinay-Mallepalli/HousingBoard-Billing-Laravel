@extends('layouts.admin')
@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            Welcome Back {{ Auth::user()->name }}!!
        </div>
    @endif

    <div class="container" data-aos="zoom-in-down">
        <div class="header-section container-fluid">
            <div class="left-section">
                <label for="selected-housing" class="label">Selected Housing</label>
                <input style="border: 1px solid rgb(55, 81, 224);" type="text" id="selected-housing" class="input-field"
                    readonly>
                <!-- Decorative Element -->
                <div class="decorative-element"></div>
            </div>
            <div class="right-section">
                <div class="maintenance-section">
                    <div class="maintenance-item">
                        <label for="startDate" class="label" style="margin-left: 40px;">Select Year</label>
                        <select style="width: 190px; border: 1px solid rgb(55, 81, 224);" name="startDate" id="startDate"
                            class="year-dropdown input-field" onchange="updateYearColumns()">
                            @for ($year = 2000; $year <= 2098; $year++)
                                <option value="{{ $year }}-{{ $year + 1 }}"
                                    {{ $year == 2024 ? 'selected' : '' }}>
                                    {{ $year }}-{{ $year + 1 }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="maintenance-item">
                        <label for="yearly-maintenance" class="label maintenance-label">Yearly Maintenance</label>
                        <input type="text" style="border: 1px solid  rgb(55, 81, 224);" id="yearly-maintenance"
                            name="yearly_maintenance" class="input-field">
                        <button id="update-yearly-maintenance" class="update-button"
                            onclick="updateYearlyMaintenance()">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div id="search-container">
            <i id="search-icon" class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Search..." oninput="searchHousingData()">
        </div>
        <div id="housingDashboard">
            <div class="table-responsive">
                <table cellspacing="0" cellpadding="5" class="table table-bordered" id="myTable">
                    <thead>
                        <th rowspan="2">Sl.No</th>
                        <th rowspan="2">Mobile Number</th>
                        <th rowspan="2" class="name-column">Name</th>
                        <th rowspan="2" style="padding-left: 25px;padding-right: 25px;">Unit</th>
                        <th colspan="3" class="main-heading">Maintenance</th>
                        <th colspan="9" class="main-heading" id="year1-header">Year 2024</th>
                        <th colspan="3" class="main-heading" id="year2-header">Year 2025</th>
                        <th rowspan="2">Status</th>
                        <th rowspan="2">Payment Mode</th>
                        <th rowspan="2">
                            <label>
                                Select All <input type="checkbox" id="select-all-checkbox" onclick="toggleSelectAll()">
                            </label>
                        </th>
                        </tr>
                        <tr>
                            <th class="sub-heading">Yearly</th>
                            <th class="sub-heading">Paid</th>
                            <th class="sub-heading">Balance</th>
                            <th class="sub-heading">Apr</th>
                            <th class="sub-heading">May</th>
                            <th class="sub-heading">Jun</th>
                            <th class="sub-heading">Jul</th>
                            <th class="sub-heading">Aug</th>
                            <th class="sub-heading">Sep</th>
                            <th class="sub-heading">Oct</th>
                            <th class="sub-heading">Nov</th>
                            <th class="sub-heading">Dec</th>
                            <th class="sub-heading">Jan</th>
                            <th class="sub-heading">Feb</th>
                            <th class="sub-heading">Mar</th>
                        </tr>
                    </thead>
                    <tbody id="housing-table-body">
                        <!-- Your table data will go here -->
                    </tbody>
                </table>

                <script src="{{ asset('js/select-all-checkbox.js') }}"></script>
            </div>
        </div>
        <div class="d-flex justify-content-between " id="footer">
            <!-- Pagination controls below the table -->
            <div id="pagination-controls">
                <button id="prev-page" disabled>Previous</button>
                <span id="page-info"></span>
                <button id="next-page" disabled>Next</button>
            </div>
            <div class="footer-section">
                <button id="send-whatsapp-button" class="action-button" onclick="sendTemplateMessage()">Send Now</button>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection

<script>
    function updateYearColumns() {
        const selectedYearRange = document.getElementById('startDate').value;
        const years = selectedYearRange.split('-');
        const year1 = years[0];
        const year2 = years[1];

        document.getElementById('year1-header').innerText = 'Year ' + year1;
        document.getElementById('year2-header').innerText = 'Year ' + year2;
    }
</script>

<style>
    body {
        background-color: #f8f9fa;
        align-items: center;
        justify-content: center;
        font-family: Arial, sans-serif;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px;
    }

    .left-section {
        flex: 1;
        margin-right: 20px;
        position: relative;
    }

    .name-column {
        width: 300px;
    }

    #search-icon {
        position: absolute;
        margin-left: 170px;
        color: grey;
        margin-top: 10px;

    }

    #search-input {
        margin-bottom: 10px;
        display: flex;
        justify-content: flex-end;
        border: 1px solid lightgray
    }

    .right-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .maintenance-section {
        text-align: center;
    }

    .maintenance-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .label {
        display: inline-block;
        width: 140px;
        font-weight: bold;
        color: #333;
    }

    .maintenance-label {
        width: 160px;
        display: inline-block;
        margin-left: 20px;
    }

    .input-field {
        height: 40px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right: 10px;
    }

    .update-button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .update-button:hover {
        background-color: #0056b3;
    }

    .footer-section {
        margin-top: 20px;
        text-align: right;
    }

    .action-button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .action-button:hover {
        background-color: #0056b3;
    }

    .table {
        margin-top: 0px;
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #007bff;
        color: white;
        text-align: center;
        font-weight: bold;
        border: 1px solid #ccc;
        padding: 15px;
    }

    .main-heading {
        font-size: 18px;
        font-weight: bold;
        background-color: #0056b3;
        color: white;
    }

    .sub-heading {
        font-size: 14px;
        font-style: italic;
        background-color: #6c757d;
        color: white;
    }

    .table tbody td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tbody tr:hover {
        background-color: #ddd;
    }

    #select-all-checkbox {
        margin-left: 5px;
    }

    .month-cell {
        min-width: 100px;
        text-align: center;
    }

    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        align-self: flex-end;
    }

    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .decorative-element {
        width: 100%;
        height: 10px;
        background: linear-gradient(to left, #007bff, #0056b3, #003f7f);
        border-radius: 0 0 4px 4px;
        position: absolute;
        bottom: -45px;
        left: 0;
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


    .year-dropdown {
        width: 190px;
    }


    @media (max-width: 768px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        #footer{
        display: flex;
        flex-direction: column;
    }

        .left-section,
        .right-section {
            width: 100%;
            margin-right: 0;
            margin-bottom: 20px;
        }

        .maintenance-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .label,
        .maintenance-label,
        .input-field,
        .year-dropdown,
        .update-button {
            width: initial;
            max-width: none;
            margin-right: 68px;
        }

        .label {
            display: inline-block;
            
            font-weight: bold;
            color: #333;
            margin-top: 15px;
            /* display: flex; */
            justify-content: a;
            align-items: flex-start;
            padding-bottom: 5px;
        }

        .year-dropdown {
            width: calc(100% - 20px);
        }

        .input-field {
            /* width: calc(100% - 20px); */
        }

        .update-button {
            margin-top: 10px;
            width: 74%;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table thead th,
        .table tbody td {
            padding: 8px;
            font-size: 14px;
        }

        /* .d-flex {
            flex-direction: column;
            align-items: center;
        } */

        .footer-section {
            text-align: center;
            width: 100%;
        }

        .decorative-element {
            margin-bottom: 30px;
            width: 74%;
        }

        .footer-section{
            text-align: center;
        width: 100%;
        margin-top: 20px;
        padding-left: 60%;
        }

        #page-info {
        font-size: 11px;
        margin: 0 10px;
    }

    }
</style>
