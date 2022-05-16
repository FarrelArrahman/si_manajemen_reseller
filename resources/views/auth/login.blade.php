<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudable.me Reseller | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}">
    <style>
        body{background-color:#fff}#auth{height:100vh;overflow-x:hidden}#auth #auth-right{background:url(../../images/bg/4853433.jpg),linear-gradient(90deg,#2d499d,#3f5491);height:100%}#auth #auth-left{padding:3rem 4rem}#auth #auth-left .auth-title{font-size:1.7rem;}#auth #auth-left .auth-subtitle{color:#a8aebb;font-size:1.2rem;}#auth #auth-left .auth-logo{margin-bottom:7rem}#auth #auth-left .auth-logo img{height:2rem}@media screen and (max-width:767px){#auth #auth-left{padding:5rem}}
        #error{background-color:#ebf3ff;height:100vh;padding-top:5rem}#error .img-error{width:100%}#error .error-title{font-size:4rem;margin-top:3rem}
    </style>
</head>

<body>
    <div id="auth">    
        <div class="row h-100">
            <div class="col-lg-8 col-12">
                <div id="auth-left">
                    <div class="auth-logo mb-4">
                        <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo"></a>
                    </div>
                    <h1 class="auth-title">Log in.</h1>
                    <p class="auth-subtitle mb-4">Log in dengan email dan password Anda.</p>
        
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group position-relative mb-4">
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email" name="email">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group position-relative mb-4">
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" name="password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-check form-check-lg d-flex align-items-end">
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault" name="remember_me">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Ingat saya
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-6">
                        <p class="text-gray-600">Belum punya akun reseller? <a href="{{ route('register') }}" class="font-bold">Daftar Sekarang</a>.</p>
                        <p><a class="font-bold" href="auth-forgot-password.html">Lupa Password?</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div id="auth-right">
        
                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('js/picsum.js') }}"></script>
<script>
    
</script>
</html>
