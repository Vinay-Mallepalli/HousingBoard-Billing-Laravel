<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload your Excel file</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"
        integrity="sha512-r22gChDnGvBylk90+2e/ycr3RVrDi8DIOkIGNhJlKfuyQM4tIRAI062MaV8sfjQKYVGjOBaZBOA87z+IhZE9DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        body {
            background-color: antiquewhite;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 5px 5px 0 0;
        }

        .header h4 {
            margin: 0;
        }

        .form-container {
            padding: 30px;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container .btn-primary {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container shadow border border-primary">
           <!-- Display Validation Errors -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="header">
            <h4>Upload your Housing Excel File</h4>
        </div>
        <div class="form-container" data-aos="fade-up">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('convertjson') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="housing_name" class="form-label">Housing Name</label>
                  <input type="text" id="housing_name" name="housing_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Select File</label>
                    <input type="file" id="file" name="file" accept=".xls,.xlsx" class="form-control"
                        required />
                </div>
                <button class="btn btn-primary" type="submit">Upload</button>
            </form>
        </div>
    </div>
</body>

</html>
