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
        body{background-color:#e5cec1}#auth{height:100vh;overflow-x:hidden}#auth #auth-right{background:url(../../storage/auth-bg.jpg),linear-gradient(90deg,#2d499d,#3f5491);height:100%}#auth #auth-left{padding:3rem 4rem;}#auth #auth-left .auth-title{font-size:1.7rem;}#auth #auth-left .auth-subtitle{color:#000;font-size:1.2rem;}#auth #auth-left .auth-logo{margin-bottom:7rem}#auth #auth-left .auth-logo img{height:3rem}@media screen and (max-width:767px){#auth #auth-left{padding:5rem}}
        #error{background-color:#ebf3ff;height:100vh;padding-top:5rem}#error .img-error{width:100%}#error .error-title{font-size:4rem;margin-top:3rem}
    </style>
</head>

<body>
    <div id="auth">    
        <div class="row h-100">
            <div class="col-lg-6 col-12">
                <div id="auth-left">
                    <div class="auth-logo mb-4">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" srcset=""/>
                        </a>
                    </div>
                    <h1 class="auth-title text-black">Log in.</h1>
                    <p class="auth-subtitle mb-4">Log in dengan email dan password Anda.</p>
                    @if($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                    @endif
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group position-relative mb-4">
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
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
                            <input {{ old('remember_me') == 'on' ? 'checked' : '' }} class="form-check-input me-2" type="checkbox" id="flexCheckDefault" name="remember_me">
                            <label class="form-check-label text-black" for="flexCheckDefault">
                                Ingat saya
                            </label>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-6">
                        <p class="text-black">Belum punya akun reseller? <a href="{{ route('register') }}" class="font-bold">Daftar sekarang</a>.</p>
                        <!-- <p><a class="font-bold" href="auth-forgot-password.html">Lupa password?</a></p> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div id="auth-right">
        
                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<!-- <script src="{{ asset('js/picsum.js') }}"></script> -->
<script>
    
</script>
</html>
