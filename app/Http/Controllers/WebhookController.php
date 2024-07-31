<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\WebHook;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use App\Models\Housing;
use App\Models\HousingBillStatus;
use App\Models\HousingBill;
use App\Models\HousingData;
use RealRashid\SweetAlert\Facades\Alert;
use CURLFILE;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

define('STDOUT', fopen('php://stdout', 'w'));
class WebhookController extends Controller
{
    private $wtCloudApi;
    private $webhook;
    private $whatsappCloudApi;
    private $phoneId;
    private $accessToken;
    public function __construct()
    {
        $this->phoneId = config('cloudapi.phoneId');
        $this->accessToken = config('cloudapi.accessToken');
        $this->wtCloudApi = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->phoneId,
            'access_token' => $this->accessToken,
        ]);
        $this->webhook = new WebHook();
        $this->webhook->verify($_GET, "vinay@100");

    }


    public function Receipt()
    {
        return view('housing.test');
    }
    public function handle(Request $request)
    {

        // $response = $this->wtCloudApi->sendTextMessage('917396672488', 'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es');
        // dd($response);

        \Log::info('Webhook received', $request->all());
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');
        $payload = json_decode($request->getContent(), true);
        Log::info(json_encode($payload));
        $buttonPayload = $payload['entry'][0]['changes'][0]['value']['messages'][0]['button']['payload'] ?? null;
        $recipientId = $payload['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'] ?? null;
        $payload = $request->input('entry')[0]['changes'][0]['value'];
        $messages = $payload['messages'] ?? [];
        if (count($messages) > 0) {
            $message = $messages[0];
            if ($message['type'] === 'interactive' && $message['interactive']['type'] === 'button_reply') {
                $buttonReply = $message['interactive']['button_reply'];
                $buttonId = $buttonReply['id'];
                $buttonParts = explode('|', $buttonId);
                if (count($buttonParts) === 4) {
                    $action = $buttonParts[0];
                    $recipientId = $buttonParts[1];
                    $paymentMethod = $buttonParts[2];
                    $date = $buttonParts[3];
                    if ($action === 'Proceed') {
                        return $this->handleProceed($recipientId, $paymentMethod, $date);
                    }
                }
            }
        }
        if (substr($recipientId, 0, 2) === '91') {
            $recipientId = substr($recipientId, 2);
        }
        if (strpos($buttonPayload, 'Yes|') === 0) {
            // Extract the number to send the message to and update the status
            list(, $numberToUpdate, $date, $housingId, $paymentMode) = explode('|', $buttonPayload);
            $this->handleYes($numberToUpdate, $recipientId, $date, $housingId, $paymentMode);
        } elseif (strpos($buttonPayload, 'No|') === 0) {
            // Update status in the database to 'pending'
            list(, $numberToUpdate, $date, $housingId, $paymentMethod) = explode('|', $buttonPayload);
            $this->handleNo($numberToUpdate, $recipientId, $date, $housingId, $paymentMethod);
        } else {
            list($paymentMethod, $date) = explode('|', $buttonPayload);
            $this->handlePaymentMethod($paymentMethod, $recipientId, $date);
        }
    }

    public function handleProceed($recipientId, $paymentMethod, $date)
    {
        Log::info("in handle proceed");
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');
        $userDetails = HousingBill::where('year', $date)->latest()->first();
        //Log::info($userDetails);

        if ($userDetails) {
            $housingId = $userDetails->housing_id;
            $housingBill = HousingData::where('id', $housingId)->first();

            if ($housingBill) {
                $dataArray = json_decode($housingBill->sheet_data, true);
                $userData = null;

                if (!empty($dataArray) && isset($dataArray[0])) {
                    foreach ($dataArray[0] as $entry) {
                        if (isset($entry[1]) && is_string($entry[1]) && trim($entry[1]) == $recipientId) {
                            $userData = $entry;
                            break;
                        }
                    }
                }
                //Log::info($userData);

                if ($userData) {
                    $name = $userData[2];
                    $flatNumber = $userData[3];
                    $mobileNumber = $userData[1];
                    $totalUtility = $userDetails->yearly_maintenance;

                    $messageText = '';
                    $additionalMessageRecipient = '';
                    $templateName = '';

                    //$currentPaymentMode = $this->disableButtons($paymentMethod, $recipientId, $date, $housingId);

                    // if ($currentPaymentMode) {
                    //     // Send a message indicating that the current payment method is already selected
                    //     $messageText = "Dear $name,\n\nYou have already selected the payment method as $currentPaymentMode. Changing the payment method is not allowed at this stage. If you have any questions or need further assistance, please contact our support team.\n\nThank you for your understanding.";
                    //     $data = [
                    //         'messaging_product' => 'whatsapp',
                    //         'recipient_type' => 'individual',
                    //         'to' => '91' . $recipientId,
                    //         'type' => 'text',
                    //         'text' => [
                    //             'body' => $messageText
                    //         ]
                    //     ];
                    //     $this->sendMessage($data, $url, $accessToken);
                    //     return;
                    // }


                    switch ($paymentMethod) {
                        case 'Cash':
                            $this->updatePaymentMode($paymentMethod, $recipientId, $date, $housingId);
                            $messageText = "Dear $name,\n\nThank you for choosing to make the payment of ₹ $totalUtility in cash. \n\nMr.Jagrutiben Pandya will be reaching out to you shortly to coordinate the transaction.\n\nThank You,\nSilicon La Vista ";
                            $additionalMessageRecipient = '918639647144'; // Send additional message to this number for Cash
                            $templateName = 'cash_payment'; // Template name for Cash payment
                            break;

                        case 'Cheque':
                            $this->updatePaymentMode($paymentMethod, $recipientId, $date, $housingId);
                            $messageText = "Dear $name,\n\nThank you for choosing to make the payment of ₹$totalUtility in cheque\n\nMr.Sushilaben M Patel will be reaching out to you shortly to coordinate the transaction.\n\nThank You,\nSilicon La Vista";
                            $additionalMessageRecipient = '919701905335'; // Send additional message to this number for Cheque
                            $templateName = 'cheque_payment'; // Template name for Cheque payment
                            break;

                        case 'Online Payment':
                            $this->updatePaymentMode($paymentMethod, $recipientId, $date, $housingId);
                            $messageText1 = "Dear $name,\n\nTo complete your payment,\n"
                                . "1. Download the QR code image.\n"
                                . "2. Open your preferred UPI payment app (Google Pay, PhonePe, Paytm, etc.)\n"
                                . "3. Scan QR code in the app.\n\n"
                                . "For assistance, please contact our support team.\n\n"
                                . "Thank you,\nSilicon La Vista.";
                            $qrCodeImageUrl = "https://res.cloudinary.com/dtzn16q9k/image/upload/v1718795643/akqhevtc9fgci4wj1yc8.png"; // Update with the actual URL of the QR code image

                            // Send the message with the QR code image
                            $this->sendMessageWithImage($recipientId, $messageText1, $qrCodeImageUrl, $url, $accessToken);

                            // Send the additional message requesting a screenshot with a clickable button link
                            $additionalMessageText = "Once the payment is done, kindly send the screenshot to below number.\n\n"
                                . "917396672488";
                            $this->sendTextMessage($recipientId, $additionalMessageText, $url, $accessToken);
                            break;

                    }

                    // Log the selected message text
                    //Log::info($messageText);

                    // Send the main message
                    $data = [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => '91' . $recipientId,
                        'type' => 'text',
                        'text' => [
                            'body' => $messageText
                        ]
                    ];
                    $this->sendMessage($data, $url, $accessToken);

                    // Send additional message using template
                    if ($additionalMessageRecipient == '918639647144' && $templateName == 'cash_payment') {
                        $this->sendAdditionalMessage($additionalMessageRecipient, $templateName, $name, $flatNumber, $mobileNumber, $totalUtility, $url, $accessToken, $date, $housingId, $paymentMethod);
                    } elseif ($additionalMessageRecipient == '919701905335' && $templateName == 'cheque_payment') {
                        $this->sendAdditionalMessage($additionalMessageRecipient, $templateName, $name, $flatNumber, $mobileNumber, $totalUtility, $url, $accessToken, $date, $housingId, $paymentMethod);
                    }
                }
            }
        }

        return response()->json(['status' => 'Proceed action handled'], 200);
    }

    private function sendTextMessage($recipientId, $messageText, $url, $accessToken)
    {
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '91' . $recipientId,
            'type' => 'text',
            'text' => [
                'body' => $messageText
            ]
        ];

        $this->sendMessage($data, $url, $accessToken);
    }

    private function sendMessageWithImage($recipientId, $messageText1, $imageUrl, $url, $accessToken)
    {
        // Example for WhatsApp API:
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $recipientId,
            'type' => 'image',
            'image' => [
                'link' => $imageUrl, // Correct key is 'link' instead of 'url'
                'caption' => $messageText1 // Add your message text as the caption
            ]
        ];

        $this->sendMessage($data, $url, $accessToken);
    }

    private function handlePaymentMethod($paymentMethod, $recipientId, $date)
    {
        Log::info("In handle payment method clicked the button " . $paymentMethod);
        $housingId = '';
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');
        $userDetails = HousingBill::where('year', $date)->latest()->first();
        $recipientId = trim($recipientId);
        $found = false;
        if ($userDetails) {
            $housingId = $userDetails->housing_id;
            $housingBill = HousingData::where('id', $housingId)->first();
            if ($housingBill) {
                $dataArray = json_decode($housingBill->sheet_data, true);
                $userData = null;
                if (!empty($dataArray) && isset($dataArray[0])) {
                    // Iterate over the inner array
                    foreach ($dataArray[0] as $entry) {
                        // Check if the mobile number matches the recipient ID
                        if (isset($entry[1]) && is_string($entry[1]) && trim($entry[1]) == $recipientId) {
                            $userData = $entry;
                            break;
                        }
                    }
                }
                if ($userData) {
                    // $name = $userData[2];
                    // $flatNumber = $userData[3];
                    // $mobileNumber = $userData[1];
                    // $utility = $userDetails->utility;
                    // $additionalUtility = $userDetails->additional_utility;
                    // $totalUtility = $userDetails->total_utility;
                    $confirmationMessage = '';

                    switch ($paymentMethod) {
                        case 'Cash':
                            $paymentMode = $paymentMethod;
                            $confirmationMessage = "You have selected Cash payment.\n\nAre you sure you want to proceed with this payment method?";
                            break;

                        case 'Cheque':
                            $paymentMode = $paymentMethod;
                            $confirmationMessage = "You have selected Cheque payment.\n\nAre you sure you want to proceed with this payment method?";
                            break;

                        case 'Online Payment':
                            $paymentMode = $paymentMethod;
                            $confirmationMessage = "You have selected Online payment.\n\nAre you sure you want to proceed with this payment method?";
                            break;
                    }
                    $this->confirmPaymentMethod($confirmationMessage, $url, $accessToken, $recipientId, $paymentMethod, $date);
                }
            }
        }
    }

    public function confirmPaymentMethod($confirmationMessage, $url, $accessToken, $recipientId, $paymentMethod, $date)
    {
        Log::info("In confirm payment method");
        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '91' . $recipientId,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => [
                    'text' => $confirmationMessage
                ],
                'action' => [
                    'buttons' => [
                        [
                            'type' => 'reply',
                            'reply' => [
                                'id' => "Proceed|$recipientId|$paymentMethod|$date",
                                'title' => 'Yes, proceed'
                            ]
                        ],

                    ]
                ]
            ]
        ];

        $this->sendMessage($data, config('cloudapi.url'), config('cloudapi.accessToken'));

    }

    public function disableButtons($paymentMethod, $recipientId, $date, $housingId)
    {
        $userDetails = HousingBill::where('year', $date)->where('housing_id', $housingId)->latest()->first();
        $recipientId = trim($recipientId); // make sure recipientId is trimmed
        Log::info($userDetails);
        if ($userDetails) {
            $status = json_decode($userDetails->status, true);

            // Update the status if the recipient ID exists in the mobile_numbers array
            if (isset($status['mobile_numbers'][$recipientId])) {
                $currentPaymentMode = $status['mobile_numbers'][$recipientId]['mode_of_payment'];
                return $currentPaymentMode;
            }
        }
        return null;
    }

    private function updatePaymentMode($paymentMode, $recipientId, $date, $housingId)
    {
        Log::info("In update Payment mode");
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');

        $userDetails = HousingBill::where('year', $date)->where('housing_id', $housingId)->latest()->first();
        $recipientId = trim($recipientId); // make sure recipientId is trimmed

        if ($userDetails) {
            $status = json_decode($userDetails->status, true);

            // Update the status if the recipient ID exists in the mobile_numbers array
            if (isset($status['mobile_numbers'][$recipientId])) {
                $currentPaymentMode = $status['mobile_numbers'][$recipientId]['mode_of_payment'];
                $paymentStatus = $status['mobile_numbers'][$recipientId]['status'];
                if (empty($currentPaymentMode)) {
                    // If the current mode of payment is empty, set it to the new payment mode
                    $status['mobile_numbers'][$recipientId]['mode_of_payment'] = $paymentMode;
                    $userDetails->status = json_encode($status);
                    $userDetails->save();
                }
            } else {
                Log::error('Recipient ID ' . $recipientId . ' not found in the status column.');
            }
        } else {
            Log::error('No HousingBill record found for the provided date and housing ID.');
        }
    }

    private function handleButtonClick($paymentMethod, $recipientId, $date)
    {
        Log::info("in button click function");
        $housingId = '';
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');
        $userDetails = HousingBill::where('date', $date)->latest()->first();
        $recipientId = trim($recipientId); // make sure recipientId is trimmed
        $found = false;
        Log::info($userDetails);
        if ($userDetails) {
            $housingId = $userDetails->housing_id;
            $housingBill = HousingData::where('id', $housingId)->first();

            if ($housingBill) {
                $dataArray = json_decode($housingBill->sheet_data, true);
                $userData = null;
                if (!empty($dataArray) && isset($dataArray[0])) {
                    // Iterate over the inner array
                    foreach ($dataArray[0] as $entry) {

                        // Check if the mobile number matches the recipient ID
                        if (isset($entry[1]) && is_string($entry[1]) && trim($entry[1]) == $recipientId) {
                            $userData = $entry;
                            break;
                        }
                    }
                }

                if ($userData) {
                    $name = $userData[2];
                    $flatNumber = $userData[3];
                    $mobileNumber = $userData[1];
                    $messageText = 'Payment is already completed. No further changes allowed.';
                    $status = json_decode($userDetails->status, true);
                    if ($status === null) {
                        // Invalid JSON, handle this error case
                        Log::error('Failed to decode status JSON: ' . json_last_error_msg());
                    } elseif (isset($status['mobile_numbers'][$mobileNumber]) && $status['mobile_numbers'][$mobileNumber]['status'] === 'completed') {
                        // Status is completed, no further action needed
                        Log::info('Payment is already completed for ' . $mobileNumber);
                        $data = [
                            'messaging_product' => 'whatsapp',
                            'recipient_type' => 'individual',
                            'to' => '91' . $recipientId,
                            'type' => 'text',
                            'text' => [
                                'body' => $messageText
                            ]
                        ];
                        $this->sendMessage($data, $url, $accessToken);
                        return true; // Message has been sent, no further action needed
                    } else {
                        // Status is empty, handle this case
                        Log::info('Status is empty for ' . $mobileNumber);
                    }

                }
            }
        }

        return false; // Message has not been sent, further processing is needed
    }

    private function handleYes($numberToUpdate, $recipientId, $date, $housingId, $paymentMethod)
    {
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');

        Log::info('Handling Yes response for number: ' . $numberToUpdate);
        Log::info('Recipient ID: ' . $recipientId);
        Log::info('Date: ' . $date);
        Log::info('Housing id:' . $housingId);
        Log::info('Payment Method:' . $paymentMethod);

        $userDetails = HousingBill::where('date', $date)->where('housing_id', $housingId)->latest()->first();
        Log::info($userDetails);

        $recipientId = trim($recipientId); // make sure recipientId is trimmed
        Log::info($userDetails);
        if ($userDetails) {
            $housingId = $userDetails->housing_id;
            Log::info("Housing ID: " . $housingId);

            $housingBill = HousingData::where('id', $housingId)->first();
            Log::info("Housing Bill: ", [$housingBill]);

            if ($housingBill) {
                $dataArray = json_decode($housingBill->sheet_data, true);
                Log::info('Checking entry: ', $dataArray);

                $userData = null;
                if (!empty($dataArray) && isset($dataArray[0])) {
                    foreach ($dataArray[0] as $entry) {
                        if (isset($entry[1]) && is_string($entry[1]) && trim($entry[1]) == $numberToUpdate) {
                            $userData = $entry;
                            break;
                        }
                    }
                }
                Log::info($userData);
                // Send the approval message
                $messageText = "Thank you for confirming the payment.\n\nThe payment has been marked as completed and we have sent the payment receipt to the resident.\n\nThank you,\nSilicon La Vista";
                $this->sendMessage([
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => '91' . $recipientId,
                    'type' => 'text',
                    'text' => [
                        'body' => $messageText
                    ]
                ], config('cloudapi.url'), config('cloudapi.accessToken'));
                if ($userData) {
                    $name = $userData[2];
                    $flatNumber = $userData[3];
                    $mobileNumber = $userData[1];

                    $utility = $userDetails->utility;
                    $additionalUtility = $userDetails->additional_utility;
                    $totalUtility = $userDetails->total_utility;
                    $image = 'https://res.cloudinary.com/dtzn16q9k/image/upload/v1718365737/header1_v6kxkr.png';
                    $receiptNumber = rand(100, 999);

                    $pdf = PDF::loadView('housing.receipt', [
                        'customerName' => $name,
                        'flatNumber' => $flatNumber,
                        'phoneNumber' => $mobileNumber,
                        'utility' => $utility,
                        'additionalUtility' => $additionalUtility,
                        'totalUtility' => $totalUtility,
                        'paymentMode' => $paymentMethod,
                        'date' => $date,
                        'image' => $image,
                        'receiptNumber' => $receiptNumber,
                    ]);

                    $pdfPath = public_path('receipts/receipt-' . $userDetails->id . '.pdf');
                    $pdf->save($pdfPath);

                    if (!file_exists($pdfPath)) {
                        Log::error('Failed to save PDF.');
                        return;
                    }

                    Log::info('PDF saved at path: ' . $pdfPath);

                    $ngrokUrl = config('cloudapi.ngrok');
                    $filePath = $pdfPath;
                    $relativePath = str_replace(public_path(), '', $filePath);
                    $fileLink = rtrim($ngrokUrl, '/') . str_replace('\\', '/', $relativePath);

                    Log::info('Generated file link: ' . $fileLink);

                    $phoneId = config('cloudapi.phoneId');
                    $accessToken = config('cloudapi.accessToken');
                    $file = $pdfPath;
                    $type = 'application/pdf';
                    $messagingProduct = 'whatsapp';

                    // Check if CURLFile class exists
                    if (class_exists('CURLFile')) {
                        $cFile = new CURLFile($file, $type, basename($file));
                    } else {
                        $cFile = '@' . realpath($file) . ';type=' . $type . ';filename=' . basename($file);
                    }

                    // Initialize cURL session
                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v19.0/{$phoneId}/media");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $accessToken,
                    ]);

                    // Prepare the multipart form data
                    $postFields = [
                        'file' => $cFile,
                        'type' => $type,
                        'messaging_product' => $messagingProduct,
                    ];

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification

                    // Execute cURL request
                    $response = curl_exec($ch);
                    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $curlError = curl_error($ch);

                    // Close cURL session
                    curl_close($ch);

                    Log::info('cURL response: ' . $response);
                    Log::info('cURL HTTP status: ' . $httpStatus);
                    Log::info('cURL error: ' . $curlError);

                    if ($httpStatus == 200) {
                        $mediaId = json_decode($response)->id;
                    } else {
                        Log::error('Failed to upload media. HTTP Status: ' . $httpStatus . '. Response: ' . $response . '. cURL error: ' . $curlError);
                        return 'Failed to upload media.';
                    }


                    $data = [
                        'messaging_product' => 'whatsapp',
                        'recipient_type' => 'individual',
                        'to' => '91' . $numberToUpdate,
                        'type' => 'template',
                        'template' => [
                            'name' => 'receipt_bill',
                            'language' => [
                                'code' => 'en_US'
                            ],
                            'components' => [
                                [
                                    'type' => 'header',
                                    'parameters' => [
                                        [
                                            'type' => 'document',
                                            'document' => [
                                                'id' => $mediaId,
                                                'filename' => 'Payment-Details.pdf'
                                            ]
                                        ]
                                    ]
                                ],
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


                    $response = curl_exec($ch);

                    if ($response === false) {
                        Log::error('cURL error: ' . curl_error($ch));
                    } else {
                        $responseData = json_decode($response, true);
                        if (isset($responseData['error'])) {
                            Log::error('Facebook Graph API error: ' . json_encode($responseData['error']));
                        } else {
                            Log::info('PDF sent successfully to ' . $numberToUpdate);
                        }
                    }
                    curl_close($ch);


                    if ($userDetails) {
                        $status = json_decode($userDetails->status, true);
                        // Update the status if the recipient ID exists in the mobile_numbers array
                        if (isset($status['mobile_numbers'][$numberToUpdate])) {
                            $status['mobile_numbers'][$numberToUpdate]['status'] = 'completed';
                            //$status['mobile_numbers'][$numberToUpdate]['mode_of_payment'] = $paymentMethod;
                            $userDetails->status = json_encode($status);
                            $userDetails->save();
                        } else {
                            Log::error('Recipient ID ' . $numberToUpdate . ' not found in the status column.');
                        }
                    } else {
                        Log::error('No HousingBill record found for the provided date and housing ID.');
                    }
                }
            }
        }
    }

    private function handleNo($numberToUpdate, $recipientId, $date, $housingId, $paymentMethod)
    {
        $url = config('cloudapi.url');
        $accessToken = config('cloudapi.accessToken');

        Log::info('Handling No response for number: ' . $numberToUpdate);
        Log::info('Recipient ID: ' . $recipientId);
        Log::info('Date: ' . $date);


        $userDetails = HousingBill::where('date', $date)->where('housing_id', $housingId)->first();
        Log::info($userDetails);


        if ($userDetails) {
            $status = json_decode($userDetails->status, true);

            // Update the status if the recipient ID exists in the mobile_numbers array
            if (isset($status['mobile_numbers'][$numberToUpdate])) {
                $status['mobile_numbers'][$numberToUpdate]['status'] = '';
                $userDetails->status = json_encode($status);
                $userDetails->save();
            } else {
                Log::error('Recipient ID ' . $numberToUpdate . ' not found in the status column.');
            }
        }

        // Send the pending message
        $messageText = "The Payment has been marked as pending. Please contact support for further assistance.";
        $this->sendMessage([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '91' . $recipientId,
            'type' => 'text',
            'text' => [
                'body' => $messageText
            ]
        ], config('cloudapi.url'), config('cloudapi.accessToken'));


    }

    private function sendMessage($data, $url, $accessToken)
    {
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

        if ($response === false) {
            Log::error('cURL error: ' . curl_error($ch));
        } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['error'])) {
                Log::error('Facebook Graph API error: ' . json_encode($responseData['error']));
            } else {
                Log::info('Message sent successfully.');
            }
        }

        curl_close($ch);
    }

    private function sendAdditionalMessage($additionalMessageRecipient, $templateName, $name, $flatNumber, $mobileNumber, $totalUtility, $url, $accessToken, $date, $housingId, $paymentMode)
    {
        Log::info("In sending additional message");
        $additionalData = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $additionalMessageRecipient,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => 'en_US'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $templateName === 'cash_payment' ? 'Jagrutiben Pandya' : 'Sushilaben M Patel',
                            ],
                            [
                                'type' => 'text',
                                'text' => $name,
                            ],
                            [
                                'type' => 'text',
                                'text' => $flatNumber,
                            ],
                            [
                                'type' => 'text',
                                'text' => $mobileNumber,
                            ],
                            [
                                'type' => 'text',
                                'text' => $totalUtility,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $additionalJsonData = json_encode($additionalData);
        $additionalCh = curl_init();
        curl_setopt($additionalCh, CURLOPT_URL, $url);
        curl_setopt($additionalCh, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($additionalCh, CURLOPT_POST, 1);
        curl_setopt($additionalCh, CURLOPT_POSTFIELDS, $additionalJsonData);
        curl_setopt($additionalCh, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($additionalCh, CURLOPT_CAINFO, 'C:\\Users\\malle\\OneDrive\\Attachments\\Desktop\\cacert (2).pem');
        $additionalResponse = curl_exec($additionalCh);

        if ($additionalResponse === false) {
            Log::error('cURL error: ' . curl_error($additionalCh));
        } else {
            $additionalResponseData = json_decode($additionalResponse, true);
            if (isset($additionalResponseData['error'])) {
                Log::error('Facebook Graph API error: ' . json_encode($additionalResponseData['error']));
            } else {
                Log::info('Additional message sent successfully.' . $additionalMessageRecipient);
            }
        }

        curl_close($additionalCh);
    }


    public function sendOnlineReceipt(Request $request)
    {
        try {
            $url = config('cloudapi.url');
            $image = 'https://res.cloudinary.com/dtzn16q9k/image/upload/v1718365737/header1_v6kxkr.png';
            $receiptNumber = rand(100, 999);

            $requestData = $request->all();
            // Log::info("request data",$requestData);
            $numberToUpdate = $requestData['number'];
            $name = $requestData['name'];
            $total = $requestData['total'];
            $flat = $requestData['flat'];
            $date = $requestData['date'];
            $paymentMode = $requestData['paymentMode'];
            $yearly = $requestData['yearly'];
            $paid = $requestData['paid'];
            $newBalance = $requestData['newBalance'];

            $pdfDirectory = public_path('receipts');
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $pdfPath = $pdfDirectory . '/' . $name . $receiptNumber . '.pdf';

            $pdf = PDF::loadView('housing.receipt', [
                'customerName' => $name,
                'flatNumber' => $flat,
                'totalUtility' => $total,
                'paymentMode' => $paymentMode,
                'date' => $date,
                'image' => $image,
                'receiptNumber' => $receiptNumber,
                'yearly' => $yearly,
                'paid' => $paid,
                'newBalance' => $newBalance

            ]);

            $pdf->save($pdfPath);

            if (!file_exists($pdfPath)) {
                Log::error('Failed to save PDF at path: ' . $pdfPath);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            Log::info('PDF saved successfully at path: ' . $pdfPath);

            $ngrokUrl = config('cloudapi.ngrok');
            $relativePath = str_replace(public_path(), '', $pdfPath);
            $fileLink = rtrim($ngrokUrl, '/') . str_replace('\\', '/', $relativePath);

            Log::info('Generated file link: ' . $fileLink);

            $phoneId = config('cloudapi.phoneId');
            $accessToken = config('cloudapi.accessToken');
            $file = $pdfPath;
            $type = 'application/pdf';
            $messagingProduct = 'whatsapp';

            $cFile = class_exists('CURLFile') ? new CURLFile($file, $type, basename($file)) : '@' . realpath($file) . ';type=' . $type . ';filename=' . basename($file);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v19.0/{$phoneId}/media");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'file' => $cFile,
                'type' => $type,
                'messaging_product' => $messagingProduct,
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            Log::info('cURL response: ' . $response);
            Log::info('cURL HTTP status: ' . $httpStatus);

            if ($httpStatus != 200) {
                Log::error('Failed to upload media. HTTP Status: ' . $httpStatus . '. Response: ' . $response . '. cURL error: ' . $curlError);
                return response()->json(['error' => 'Failed to upload media'], $httpStatus);
            }

            $responseData = json_decode($response, true);
            if (!isset($responseData['id'])) {
                Log::error('No media ID returned in response: ' . $response);
                return response()->json(['error' => 'No media ID returned'], 500);
            }

            $mediaId = $responseData['id'];
            Log::info('Media uploaded successfully with ID: ' . $mediaId);

            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '91' . $numberToUpdate,
                'type' => 'template',
                'template' => [
                    'name' => 'receipt_bill',
                    'language' => [
                        'code' => 'en_US'
                    ],
                    'components' => [
                        [
                            'type' => 'header',
                            'parameters' => [
                                [
                                    'type' => 'document',
                                    'document' => [
                                        'id' => $mediaId,
                                        'filename' => 'Payment-Details.pdf'
                                    ]
                                ]
                            ]
                        ],
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
            if ($response === false) {
                $curlError = curl_error($ch);
                Log::error('cURL error: ' . $curlError);
                curl_close($ch);
                return response()->json(['error' => $curlError], 500);
            } else {
                $responseData = json_decode($response, true);
                curl_close($ch);
                Log::info('WhatsApp API response: ' . json_encode($responseData));
                if (isset($responseData['error'])) {
                    Log::error('Facebook Graph API error: ' . json_encode($responseData['error']));
                    return response()->json(['error' => $responseData['error']], 500);
                } else {
                    Log::info('PDF sent successfully to ' . $numberToUpdate);
                    return response()->json(['message' => 'PDF sent successfully']);
                }
            }

        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resendReceipt(Request $request)
    {
        try {
            $url = config('cloudapi.url');
            $image = 'https://res.cloudinary.com/dtzn16q9k/image/upload/v1718365737/header1_v6kxkr.png';
            $receiptNumber = rand(100, 999);

            $requestData = $request->all();
            Log::info("request data", $requestData);
            $numberToUpdate = $requestData['number'];
            $name = $requestData['name'];
            $total = $requestData['total'];
            $flat = $requestData['flat'];
            $date = $requestData['date'];
            $paymentMode = $requestData['paymentMode'];
            $yearly = $requestData['yearly'];
            $paid = $requestData['paid'];
            $newBalance = $requestData['newBalance'];

            $pdfDirectory = public_path('receipts');
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $pdfPath = $pdfDirectory . '/' . $name . $receiptNumber . '.pdf';

            $pdf = PDF::loadView('housing.receipt', [
                'customerName' => $name,
                'flatNumber' => $flat,
                'totalUtility' => $total,
                'paymentMode' => $paymentMode,
                'date' => $date,
                'image' => $image,
                'receiptNumber' => $receiptNumber,
                'yearly' => $yearly,
                'paid' => $paid,
                'newBalance' => $newBalance

            ]);

            $pdf->save($pdfPath);

            if (!file_exists($pdfPath)) {
                Log::error('Failed to save PDF at path: ' . $pdfPath);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            Log::info('PDF saved successfully at path: ' . $pdfPath);

            $ngrokUrl = config('cloudapi.ngrok');
            $relativePath = str_replace(public_path(), '', $pdfPath);
            $fileLink = rtrim($ngrokUrl, '/') . str_replace('\\', '/', $relativePath);

            Log::info('Generated file link: ' . $fileLink);

            $phoneId = config('cloudapi.phoneId');
            $accessToken = config('cloudapi.accessToken');
            $file = $pdfPath;
            $type = 'application/pdf';
            $messagingProduct = 'whatsapp';

            $cFile = class_exists('CURLFile') ? new CURLFile($file, $type, basename($file)) : '@' . realpath($file) . ';type=' . $type . ';filename=' . basename($file);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v19.0/{$phoneId}/media");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'file' => $cFile,
                'type' => $type,
                'messaging_product' => $messagingProduct,
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            Log::info('cURL response: ' . $response);
            Log::info('cURL HTTP status: ' . $httpStatus);

            if ($httpStatus != 200) {
                Log::error('Failed to upload media. HTTP Status: ' . $httpStatus . '. Response: ' . $response . '. cURL error: ' . $curlError);
                return response()->json(['error' => 'Failed to upload media'], $httpStatus);
            }

            $responseData = json_decode($response, true);
            if (!isset($responseData['id'])) {
                Log::error('No media ID returned in response: ' . $response);
                return response()->json(['error' => 'No media ID returned'], 500);
            }

            $mediaId = $responseData['id'];
            Log::info('Media uploaded successfully with ID: ' . $mediaId);

            $data = [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => '91' . $numberToUpdate,
                'type' => 'template',
                'template' => [
                    'name' => 'receipt_bill',
                    'language' => [
                        'code' => 'en_US'
                    ],
                    'components' => [
                        [
                            'type' => 'header',
                            'parameters' => [
                                [
                                    'type' => 'document',
                                    'document' => [
                                        'id' => $mediaId,
                                        'filename' => 'Payment-Details.pdf'
                                    ]
                                ]
                            ]
                        ],
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
            if ($response === false) {
                $curlError = curl_error($ch);
                Log::error('cURL error: ' . $curlError);
                curl_close($ch);
                return response()->json(['error' => $curlError], 500);
            } else {
                $responseData = json_decode($response, true);
                curl_close($ch);
                Log::info('WhatsApp API response: ' . json_encode($responseData));
                if (isset($responseData['error'])) {
                    Log::error('Facebook Graph API error: ' . json_encode($responseData['error']));
                    return response()->json(['error' => $responseData['error']], 500);
                } else {
                    Log::info('PDF sent successfully to ' . $numberToUpdate);
                    return response()->json(['message' => 'PDF sent successfully']);
                }
            }

        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function updateStatus(Request $request)
    {
        $number = $request->input('number');
        $year = $request->input('year');
        $housingId = $request->input('housing_id');
        $status = $request->input('status');

        $userDetails = HousingBill::where('year', $year)->where('housing_id', $housingId)->latest()->first();

        if ($userDetails) {
            $statusData = json_decode($userDetails->status, true);

            // Update the status if the number exists in the mobile_numbers array
            if (isset($statusData['mobile_numbers'][$number])) {
                $statusData['mobile_numbers'][$number]['status'] = $status;
                $userDetails->status = json_encode($statusData);
                $userDetails->save();

                return response()->json(['message' => 'Status updated successfully']);
            } else {
                return response()->json(['message' => 'Number not found in the status column'], 404);
            }
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }


}

