<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('admin/images/housing.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/aos/aos.css') }}">
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .ui-datepicker-calendar {
            display: none;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;


        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        input[type="text"],
        button {
            padding: 8px;
            font-size: 14px;
        }

        button {
            background-color: #333;
            color: white;
            border: rgb(67, 114, 243);
            cursor: pointer;
            transition: background-color 0.3s;
        }


        button:hover {
            background-color: darkgrey;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            text-align: center;
            padding: 8px;
            font-weight: 500 !important;
            ;
        }

        .record-checkbox {
            margin: 0 auto;
            display: block;
        }

        /* CSS to ensure data visibility */


        .action-button1 {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        td:hover {
            transition: all 0.3s ease-in-out;
        }

        /* Modal container */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
        }

        /* Modal content */
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border: none;
            max-width: 600px;
            width: 90%;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalopen 0.3s ease-out;
        }

        /* Modal close button */
        .close-button {
            color: #333;
            float: right;
            font-size: 24px;
            font-weight: bold;
            margin-top: -10px;
        }

        .close-button:hover,
        .close-button:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* Add animation */
        @keyframes modalopen {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form styling */
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            margin-left: 70px;
        }

        .form-group label {
            flex: 0 0 180px;
            margin-right: 10px;
            text-align: right;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            width: 60%;
            margin-right: 130px;
            /* Decreased width for input fields */
        }

        .form-group input:read-only {
            background-color: #f9f9f9;
            width: 60%;
            margin-right: 130px;
            /* Decreased width for read-only fields */
        }

        .form-group select {
            cursor: pointer;
        }

        .form-group-center {
            text-align: center;
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .small-button {
            padding: 10px 30px;
            /* Adjusted padding for smaller button */
            font-size: 16px;
            /* Adjusted font size */
            background-color: #3d3deb;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .small-button:hover {
            background-color: #2606f7;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content {
                margin: 10% auto;
                padding: 20px;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-group label {
                flex: 0 0 auto;
                margin-bottom: 5px;
                text-align: left;
            }

            .form-group input,
            .form-group select {
                /* width: 100%; */
                margin: 0;
            }

            .form-group-center {
                flex-direction: column;
            }

            .small-button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        /* Small button style */
    </style>

</head>

<body>
    <div class="container-scroller">
        @include('layouts.inc.admin.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layouts.inc.admin.sidebar')
            <div class="main-panel">
                <div class="content-wrapper container-fluid">
                    <div class="row">
                        <div class="col-12">
                            @yield('content')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="selected-housing-id" value="">
    <input type="hidden" id="selected-housing-name" value="">

    <!-- Popup Modal -->
    <!-- Popup Modal -->
    <div id="month-popup" class="modal">
        <div class="modal-content mt-5">
            <span class="close-button">&times;</span>
            <h2 id="popup-title"></h2>
            <form id="popup-form">
                <div class="form-group">
                    <label for="mobile-number">Mobile Number:</label>
                    <input type="text" id="popup-mobile-number" name="mobile_number" readonly>
                </div>
                <div class="form-group">
                    <label for="yearly-maintenance">Yearly Maintenance:</label>
                    <input type="text" id="popup-yearly-maintenance" name="yearly_maintenance" readonly>
                </div>
                <div class="form-group">
                    <label for="paid">Paid:</label>
                    <input type="text" id="popup-paid" name="paid" readonly>
                </div>
                <div class="form-group">
                    <label for="balance">Balance:</label>
                    <input type="text" id="popup-balance" name="balance" readonly>
                </div>
                <input type="hidden" id="popup-name" name="name">
                <input type="hidden" id="popup-flat" name="flat">
                <input type="hidden" id="popup-date" name="date">
                <input type="hidden" id="popup-amount" name="amount">
                <input type="hidden" id="popup-paymentMode" name="paymentMode">

                <div class="form-group">
                    <label for="amount">Enter Amount:</label>
                    <input type="text" id="amount" name="amount">
                </div>
                <div class="form-group">
                    <label for="payment-mode">Select Payment Mode:</label>
                    <select id="payment-mode" name="payment_mode">
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Online Payment">Online Payment</option>
                    </select>
                </div>
                <div class="form-group-center">
                    <button type="button" class="small-button" onclick="addAmount()">Add</button>
                </div>
                <div class="form-group-center">
                    <button type="button" id="popup-send-receipt-again" class="small-button"
                        style="display: none;">Send Receipt Again</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Ensure jQuery is loaded first -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('js/yearpicker.js') }}"></script>

    <script>
        $(document).ready(function() {

            var housingId = {{ isset($housingId) ? $housingId : 'null' }};
            console.log(housingId);
            var housingName = {!! isset($housingName) ? json_encode($housingName) : 'null' !!};
            console.log(housingName);
            if (housingId) {
                document.getElementById('selected-housing-id').value = housingId;
                document.getElementById('selected-housing-name').value = housingName;
                fetchHousingData(housingId);
                $('#housing-' + housingId).addClass('active');
            }

            $('#startDate').change(function() {
                var Year = $(this).val();
                fetchHousingBills(Year, housingId);
            });

            // Automatically trigger the change event on startDate when the screen is loaded
            $('#startDate').trigger('change');

            $('.select-housing').click(function() {
                $('.select-housing').removeClass('active');
                $(this).addClass('active');
            });
        });



        function updateYearlyMaintenance() {
            var additionalUtilityInput = document.getElementById("yearly-maintenance");
            var newAdditionalUtilityValue = parseFloat(additionalUtilityInput.value);
            var perUserAdditionalUtility = newAdditionalUtilityValue.toFixed(2);
            document.querySelectorAll("td:nth-child(5)").forEach(function(cell) {
                cell.textContent = perUserAdditionalUtility;
            });
            document.querySelectorAll("td:nth-child(6)").forEach(function(cell) {
                cell.textContent = 0.00;
            });

            document.querySelectorAll("td:nth-child(7)").forEach(function(cell) {
                cell.textContent = perUserAdditionalUtility;
            });


            var selectedHousingId = document.getElementById("selected-housing-id").value;
            var selectedYearRange = document.getElementById("startDate").value;

            updateDatabase({
                yearly_maintenance: perUserAdditionalUtility,
                year: selectedYearRange,
                housing_id: selectedHousingId
            });
        }

        function updateDatabase(data) {
            // console.log('Update Database Data:', data);
            var csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            fetch("https://housingboard.automatically.live/update-utility", {

                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify(data),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    // console.log(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Bills Updated',
                        text: 'The bills have been updated successfully!'
                    });
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }

        function fetchHousingBills(Year, housingId, housingData) {
            // console.log(Year);
            // console.log(housingId);
            $.ajax({
                url: '/get-housing-bills',
                type: 'GET',
                data: {
                    housingId: housingId,
                    year: Year
                },
                success: function(response) {
                    //console.log("Fetched Bills: ", response);
                    updateBills(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        }

        function updateBills(billDetails) {
            //console.log("Bill details received:", billDetails);

            var table = document.getElementById("housing-table-body");
            for (var i = 0; i < table.rows.length; i++) {
                var row = table.rows[i];
                var mobileNumber = row.cells[1].innerText.trim();

                // Update Maintenance columns
                var yearlyMaintenanceCell = row.cells[4];
                var paidCell = row.cells[5];
                var balanceCell = row.cells[6];
                var aprCell = row.cells[7]; // April cell
                var mayCell = row.cells[8]; // May cell
                var junCell = row.cells[9]; // June cell
                var julCell = row.cells[10]; // July cell
                var augCell = row.cells[11]; // August cell
                var sepCell = row.cells[12]; // September cell
                var octCell = row.cells[13]; // October cell
                var novCell = row.cells[14]; // November cell
                var decCell = row.cells[15]; // December cell
                var janCell = row.cells[16]; // January cell
                var febCell = row.cells[17]; // February cell
                var marCell = row.cells[18]; // March cell

                // Update Maintenance data
                if (typeof billDetails.yearly_maintenance !== 'undefined') {
                    yearlyMaintenanceCell.textContent = parseFloat(billDetails.yearly_maintenance).toFixed(2);
                } else {
                    yearlyMaintenanceCell.textContent = '0.00'; // Set default value or handle as needed
                }

                if (billDetails.bill_status[mobileNumber]) {
                    var billStatus = billDetails.bill_status[mobileNumber];
                    paidCell.textContent = billStatus.paid.toFixed(2);
                    balanceCell.textContent = billStatus.balance.toFixed(2);

                    function updateMonthCell(cell, monthData) {
                        if (monthData.amount) {
                            // Extract the day from the date
                            const day = monthData.date.split('-')[0];

                            // Store the full date in a data attribute
                            cell.setAttribute('data-full-date', monthData.date);

                            // Display only the day in the cell
                            cell.innerHTML = `
            <span style="color: blue; font-size: small; display: block; margin-bottom: 4px;">${day}</span>
            <span style="color: black; display: block; margin-bottom: 4px;">${monthData.amount}</span>
            <span style="color: green; font-size: small;">${monthData.payment_mode}</span>`;
                        } else {
                            cell.innerHTML = '';
                        }
                    }



                    updateMonthCell(janCell, billStatus.jan);
                    updateMonthCell(febCell, billStatus.feb);
                    updateMonthCell(marCell, billStatus.mar);
                    updateMonthCell(aprCell, billStatus.apr);
                    updateMonthCell(mayCell, billStatus.may);
                    updateMonthCell(junCell, billStatus.jun);
                    updateMonthCell(julCell, billStatus.jul);
                    updateMonthCell(augCell, billStatus.aug);
                    updateMonthCell(sepCell, billStatus.sep);
                    updateMonthCell(octCell, billStatus.oct);
                    updateMonthCell(novCell, billStatus.nov);
                    updateMonthCell(decCell, billStatus.dec);
                } else {
                    paidCell.textContent = '0.00';
                    balanceCell.textContent = billDetails.yearly_maintenance.toFixed(2);
                    janCell.innerHTML = '';
                    febCell.innerHTML = '';
                    marCell.innerHTML = '';
                    aprCell.innerHTML = '';
                    mayCell.innerHTML = '';
                    junCell.innerHTML = '';
                    julCell.innerHTML = '';
                    augCell.innerHTML = '';
                    sepCell.innerHTML = '';
                    octCell.innerHTML = '';
                    novCell.innerHTML = '';
                    decCell.innerHTML = '';
                }

                // Update Status and Payment Mode cells
                var statusCell = row.cells[19];
                var paymentModeCell = row.cells[20];

                if (billDetails.status && billDetails.status[mobileNumber]) {
                    var status = billDetails.status[mobileNumber].status || '';
                    statusCell.textContent = status === 'completed' ? 'Completed' : (status === 'sent' ? 'Sent' : '');
                    var paymentMode = billDetails.status[mobileNumber].mode_of_payment || '';
                    paymentModeCell.textContent = paymentMode;

                } else {
                    statusCell.textContent = '';
                    paymentModeCell.textContent = '';
                }
            }
        }

        function searchHousingData() {
            var searchQuery = document.getElementById('search-input').value;
            //console.log(selectedHousingId);
            fetchHousingData(selectedHousingId, 1, searchQuery); // Reset to page 1 for new search
        }

        let currentPage = 1;
        let totalPages = 1;
        let selectedHousingId = null;

        function fetchHousingData(housingId, page = 1, searchQuery = '') {
            selectedHousingId = housingId;
            var housingName = $('.select-housing[href*="housingId=' + housingId + '"]').data('housing-name');
            document.getElementById('selected-housing').value = housingName;

            $.ajax({
                url: '/get-housing-data',
                type: 'GET',
                data: {
                    housing_name: housingName,
                    housing_id: housingId,
                    page: page,
                    search: searchQuery // Pass the search query
                },
                success: function(data) {
                    updateTable(data.data);
                    currentPage = parseInt(data.current_page);
                    totalPages = Math.ceil(data.total / data.per_page);
                    updatePaginationControls();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }


        function updateTable(data) {
            document.getElementById("housing-table-body").innerHTML = "";

            for (var i = 0; i < data.length; i++) {
                var rowData = data[i];
                var rowHtml = "<tr>";
                for (var j = 0; j < rowData.length; j++) {
                    rowHtml += "<td>" + rowData[j] + "</td>";
                }
                for (var k = 0; k < 16; k++) {
                    if (k >= 3 && k < 15) {
                        rowHtml += "<td class='month-cell'></td>";
                    } else {
                        rowHtml += "<td></td>";
                    }
                }
                rowHtml += "<td></td>";
                rowHtml += "<td><input type='checkbox' class='record-checkbox' name='selected_records[]' value='" + rowData[
                    0] + "'></td>";
                rowHtml += "</tr>";
                document.getElementById("housing-table-body").innerHTML += rowHtml;
            }

            // Add hover and click effects to month cells
            const monthCells = document.querySelectorAll('.month-cell');
            monthCells.forEach(cell => {
                // Add hover effect only if the cell is empty
                cell.addEventListener('mouseover', function() {
                    if (this.innerHTML.trim() === "") {
                        this.style.cursor = 'pointer';
                        this.setAttribute('data-original-text', this.innerHTML); // Store original text
                        this.innerHTML = 'Click here'; // Add hover text
                    }
                });

                cell.addEventListener('mouseout', function() {
                    if (this.innerHTML === 'Click here') {
                        this.innerHTML = this.getAttribute('data-original-text'); // Restore original text
                    }
                });

                cell.addEventListener('click', function() {
                    if (this.innerHTML === 'Click here') {
                        //console.log("Cell clicked");
                        const row = this.parentNode; // Get the row element
                        const col = this.cellIndex;
                        const monthIndex = (col - 7) % 12;
                        const selectedYearRange = document.getElementById('startDate').value;

                        const fiscalYear = selectedYearRange;

                        // Extract data from the row
                        const mobileNumber = row.cells[1].innerText;
                        const yearlyMaintenance = row.cells[4].innerText;
                        const paid = row.cells[5].innerText;
                        const balance = row.cells[6].innerText;
                        const name = row.cells[2].innerText;
                        const flat = row.cells[3].innerText;


                        // Call showPopup with the extracted data
                        showPopup(fiscalYear, monthIndex, mobileNumber, yearlyMaintenance, paid, balance,
                            name, flat);
                    }
                });

                // Add double-click effect for cells with data
                cell.addEventListener('dblclick', function() {
                    //console.log("double clicked");
                    if (this.innerHTML.trim() !== "" && this.innerHTML !== 'Click here') {
                        const row = this.parentNode; // Get the row element
                        const col = this.cellIndex;
                        const monthIndex = (col - 7) % 12;
                        const selectedYearRange = document.getElementById('startDate').value;

                        const fiscalYear = selectedYearRange;

                        // Extract data from the row
                        const mobileNumber = row.cells[1].innerText;
                        const yearlyMaintenance = row.cells[4].innerText;
                        const paid = row.cells[5].innerText;
                        const balance = row.cells[6].innerText;
                        const name = row.cells[2].innerText;
                        const flat = row.cells[3].innerText;

                        // Extract data from the cell
                        const date = this.getAttribute('data-full-date');
                        const amount = this.querySelector('span:nth-child(2)').innerText;
                        const paymentMode = this.querySelector('span:nth-child(3)').innerText;

                        // console.log(date);
                        // console.log(amount);
                        // console.log(paymentMode);

                        // Call showPopup with an indication to resend the receipt
                        showPopup(fiscalYear, monthIndex, mobileNumber, yearlyMaintenance, paid, balance,
                            name, flat, date, amount, paymentMode, true);
                    }
                });
            });
        }

        function updatePaginationControls() {
            var housingId = document.getElementById("selected-housing-id").value;
            var year = document.getElementById("startDate").value;
            refreshTableData(year, housingId);
            document.getElementById('page-info').innerText = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prev-page').disabled = currentPage <= 1;
            document.getElementById('next-page').disabled = currentPage >= totalPages;
        }

        document.getElementById('prev-page').addEventListener('click', function() {
            var housingId = document.getElementById("selected-housing-id").value;
            var year = document.getElementById("startDate").value;

            if (currentPage > 1) {

                fetchHousingData(selectedHousingId, currentPage - 1);
                refreshTableData(year, housingId);
            }
        });

        document.getElementById('next-page').addEventListener('click', function() {
            var housingId = document.getElementById("selected-housing-id").value;
            var year = document.getElementById("startDate").value;

            if (currentPage < totalPages) {

                fetchHousingData(selectedHousingId, currentPage + 1); // Correctly increment the page number
                refreshTableData(year, housingId);
            }
        });

        // Initial call to fetchHousingData for the first housing (you may want to set selectedHousingId appropriately)
        $('.selected-housing-name').first().click();

        function showPopup(year, monthIndex, mobileNumber, yearlyMaintenance, paid, balance, name, flat, date, amount,
            paymentMode, resend = false) {

                 // Check if the balance is zero and show SweetAlert if true
            if (parseFloat(balance) === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Balance',
                    text: 'There is no balance left to pay for this month.'
                });
                return; // Don't show the popup
            }
            //console.log(monthIndex);
            const modal = document.getElementById('month-popup');
            modal.style.display = "block";
            const title = document.getElementById('popup-title');
            const monthNames = ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar"];

            // Display the title in the popup
            title.innerText = `Year: ${year}, Month: ${monthNames[monthIndex]}`;

            // // Extract the year and month from the title
            // const [startYear, endYear] = year.split('-');
            // const monthName = monthNames[monthIndex];

            // // Convert month index to correct month number (April is 4, May is 5, ..., March is 3)
            // const adjustedMonthIndex = (monthIndex + 3) % 12 + 1; // Pad single digit with 0
            // const monthNumber = ("0" + adjustedMonthIndex).slice(-2);
            // // Determine the correct year based on the month
            // let fullYear;
            // if (["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"].includes(monthName)) {
            //     fullYear = startYear;
            // } else {
            //     fullYear = endYear;
            // }

            // // Format the date as DD-MM-YYYY
            // const formattedDate = `${("0" + date).slice(-2)}-${monthNumber}-${fullYear}`;


            // console.log(formattedDate);
            // Store the details in hidden input fields
            document.getElementById('popup-mobile-number').value = mobileNumber;
            document.getElementById('popup-yearly-maintenance').value = yearlyMaintenance;
            document.getElementById('popup-paid').value = paid;
            document.getElementById('popup-balance').value = balance;
            document.getElementById('popup-name').value = name;
            document.getElementById('popup-flat').value = flat;

            document.getElementById('popup-date').value = date;
            document.getElementById('popup-amount').value = amount;
            document.getElementById('popup-paymentMode').value = paymentMode;


            if (resend) {

                paid = paid - amount;
                //console.log(paid);
                document.getElementById("amount").style.display = "none";
                document.getElementById("payment-mode").style.display = "none";
                document.querySelector('label[for="amount"]').style.display = "none";
                document.querySelector('label[for="payment-mode"]').style.display = "none";
                document.querySelector('button[onclick="addAmount()"]').style.display = "none";
                document.getElementById("popup-send-receipt-again").style.marginTop = "-40px";
                document.getElementById("popup-send-receipt-again").style.display = "block";

            } else {
                document.getElementById("amount").style.display = "block";
                document.getElementById("payment-mode").style.display = "block";
                document.querySelector('label[for="amount"]').style.display = "block";
                document.querySelector('label[for="payment-mode"]').style.display = "block";
                document.querySelector('button[onclick="addAmount()"]').style.display = "block";
                document.getElementById("popup-send-receipt-again").style.display = "none";
            }

            // Reset the button state each time the popup is shown
            const sendButton = document.getElementById("popup-send-receipt-again");
            sendButton.disabled = false;
            sendButton.style.backgroundColor = ""; // Reset to default
            sendButton.style.color = ""; // Reset to default
            sendButton.style.cursor = ""; // Reset to default
            // Add event listener to the "Send Receipt Again" button
            document.getElementById("popup-send-receipt-again").onclick = function() {
                // Disable the button and change its color
                const sendButton = document.getElementById("popup-send-receipt-again");
                sendButton.disabled = true;
                sendButton.style.backgroundColor = "gray";
                sendButton.style.color = "white";
                sendButton.style.cursor = "not-allowed";
                // Logic to resend the receipt
                resendReceipt(year, monthIndex, mobileNumber, yearlyMaintenance, paid, balance, name, flat,
                    date,
                    amount, paymentMode);

            };


           
            // modal.style.display = 'block';
        }


        function addAmount() {
            // console.log("inside add amount");
            const housingId = document.getElementById('selected-housing-id').value;
            const mobileNumber = document.getElementById('popup-mobile-number').value;
            const yearly = parseFloat(document.getElementById('popup-yearly-maintenance').value);
            const paid = parseFloat(document.getElementById('popup-paid').value);
            const balance = parseFloat(document.getElementById('popup-balance').value);
            const amount = parseFloat(document.getElementById('amount').value);
            const paymentMode = document.getElementById('payment-mode').value;
            const name = document.getElementById('popup-name').value;
            const flat = document.getElementById('popup-flat').value;

            // Extract year and month from popup title
            const title = document.getElementById('popup-title').innerText;
            const year = title.split(':')[1].trim().split(',')[0].trim(); // Extract year from title
            const monthPart = title.split(',')[1].trim(); // Extract month part from title
            const month = monthPart.split(':')[1].trim()
                .toLowerCase(); // Extract actual month name and ensure month name is in lowercase

            // Calculate the new balance
            const newBalance = yearly - (paid + amount);

            // Check if the new balance is greater than or equal to 0
            if (newBalance < 0) {
                // Close the modal first
                document.getElementById('month-popup').style.display = 'none';

                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'The amount entered exceeds the balance. Please enter a valid amount.'
                });
                return; // Stop execution if the amount is invalid
            }

            const formData = new FormData(); // Create FormData object

            // Append data to FormData object
            formData.append('housingId', housingId);
            formData.append('mobileNumber', mobileNumber);
            formData.append('yearly', yearly);
            formData.append('paid', paid);
            formData.append('balance', balance);
            formData.append('year', year);
            formData.append('month', month);
            formData.append('amount', amount);
            formData.append('payment_mode', paymentMode); // Append payment mode
            formData.append('name', name); // Append name to the FormData
            formData.append('flat', flat);

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            fetch("https://housingboard.automatically.live/add-amount", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: formData,
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    // Close the modal after successful addition
                    document.getElementById('month-popup').style.display = 'none';

                    Swal.fire({
                        icon: 'success',
                        title: 'Amount Added',
                        text: 'The amount has been added successfully. A receipt will follow shortly.'
                    }).then(() => {
                        // Update the table with new data
                        // updateBills(data.billDetails);
                        // Call sendOnlineReceipt function
                        sendOnlineReceipt(mobileNumber, name, amount, flat, year, housingId, paymentMode,
                            yearly, paid, newBalance, this);
                        // Refresh the table to get new changes in the cells
                        refreshTableData(year, housingId);
                    });
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        }

        // Close the modal when the close button is clicked
        document.querySelector('.close-button').onclick = function() {
            document.getElementById('month-popup').style.display = 'none';
        };

        // Close the modal when clicking outside of the modal
        window.onclick = function(event) {
            const modal = document.getElementById('month-popup');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        // Function to refresh the table data
        function refreshTableData(year, housingId) {
            fetchHousingBills(year, housingId);
        }


        function toggleSelectAll() {
            var checkboxes = document.querySelectorAll('.record-checkbox');
            var selectAllCheckbox = document.getElementById('select-all-checkbox');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        function sendOnlineReceipt(number, name, total, flat, year, housingId, paymentMode, yearly, paid, newBalance,
            button) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            var formattedDate = new Date().toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).split('/').join('-');

            fetch('https://housingboard.automatically.live/send-receipt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        number: number,
                        name: name,
                        total: total,
                        flat: flat,
                        date: formattedDate,
                        paymentMode: paymentMode,
                        yearly: yearly,
                        paid: paid,
                        newBalance: newBalance
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        Swal.fire({
                            icon: 'success',
                            title: 'Receipt Sent',
                            text: 'The receipt has been sent successfully'
                        });
                        updateHousingReport(number, name, total, flat, year, housingId, paymentMode, formattedDate,
                            'Delivered');
                    } catch (error) {
                        console.error('Failed to parse response as JSON:', error);
                        updateHousingReport(number, name, total, flat, year, housingId, paymentMode, formattedDate,
                            'Not Delivered');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    updateHousingReport(number, name, total, flat, year, housingId, paymentMode, formattedDate,
                        'Not Delivered');
                });
        }

        function resendReceipt(fiscalYear, monthIndex, mobileNumber, yearlyMaintenance, paid, balance, name, flat, date,
            amount, paymentMode) {

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            var formattedDate = new Date().toLocaleDateString('en-GB', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).split('/').join('-');

            fetch('https://housingboard.automatically.live/resend-receipt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        number: mobileNumber,
                        total: amount,
                        name: name,
                        flat: flat,
                        date: date,
                        formattedDate: formattedDate,
                        paymentMode: paymentMode,
                        yearly: yearlyMaintenance,
                        paid: paid,
                        balance: balance,
                        newBalance: balance
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        Swal.fire({
                            icon: 'success',
                            title: 'Receipt Sent',
                            text: 'The receipt has been sent successfully'
                        });
                        document.getElementById('month-popup').style.display = 'none';
                        updateHousingReport(mobileNumber, name, amount, flat, fiscalYear, '', paymentMode,
                            formattedDate, 'Delivered');

                    } catch (error) {
                        console.error('Failed to parse response as JSON:', error);

                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                });
        }

        function updateHousingReport(number, name, total, flat, year, housingId, paymentMode, formattedDate,
            receiptStatus) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('https://housingboard.automatically.live/update-housing-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        number: number,
                        name: name,
                        total: total,
                        flat: flat,
                        year: year,
                        housingId: housingId,
                        paymentMode: paymentMode,
                        formattedDate: formattedDate,
                        receiptStatus: receiptStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Housing report updated:', data);
                })
                .catch(error => {
                    console.error('Error updating housing report:', error);
                });
        }


        function updateStatusToCompleted(number, date, housingId) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('https://housingboard.automatically.live/update-status', {

                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        number: number,
                        date: date,
                        housing_id: housingId,
                        status: 'completed'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // console.log(data);

                    // Update the status in the table
                    var rows = document.getElementById("housing-table-body").rows;
                    for (var i = 0; i < rows.length; i++) {
                        var cells = rows[i].cells;
                        if (cells[1].innerText.trim() === number) {
                            cells[7].innerText = 'Completed';
                            //cells[8].innerText = 'Online Payment'; // Update the payment mode

                            // Find the button element and replace it with text
                            var button = cells[8].querySelector('button');
                            if (button) {
                                var textNode = document.createTextNode('Receipt Sent');
                                button.parentNode.replaceChild(textNode, button);
                            }
                            break;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function sendTemplateMessage() {

            var checkboxes = document.querySelectorAll('.record-checkbox:checked');
            var selectedRecords = [];
            var selectedDate = document.getElementById('startDate').value;
            var selectedHousingId = document.getElementById("selected-housing-id").value;

            if (checkboxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Records Selected',
                    text: 'Please select at least one record to send the message.'
                });
                return;
            }

            // Check if selectedDate or selectedHousingId is empty
            if (!selectedDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please ensure date, utility and maintenance are given.'
                });
                return;
            }

            document.getElementById('send-whatsapp-button').disabled = true;


            // console.log(selectedDate);
            checkboxes.forEach(function(checkbox) {
                var record = {
                    id: checkbox.value,
                    name: checkbox.parentNode.parentNode.cells[2].innerText,
                    mobileNumber: checkbox.parentNode.parentNode.cells[1].innerText,
                    unit: checkbox.parentNode.parentNode.cells[3].innerText,
                    yearly_maintenance: checkbox.parentNode.parentNode.cells[4].innerText,
                    paid: checkbox.parentNode.parentNode.cells[5]
                        .innerText,
                    balance: checkbox.parentNode.parentNode.cells[6].innerText,
                    date: selectedDate,
                    housingId: selectedHousingId
                };
                // console.log(selectedRecords);
                selectedRecords.push(record);

            });


            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content');

            // Include the CSRF token in your fetch request headers
            fetch('https://housingboard.automatically.live/send-message', {

                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(selectedRecords)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent',
                        text: 'The message has been sent successfully'

                    });

                    // Update the status column to 'sent'
                    updateStatusColumn(selectedRecords);

                })
                .catch(error => {
                    console.error('Error:', error);

                });
            // Re-enable the button after 5 seconds
            setTimeout(() => {
                document.getElementById('send-whatsapp-button').disabled = false;
            }, 5000);
        }

        function updateStatusColumn(records) {
            records.forEach(function(record) {
                var row = document.querySelector(
                        `.record-checkbox[value="${record.id}"]`).parentNode
                    .parentNode;
                row.cells[19].innerText = 'Sent';
            });
        }
    </script>
    <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>
    <script src="{{ asset('admin/js/settings.js') }}"></script>
    <script src="{{ asset('admin/js/dashboard.js') }}"></script>
    <script>
        AOS.init();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add event listener for the New button
            document.getElementById('new-button').addEventListener('click', function() {
                // Clear the table body
                document.getElementById('housing-table-body').innerHTML = '';
                // Reset the dropdown to default
                document.getElementById('dropdown').selectedIndex = 0;
                // Clear the date input field
                document.getElementById('startDate').value = '';
                document.getElementById('utility').value = '';
                document.getElementById('additional-utility').value = '';
            });
        });
    </script>

    @include('sweetalert::alert')
    @livewireScripts
</body>

</html>
