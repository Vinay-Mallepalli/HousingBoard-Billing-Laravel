<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="{{ asset('admin/scss/common/light/_fonts.scss') }}">
    <!-- Ensure you link to your fonts.scss file -->
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .receipt {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #000;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header img {
            width: 100%;
            border-radius: 10px;
        }

        .footer {
            background-color: #0c3669;
            color: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }

        .content {
            margin-top: 20px;
            width: 100%;
        }

        .content p {
            font-family: 'Monotype Corsiva', cursive;
            font-size: 20px;
            line-height: 1.6;
            color: #d63384;
            font-style: italic;
            text-align: left;
        }

        .content .line {
            display: inline-block;
            border-bottom: 1px solid #d63384;
            font-family: 'Times New Roman', cursive;
            width: 150px;
            color: black;
            text-align: center;
        }

        .content .line-wide {
            display: inline-block;
            width: 400px;
            border-bottom: 1px solid #d63384;
            font-family: 'Times New Roman', cursive;
            color: black;
            text-align: center;
        }

        .content .line-width {
            display: inline-block;
            width: 270px;
            border-bottom: 1px solid #d63384;
            font-family: 'Times New Roman', cursive;
            color: black;
            text-align: center;
        }

        .rupee-symbol {
            font-family: DejaVu Sans, sans-serif;
            margin-right: 5px;
        }

        .linehighlight {
            display: inline-flex;
            align-items: center;
            font-family: 'Times New Roman', cursive;
            color: black;
            text-align: center;
            background-color: #e0f7fa;
            border: 1px solid #007bff;
            padding: 2px 5px;
            font-weight: bold;
            border-radius: 5px;
            min-width: 150px;
            justify-content: center;
        }

        .brand {
            font-family: 'Monotype Corsiva', cursive;
            margin-top: 10px;
            text-align: right;
            color: #103a6d;
            width: 100%;
        }

        .rectangle-and-signature {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 30px;
        }

        .rectangle {
            display: flex;
            position: relative;
            bottom: 50px;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #103a6d;
            background-color: #fff;
            color: black;
            font-size: 20px;
            width: 150px;
        }

        .rectangle-content {
            text-align: center;
            flex: 1;
        }

        .signature-container {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .signature-image {
            width: 100px;
            height: auto;
        }

        .signature-text {
            font-family: 'Monotype Corsiva', cursive;
            font-size: 20px;
            color: #103a6d;
            text-align: right;
            margin-top: 5px;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <img src="{{ $image }}" alt="Logo">
        </div>
        <div class="content">
            <p>
                Receipt No. <span class="line">{{ $receiptNumber }}</span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Date: <span class="line">{{ $date }}</span>
                <br /><br />
                Received with thanks from <span class="line-wide">{{ $customerName }}</span> the sum of Rupees <span
                    class="line" style="padding-top: 20px"><span class="rupee-symbol">&#8377;</span>{{ $totalUtility }}</span>
                By Cash/Cheque/DD No. <span class="line" style="padding-top: 20px">{{ $paymentMode }}</span> Dated
                <span class="line" style="padding-top: 20px">{{ $date }}</span> For the purpose of <span
                    class="line-width" style="padding-top: 20px">Utility & Maintenance</span> .
                <br /><br />
            </p>
            <div class="amount-row">
                <span>Yearly Maintenance: <span class="linehighlight"><span class="rupee-symbol">&#8377;</span>{{ $yearly }}</span></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Already Paid: <span class="linehighlight"><span class="rupee-symbol">&#8377;</span>{{ $paid }}</span></span>
            </div>
            <div class="amount-row">
                <span>Amount Received:&nbsp;&nbsp;&nbsp; <span class="linehighlight"><span class="rupee-symbol">&#8377;</span>{{ $totalUtility }}</span></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Balance to be paid: <span class="linehighlight"><span class="rupee-symbol">&#8377;</span>{{ $newBalance }}</span></span>
            </div>
        </div>
        <div class="brand" style="font-family: 'Monotype Corsiva', cursive; margin-top:50px">
            For SILICON LA VISTA Co-Operative Housing and Commercial Service Society
        </div>
        <div class="rectangle-and-signature">
            <div class="signature-container">
                <img src="https://res.cloudinary.com/dtzn16q9k/image/upload/v1718522946/sign-Photoroom-transformed_smc0bb.jpg"
                    alt="Signature" class="signature-image"> <!-- Replace with the path to your signature image -->
                <div class="signature-text">Authorized Signature</div>
            </div>
            {{-- <div class="rectangle">
                <span class="rupee-symbol">&#8377;</span>
                <span class="rectangle-content">{{ $totalUtility }}</span>
            </div> --}}
        </div>
        <div class="footer" style="margin-top: 20px">
            <p>Society Office, Silicon La Vista, Ground Floor, C Block, Block Nr. PDPU, Jainam Banglow Road, Raysan, Ta.
                Gandhinagar, Dist. Gandhinagar, Pin Code: 382007, Email: mailmesociety@gmail.com</p>
        </div>
    </div>
</body>

</html>
