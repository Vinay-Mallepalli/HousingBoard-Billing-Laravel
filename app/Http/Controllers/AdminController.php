<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\HousingData;
use App\Models\HousingReport;
use App\Models\HousingBill;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Delete Admin!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $housings = HousingData::all();
        $admins = User::where('role_as', 1)->get();
        return view('admin.index', compact('admins', 'housings'));
    }

    public function showCreateForm()
    {
        $housings = HousingData::all();
        return view('admin.create', compact('housings'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        try {
            // Create the new admin user
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            // Redirect to a success page or show a success message
            return redirect()->route('admin.index')->with('success', 'Admin created successfully!');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error creating user: ' . $e->getMessage());
            // Optionally, return with an error message
            return redirect()->back()->with('error', 'Failed to create admin user.');
        }
    }

    public function edit($id)
    {
        $housings = HousingData::all();
        $admin = User::findOrFail($id);
        return view('admin.edit', compact('admin', 'housings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|confirmed',
        ]);

        $admin = User::findOrFail($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        $admin->save();
        return redirect()->route('admin.index')->with('success', 'Admin updated successfully');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();
        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully');
    }

    public function addResident($housingId)
    {
        Log::info($housingId);
        $housing = HousingData::findOrFail($housingId);
        $housings = HousingData::all();
        return view('admin.add', compact('housings', 'housing'));
    }

    public function saveResident(Request $request, $housingId)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|integer',
            'mobile_number' => 'required|digits:10',
            'name' => 'required|string',
            'unit' => 'required|string',
        ]);

        // Retrieve the housing data
        $housing = HousingData::findOrFail($housingId);

        // Decode the JSON data into a PHP array
        $sheetData = json_decode($housing->sheet_data, true);

        // Check if the ID or mobile number already exists in the sheet data
        foreach ($sheetData[0] as $row) {
            if ($row[0] == $request->id) {
                // Redirect back with error message if ID already exists
                return redirect()->route('admin.manageHousing', ['housingId' => $housingId])
                    ->with('error', 'Resident ID already exists');
            }
            if ($row[1] == $request->mobile_number) {
                // Redirect back with error message if mobile number already exists
                return redirect()->route('admin.manageHousing', ['housingId' => $housingId])
                    ->with('error', 'Mobile number already exists');
            }
            if ($row[3] == $request->unit) {
                // Redirect back with error message if unit already exists
                return redirect()->route('admin.manageHousing', ['housingId' => $housingId])
                    ->with('error', 'Flat Number already exists');
            }
        }

        // Add the new resident data
        $sheetData[0][] = [
            $request->id,
            $request->mobile_number,
            $request->name,
            $request->unit,
        ];

        // Sort the sheet data by ID
        usort($sheetData[0], function ($a, $b) {
            return (int) $a[0] - (int) $b[0];
        });

        // Encode the updated sheet data back to JSON
        $updatedSheetData = json_encode($sheetData);

        // Update the housing data with the new sheet data
        $housing->update(['sheet_data' => $updatedSheetData]);

        // Update the housing bills with the new resident's mobile number
        $housingBill = HousingBill::where('housing_id', $housingId)->first();
        if ($housingBill) {
            $status = json_decode($housingBill->status, true);
            $billStatus = json_decode($housingBill->bill_status, true);
    
            $status['mobile_numbers'][$request->mobile_number] = ["status" => "", "mode_of_payment" => ""];
            $billStatus['mobile_numbers'][$request->mobile_number] = [
                "paid" => "",
                "balance" => "",
                "jan" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "feb" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "mar" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "apr" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "may" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "jun" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "jul" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "aug" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "sep" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "oct" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "nov" => ["amount" => "", "date" => "", "payment_mode" => ""],
                "dec" => ["amount" => "", "date" => "", "payment_mode" => ""],
            ];
    
            $housingBill->status = json_encode($status);
            $housingBill->bill_status = json_encode($billStatus);
            $housingBill->save();
        }
    

        // Redirect back with success message
        return redirect()->route('admin.manageHousing', ['housingId' => $housingId])->with('success', 'Resident added successfully');
    }

    public function import()
    {
        $housings = HousingData::all();
        return view('admin.import', compact('housings'));
    }

    public function deleteResident($housingId, $rowId)
    {
        Log::info("Delete request received for ID: $rowId, Housing ID: $housingId");
        $housing = HousingData::find($housingId);

        if ($housing) {
           // Log::info("Housing entry found: ", ['housing' => $housing]);
            $sheetData = json_decode($housing->sheet_data, true);
            // Check if $sheetData is not empty and is an array
            if (!empty($sheetData) && is_array($sheetData)) {
                // Find the entry to delete
                foreach ($sheetData as $key => &$entry) {
                    foreach ($entry as $index => $row) {
                        if ($row[0] == $rowId) {
                            // Remove the entry from sheetData
                            unset($entry[$index]);
                            // Re-index the inner array
                            $entry = array_values($entry);
                            break 2;
                        }
                    }
                }
                // Re-index the outer array
                $sheetData = array_values(array_filter($sheetData));
                // Save back to JSON
                $housing->sheet_data = json_encode($sheetData);
                $housing->save();

                // Flash success message to session
                return redirect()->route('admin.manageHousing', ['housingId' => $housingId])->with('success', 'Resident deleted successfully.');
            } else {
                return redirect()->route('admin.manageHousing', ['housingId' => $housingId])->with('error', 'No data found in sheet data.');
            }
        }
        return redirect()->route('admin.manageHousing', ['housingId' => $housingId])->with('error', 'Housing entry not found.');
    }

    public function editResident($housingId, $rowId)
    {
        $housings = HousingData::all();
        // Retrieve the housing data
        $housingData = HousingData::findOrFail($housingId);
        $sheetData = json_decode($housingData->sheet_data, true);

        // Find the row to edit
        $rowData = collect($sheetData[0])->firstWhere('0', $rowId);

        return view('admin.editResident', compact('housingId', 'rowData', 'housings'));
    }

    public function updateResident(Request $request, $housingId, $rowId)
    {
        // Validate the request data
        $request->validate([
            'mobile_number' => 'required',
            'name' => 'required',
            'unit' => 'required',
        ]);

        // Retrieve the housing data
        $housingData = HousingData::findOrFail($housingId);
        $sheetData = json_decode($housingData->sheet_data, true);

        // Extract existing mobile numbers and units (excluding the current row)
        $existingMobileNumbers = [];
        $existingUnits = [];
        foreach ($sheetData[0] as $row) {
            if ($row[0] != $rowId) {
                $existingMobileNumbers[] = $row[1];
                $existingUnits[] = $row[3];
            }
        }

        // Check if the new mobile number or unit already exists
        if (in_array($request->mobile_number, $existingMobileNumbers)) {
            return redirect()->back()->withErrors(['mobile_number' => 'The mobile number is already in use.']);
        }

        if (in_array($request->unit, $existingUnits)) {
            return redirect()->back()->withErrors(['unit' => 'The unit is already in use.']);
        }

        // Find and update the row
        foreach ($sheetData[0] as &$row) {
            if ($row[0] == $rowId) {
                $row[1] = $request->mobile_number;
                $row[2] = $request->name;
                $row[3] = $request->unit;
                break;
            }
        }

        // Save the updated housing data
        $housingData->sheet_data = json_encode($sheetData);
        $housingData->save();

        return redirect()->route('admin.manageHousing', ['housingId' => $housingId])->with('success', 'Resident updated successfully.');
    }



    public function deleteHousing($housingId)
    {
        $housing = HousingData::find($housingId);
        $housing->delete();
        return redirect()->route('superadmin.dashboard')->with('success', 'Housing deleted successfully!');

    }
    public function convertExcelToJson(Request $request)
    {
       // Log::info('in method');

        // Validate the incoming request
        $request->validate([
            'housing_name' => 'required|string',
            'file' => 'required|mimes:xls,xlsx'
        ]);

        // Check if housing name already exists
        $housingName = $request->input('housing_name');
        $existingHousing = HousingData::where('housing_name', $housingName)->first();

        if ($existingHousing) {
            return redirect()->back()->withErrors(['housing_name' => 'The housing name already exists. Please enter a unique housing name.']);
        }

        // Process the file
        $file = $request->file('file');
        $data = Excel::toArray([], $file);

        // Check if the first row contains the correct headers
        $expectedHeaders = ['id', 'mobile_number', 'name', 'unit'];
        $fileHeaders = $data[0][0]; // Assuming the first sheet and the first row

        if ($fileHeaders !== $expectedHeaders) {
            return redirect()->back()->withErrors(['file' => 'Invalid file format. Please make sure the file contains id, mobile_number, name, and unit columns. There should not be any null values.']);
        }

        // Process data to ensure mobile numbers are stored as strings
        $processedData = $data;
        foreach ($processedData[0] as &$row) {
            $row[1] = (string) $row[1]; // Convert mobile number to string
        }
        $jsonData = json_encode($processedData);

        // Save the housing data
        $housingData = new HousingData();
        $housingData->housing_name = $housingName;
        $housingData->sheet_data = $jsonData;
        $housingData->save();

        return redirect()->route('superadmin.dashboard')->with('success', 'File uploaded and data inserted successfully.');
    }

    public function report()
    {
        $housings = HousingData::all();
        $reports = HousingReport::paginate(10); // Fetch all records from housing_reports table

        return view('admin.report', [
            'reports' => $reports,
            'housings' => $housings
        ]);
    }


}
