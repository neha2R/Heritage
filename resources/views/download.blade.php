<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cultre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #dbe2eb !important;
            margin: auto;
            width: auto;
            text-align: center;
            font-size: 12px;
        }

        .radius {
            border-radius: 20px;
        }

        .img-fluid {
            max-width: 65% !important;
            height: auto;
        }
    </style>
</head>

<body class="container d-flex justify-content-center">
    <div class="m-5 shadow-lg col-sm-6 col-lg-4 col-md-6 h-75 py-5 bg-white radius">
        <div class="container-flex d-flex justify-content-center col-md-5 col-sm-4 col-lg-11 m-3 ">
            <div class="container">
                <div class="container shadow col-lg-8 col-md-8 col-sm-6">
                    <img src="{{ asset('/assets/images/192.png')}}" class="img-fluid">
                </div>
                <div class="p-2">
                    <h2>CULTRE</h2>
                </div>
            </div>
        </div>
        <div class=" container d-flex justify-content-center  p-5">
            <div class="">
                <div>
                    <h5 class=" ">Click here to Download Application</h5>
                </div>
                <div class="p-1"><a href="https://play.google.com/store/apps/details?id=com.heritageolympiad.quiz"><img src="{{ asset('/assets/images/apple_store.png')}}" class="img-fluid  "></a></div>
                <div class="p-1"><a href="https://www.apple.com/in/app-store/"><img src="{{ asset('/assets/images/google-play.png')}}" class="img-fluid  "></a></div>
            </div>
        </div>
    </div>
</body>

</html>