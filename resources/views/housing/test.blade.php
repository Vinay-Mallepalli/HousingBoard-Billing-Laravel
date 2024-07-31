<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt</title>
  <link rel="stylesheet" href="asset('admin/scss/common/light/_fonts.scss')"> <!-- Ensure you link to your fonts.scss file -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 
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
      width: 220px;
      border-bottom: 1px solid #d63384;
      font-family: 'Times New Roman', cursive;
      color: black;
      text-align: center;
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
    .rupee-symbol {
      background-color: #103a6d;
      color: white;
      padding: 5px;
      border-radius: 10px;
      margin-right: 10px;
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
    .additional-boxes {
    display: grid;
    grid-template-rows: auto; /* Each row will take the height of its content */
    row-gap: 20px; /* Adjust the gap between the rows as needed */
    margin-top: 20px;
    margin-bottom: 20px;
}
.box-container {
            width: 45%; /* Adjust width as needed */
            margin-right: 5%; /* Adjust margin between boxes */
            float: left; /* Float elements left */
            margin-bottom: 20px; /* Adjust margin bottom as needed */
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f0f0f0;
            overflow: hidden; /* Clear float */
        }

        .box-label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .rupee-symbol {
            font-family: DejaVu Sans, sans-serif;
        }

        .box-content {
            display: inline-block;
            font-size: 1em;
        }

        /* Clear floats for the row */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }



  </style>
</head>
<body>
  <div class="receipt">
    <div class="header">
      <img src="" alt="Logo">
    </div>
    <div class="content">
      <p>
        Receipt No. <span class="line"></span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Date: <span class="line"></span>
        <br /><br />
        Received with thanks from <span class="line-wide"></span> the sum of Rupees <span class="line" style="padding-top: 20px"></span>
        By Cash/Cheque/DD No. <span class="line" style="padding-top: 20px"></span> Dated <span class="line" style="padding-top: 20px"></span> For the purpose of <span class="line-width" style="padding-top: 20px">Utility & Maintenance</span> .
      </p>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="box-container">
                    <div class="box-label font-weight-bold mb-2">Yearly Maintenance</div>
                    <div class="box d-inline-block">
                        <span class="rupee-symbol">&#8377;</span>
                        <span class="box-content">1000</span>
                    </div>
                </div>
                <div class="box-container">
                    <div class="box-label font-weight-bold mb-2">Amount Received</div>
                    <div class="box d-inline-block">
                        <span class="rupee-symbol">&#8377;</span>
                        <span class="box-content">1500</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-container">
                    <div class="box-label font-weight-bold mb-2">Total Paid</div>
                    <div class="box d-inline-block">
                        <span class="rupee-symbol">&#8377;</span>
                        <span class="box-content">2000</span>
                    </div>
                </div>
                <div class="box-container">
                    <div class="box-label font-weight-bold mb-2">Balance</div>
                    <div class="box d-inline-block">
                        <span class="rupee-symbol">&#8377;</span>
                        <span class="box-content">500</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="brand" style="font-family: 'Monotype Corsiva', cursive;">
     For SILICON LA VISTA Co-Operative Housing and Commercial Service Society
    </div>
    <div class="rectangle-and-signature">
      <div class="signature-container">
        <img src="https://res.cloudinary.com/dtzn16q9k/image/upload/v1718522946/sign-Photoroom-transformed_smc0bb.jpg" alt="Signature" class="signature-image"> <!-- Replace with the path to your signature image -->
        <div class="signature-text">Authorized Signature</div>
      </div>
      {{-- <div class="rectangle">
        <span class="rupee-symbol" style="font-family: DejaVu Sans, sans-serif;">&#8377;</span> 
        <span class="rectangle-content">{{ $totalUtility }}</span>
      </div> --}}
    </div>
    
    <div class="footer">
      <p>Society Office, Silicon La Vista, Ground Floor, C Block, Block Nr. PDPU, Jainam Banglow Road, Raysan, Ta.
        Gandhinagar, Dist. Gandhinagar, Pin Code: 382007, Gujarat
        Email: siliconlavista3@gmail.com</p>
    </div>
  </div>

    <!-- jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const { jsPDF } = window.jspdf;

            const generatePDF = () => {
                html2canvas(document.body).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('document.pdf');
                });
            };


        })

    </script>
</body>
</html>
