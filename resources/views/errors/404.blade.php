<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudable.me Reseller | Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/all.min.css') }}">
    <style>
        #error{background-color:#ebf3ff;height:100vh;padding-top:5rem}#error .img-error{width:50%}#error .error-title{font-size:3rem;}
    </style>
</head>

<body>
    <div id="error">
        <div class="error-page container">
            <div class="col-md-8 col-12 offset-md-2">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle text-muted fa-8x mt-5 mb-4"></i>
                    <h1 class="error-title">Error 404</h1>
                    <p class='fs-5 text-gray-600'>Halaman yang Anda tuju tidak ditemukan.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-lg btn-primary mt-3">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
