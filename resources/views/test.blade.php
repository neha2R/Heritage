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
            <form action="fbdeleterequest" method="post">
                <input type="text" name="text"/>
                <input type="submit"/>
            </form>
        </div>
    </div>
</body>

</html>