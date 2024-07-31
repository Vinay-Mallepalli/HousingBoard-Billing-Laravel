<?php

namespace App\Http\Controllers;

use App\Models\Housing;
use App\Models\HousingBill;
use App\Models\HousingData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use RealRashid\SweetAlert\Facades\Alert;



use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private $cloudApi;

    public function __construct()
    {
        $this->cloudApi = new WhatsAppCloudApi([
            'from_phone_number_id' => config('cloudapi.phoneId'),
            'access_token' => config('cloudapi.accessToken'),
        ]);
    }
    public function index()
    {
        $housings = HousingData::all();
        return view('housing.dashboard', compact('housings'));
    }

    public function sendTemplateMessage(Request $request)
    {
        $selectedRecords = $request->all();
        //Log::info($selectedRecords);

        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken'); // Replace with your actual access token

        $responses = [];

        foreach ($selectedRecords as $record) {
            $name = $record['name'];
            $flatNo = $record['unit'];
            $yearlyMaintenace = $record['yearly_maintenance'];
            $paid = $record['paid'];
            $balance = $record['balance'];
            $date = $record['date'];
            $housingId = $record['housingId'];
            $mobileNumber = '91' . $record['mobileNumber'];
            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $mobileNumber,
                'type' => 'template',

                'template' => [
                    'name' => 'send_utility_bill',
                    'language' => [
                        'code' => 'en_US'
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                [
                                    'type' => 'text',
                                    'text' => $name,
                                ],
                                [
                                    'type' => 'text',
                                    'text' => $flatNo,
                                ],
                                [
                                    'type' => 'text',
                                    'text' => $yearlyMaintenace,
                                ],
                                [
                                    'type' => 'text',
                                    'text' => $paid,
                                ],
                                [
                                    'type' => 'text',
                                    'text' => $balance,
                                ]

                            ]
                        ],
                        [
                            'type' => 'button',
                            'sub_type' => 'quick_reply',
                            'index' => 0,
                            'parameters' => [
                                [
                                    'type' => 'payload',
                                    'payload' => 'Cash|' . $date,
                                ]
                            ]

                        ],
                        [
                            'type' => 'button',
                            'sub_type' => 'quick_reply',
                            'index' => 1,
                            'parameters' => [
                                [
                                    'type' => 'payload',
                                    'payload' => 'Cheque|' . $date,
                                ]
                            ]
                        ],
                        [
                            'type' => 'button',
                            'sub_type' => 'quick_reply',
                            'index' => 2,
                            'parameters' => [
                                [
                                    'type' => 'payload',
                                    'payload' => 'Online Payment|' . $date,
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $jsonData = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_CAINFO, 'C:\\Users\\malle\\OneDrive\\Attachments\\Desktop\\cacert (2).pem');
            


            $response = curl_exec($ch);
            $responses[] = $response;

            if (curl_errno($ch)) {
                Log::error("Failed to send message to $name ($mobileNumber): " . curl_error($ch));
            } else {
                $this->messageSentConfirmation($housingId, $date, $mobileNumber);
            }

            curl_close($ch);
        }

        return $responses;
    }


    public function messageSentConfirmation($housingId, $date, $mobileNumber)
    {
        log::info("in confirm");
        if (substr($mobileNumber, 0, 2) === '91') {
            $mobileNumber = substr($mobileNumber, 2);
        }
        $housingBill = HousingBill::where('housing_id', $housingId)
            ->where('year', $date)
            ->first();

        if (!$housingBill) {
            Log::error("No housing bill found for housing ID: $housingId and date: $date");
            return;
        }

        $statusData = json_decode($housingBill->status, true);

        if (!isset($statusData['mobile_numbers'][$mobileNumber])) {
            Log::error("Mobile number $mobileNumber not found in housing bill status data");
            return;
        } 

        // Update the status to 'sent' for the mobile number
        $statusData['mobile_numbers'][$mobileNumber]['status'] = 'sent';

        // Update the status column in the housingbills table
        $housingBill->status = json_encode($statusData);
        $housingBill->save();
    }



    public function saveData(Request $request)
    {
        $housingId = $request->input('housing_id');
        $year = $request->input('year');
        $yearlyMaintenance = $request->input('yearly_maintenance');
    
        // Log::info('Housing ID: ' . $housingId);
        // Log::info('Year: ' . $year);
        // Log::info('Yearly Maintenance: ' . $yearlyMaintenance);
    
        // Check if the record exists
        $existingRecord = HousingBill::where('housing_id', $housingId)->where('year', $year)->first();
    
        if ($existingRecord) {
            // Update the existing record
            $existingRecord->update([
                'yearly_maintenance' => $yearlyMaintenance,
            ]);
        } else {
            // Insert a new record
            HousingBill::create([
                'housing_id' => $housingId,
                'year' => $year,
                'yearly_maintenance' => $yearlyMaintenance,
            ]);
    
            // Retrieve the newly created record
            $existingRecord = HousingBill::where('housing_id', $housingId)
                ->where('year', $year)
                ->first();
        }
    
        if ($existingRecord) {
            // Fetch the sheet_data for the housing ID
            $housingData = HousingData::where('id', $housingId)->first();
            $sheetData = json_decode($housingData->sheet_data, true);
    
            // Log the sheet_data for debugging
           // Log::info('Sheet Data: ' . json_encode($sheetData));
    
            // Extract mobile numbers from the sheet_data
            $mobileNumbers = [];
            $billStatus = [];
            for ($i = 1; $i < count($sheetData[0]); $i++) {
                $userData = $sheetData[0][$i];
                // Initialize bill status and mode of payment to empty strings
                $mobileNumbers[$userData[1]] = ["status" => "", "mode_of_payment" => ""];
    
                // Initialize bill status with paid, balance, months, date, and payment_mode to empty strings
                $billStatus[$userData[1]] = [
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
            }
    
            // Update the status column with mobile numbers and empty bill status
            $existingRecord->status = json_encode([
                'mobile_numbers' => $mobileNumbers,
            ]);
    
            // Update the bill_status column with the new structure
            $existingRecord->bill_status = json_encode([
                'mobile_numbers' => $billStatus,
            ]);
    
            $existingRecord->save();
    
            return response()->json(['message' => 'Database updated successfully']);
        } else {
            return response()->json(['message' => 'Failed to update database'], 500);
        }
    }
    








}




