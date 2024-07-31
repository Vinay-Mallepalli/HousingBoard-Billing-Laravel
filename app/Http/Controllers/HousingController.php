<?php

namespace App\Http\Controllers;

use App\Imports\HousingImport;
use Illuminate\Http\Request;

use App\Models\HousingData;
use App\Models\HousingBill;
use App\Models\HousingBillStatus;
use Illuminate\Support\Facades\Hash;
use illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\HousingReport;
use Carbon\Carbon;


class HousingController extends Controller
{
    public function index()
    {
        return view("housing.index");
    }

    
    public function getHousingData(Request $request)
    {
        $housingName = $request->input('housing_name');
        $page = $request->input('page', 1); // Get the page number from the request, default to 1
        $perPage = 10; // Number of items per page
        $searchQuery = $request->input('search', ''); // Get the search query from the request
        //Log::info($searchQuery);

        $housingData = HousingData::where('housing_name', $housingName)->first();

        if (!$housingData) {
            return response()->json(['error' => 'Housing data not found'], 404);
        }

        $sheetData = json_decode($housingData->sheet_data, true);
        // Log::info($sheetData);
        $dataRows = array_slice($sheetData[0], 1); // Exclude the first row which is the header

        // Filter the data based on the search query
        if (!empty($searchQuery)) {
            $filteredData = array_filter($dataRows, function ($row) use ($searchQuery) {
                // Check if any of the fields contain the search query
                foreach ($row as $column) {
                    if (stripos($column, $searchQuery) !== false) {
                        return true;
                    }
                }
                return false;
            });
            $dataRows = array_values($filteredData) ;
        }

        // Paginate the data
        $total = count($dataRows); // Total number of rows excluding the header
        $start = ($page - 1) * $perPage; // Calculate the starting index for the current page
        $paginatedData = array_slice($dataRows, $start, $perPage);

        //Log::info($paginatedData);

        // Prepare response data
        $response = [
            'data' => $paginatedData,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
        ];

        //Log::info($response);
        return response()->json($response);
    }





    //     public function addAmount(Request $request)
// {
//     $housingId = $request->input('housingId');
//     $mobileNumber = $request->input('mobileNumber');
//     $yearly = floatval($request->input('yearly')); // Convert to float for numerical operations
//     $paid = floatval($request->input('paid')); // Convert to float for numerical operations
//     $balance = floatval($request->input('balance')); // Convert to float for numerical operations
//     $year = $request->input('year');
//     $month = strtolower($request->input('month')); // Convert month to lowercase for consistency
//     $amount = floatval($request->input('amount')); // Convert to float for numerical operations
//     $paymentMode = $request->input('payment_mode');

    //     // Get the current system date
//         // Get the current system date and format it
//         $currentDate = now()->format('d-m-Y');

    //     Log::info("in add amount");
//     Log::info($housingId);
//     Log::info($mobileNumber);
//     Log::info($yearly);
//     Log::info($paid);
//     Log::info($balance);
//     Log::info($year);
//     Log::info($month);
//     Log::info($amount);
//     Log::info($paymentMode);
//     Log::info("Current Date: " . $currentDate);

    //     try {
//         // Fetch the housing bill matching the housingId and year
//         $housingBill = HousingBill::where('housing_id', $housingId)
//             ->where('year', $year)
//             ->first();

    //         if ($housingBill) {
//             // Decode the bill_status JSON field
//             $billStatus = json_decode($housingBill->bill_status, true);

    //             if (!isset($billStatus['mobile_numbers'][$mobileNumber])) {
//                 // Initialize the mobile number entry if it doesn't exist
//                 $billStatus['mobile_numbers'][$mobileNumber] = [
//                     'paid' => 0,
//                     'balance' => $yearly, // Initialize balance with yearly maintenance
//                     'jan' => '',
//                     'feb' => '',
//                     'mar' => '',
//                     'apr' => '',
//                     'may' => '',
//                     'jun' => '',
//                     'jul' => '',
//                     'aug' => '',
//                     'sep' => '',
//                     'oct' => '',
//                     'nov' => '',
//                     'dec' => '',
//                 ];
//             }

    //             // Convert existing 'paid' and 'balance' values to float if they're not already
//             $billStatus['mobile_numbers'][$mobileNumber]['paid'] = floatval($billStatus['mobile_numbers'][$mobileNumber]['paid']);
//             $billStatus['mobile_numbers'][$mobileNumber]['balance'] = floatval($billStatus['mobile_numbers'][$mobileNumber]['balance']);

    //             // Update the paid amount and balance
//             $billStatus['mobile_numbers'][$mobileNumber]['paid'] += $amount; // Add the entered amount to 'paid'
//             $billStatus['mobile_numbers'][$mobileNumber]['balance'] = $yearly - $billStatus['mobile_numbers'][$mobileNumber]['paid']; // Update the balance

    //             // Set the specific month payment without creating new keys
//             $billStatus['mobile_numbers'][$mobileNumber][$month] = $amount;

    //             // Encode the updated bill status back to JSON
//             $housingBill->bill_status = json_encode($billStatus);
//             $housingBill->save();

    //             return response()->json(['success' => true, 'message' => 'Amount added successfully.']);
//         } else {
//             return response()->json(['success' => false, 'message' => 'Housing bill not found.'], 404);
//         }
//     } catch (\Exception $e) {
//         Log::error('Error adding amount: ' . $e->getMessage());
//         return response()->json(['success' => false, 'message' => 'Failed to add amount.'], 500);
//     }
//}

    public function addAmount(Request $request)
    {
        $housingId = $request->input('housingId');
        $mobileNumber = $request->input('mobileNumber');
        $yearly = floatval($request->input('yearly')); // Convert to float for numerical operations
        $paid = floatval($request->input('paid')); // Convert to float for numerical operations
        $balance = floatval($request->input('balance')); // Convert to float for numerical operations
        $year = $request->input('year');
        $month = strtolower($request->input('month')); // Convert month to lowercase for consistency
        $amount = floatval($request->input('amount')); // Convert to float for numerical operations
        $paymentMode = $request->input('payment_mode');

        // Get the current system date and format it
        $currentDate = now()->format('d-m-Y');
        // Extract only the day part from the current date
        $day = date('d', strtotime($currentDate));


        // Log::info("in add amount");
        // Log::info($housingId);
        // Log::info($mobileNumber);
        // Log::info($yearly);
        // Log::info($paid);
        // Log::info($balance);
        // Log::info($year);
        // Log::info($month);
        // Log::info($amount);
        // Log::info($paymentMode);
        // Log::info("Current Date: " . $currentDate);

        try {
            // Fetch the housing bill matching the housingId and year
            $housingBill = HousingBill::where('housing_id', $housingId)
                ->where('year', $year)
                ->first();

            if ($housingBill) {
                // Decode the bill_status JSON field
                $billStatus = json_decode($housingBill->bill_status, true);
                $status = json_decode($housingBill->status, true);

                if (!isset($billStatus['mobile_numbers'][$mobileNumber])) {
                    // Initialize the mobile number entry if it doesn't exist
                    $billStatus['mobile_numbers'][$mobileNumber] = [
                        'paid' => 0,
                        'balance' => $yearly, // Initialize balance with yearly maintenance
                        'jan' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'feb' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'mar' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'apr' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'may' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'jun' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'jul' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'aug' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'sep' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'oct' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'nov' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                        'dec' => ['amount' => '', 'date' => '', 'payment_mode' => ''],
                    ];
                }

                // Convert existing 'paid' and 'balance' values to float if they're not already
                $billStatus['mobile_numbers'][$mobileNumber]['paid'] = floatval($billStatus['mobile_numbers'][$mobileNumber]['paid']);
                $billStatus['mobile_numbers'][$mobileNumber]['balance'] = floatval($billStatus['mobile_numbers'][$mobileNumber]['balance']);

                // Update the paid amount and balance
                $billStatus['mobile_numbers'][$mobileNumber]['paid'] += $amount; // Add the entered amount to 'paid'
                $billStatus['mobile_numbers'][$mobileNumber]['balance'] = $yearly - $billStatus['mobile_numbers'][$mobileNumber]['paid']; // Update the balance

                // Update the specific month with the amount, date, and payment mode
                $billStatus['mobile_numbers'][$mobileNumber][$month] = [
                    'amount' => $amount,
                    'date' => $currentDate,
                    'payment_mode' => $paymentMode
                ];

                // Check if the balance is 0 and yearly maintenance equals paid amount
                if ($billStatus['mobile_numbers'][$mobileNumber]['balance'] == 0 && $billStatus['mobile_numbers'][$mobileNumber]['paid'] == $yearly) {
                    $status['mobile_numbers'][$mobileNumber]['status'] = 'completed';

                }

                // Encode the updated bill status and status back to JSON
                $housingBill->bill_status = json_encode($billStatus);
                $housingBill->status = json_encode($status);
                $housingBill->save();

                return response()->json(['success' => true, 'message' => 'Amount added successfully.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Housing bill not found.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error adding amount: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add amount.'], 500);
        }
    }

    public function updateHousingReport(Request $request)
    {
        //Log::info("update housing report");
        $data = $request->all();

        //Log::info("Received data: ", $data);

        $housingBill = HousingBill::where('year', $data['year'])->firstOrFail();

         // Log the received formattedDate
    // \Log::info('Received formattedDate:', [$data['formattedDate']]);
        //Log::info($housingBill);
        $receiptSentDate = Carbon::createFromFormat('d-m-Y', $data['formattedDate'])->toDateString();

        HousingReport::create([
            'housing_bills_id' => $housingBill->id,
            'year' => $data['year'],
            'mobile_number' => $data['number'],
            'resident_name' => $data['name'],
            'flat_number' => $data['flat'],
            'amount_paid' => $data['total'],
            'payment_mode' => $data['paymentMode'],
            'receipt_status' => $data['receiptStatus'],
            'receipt_sent_at' => $receiptSentDate,
        ]);

        return response()->json(['message' => 'Housing report updated successfully']);
    }

    public function getHousingBills(Request $request)
    {
        $housingId = $request->query('housingId');
        $year = $request->query('year');
        Log::info('Fetching housing bills for housing ID: ' . $housingId . ' and year: ' . $year);

        try {
            // Fetch the housing bill matching the housingId and year
            $housingBill = HousingBill::where('housing_id', $housingId)
                ->where('year', $year)
                ->first();

            if ($housingBill) {
                // Decode status and bill_status JSON fields
                $status = json_decode($housingBill->status, true)['mobile_numbers'] ?? [];
                $billStatus = json_decode($housingBill->bill_status, true)['mobile_numbers'] ?? [];

                // Initialize variables
                $billDetails = [
                    'yearly_maintenance' => $housingBill->yearly_maintenance,
                    'bill_status' => [],
                    'status' => $status
                ];

                // Calculate paid and balance amounts for each mobile number
                foreach ($billStatus as $mobile => $details) {
                    $paid = isset($details['paid']) && !empty($details['paid']) ? (float) $details['paid'] : 0;
                    $balance = $housingBill->yearly_maintenance - $paid;

                    // Prepare bill status data for response
                    $billDetails['bill_status'][$mobile] = [
                        'paid' => $paid,
                        'balance' => $balance,
                        'jan' => $details['jan'] ?? '',
                        'feb' => $details['feb'] ?? '',
                        'mar' => $details['mar'] ?? '',
                        'apr' => $details['apr'] ?? '',
                        'may' => $details['may'] ?? '',
                        'jun' => $details['jun'] ?? '',
                        'jul' => $details['jul'] ?? '',
                        'aug' => $details['aug'] ?? '',
                        'sep' => $details['sep'] ?? '',
                        'oct' => $details['oct'] ?? '',
                        'nov' => $details['nov'] ?? '',
                        'dec' => $details['dec'] ?? '',
                    ];
                }

                return response()->json($billDetails);
            } else {
                return response()->json([
                    'yearly_maintenance' => 0,
                    'bill_status' => [],
                    'status' => []
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching housing bill details: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch bill details'], 500);
        }
    }



}
