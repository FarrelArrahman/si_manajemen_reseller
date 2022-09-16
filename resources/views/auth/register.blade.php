<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laudable.me Reseller | Registrasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/choices.js/choices.min.css') }}">
    <style>
        body{background-color:#e5cec1}#auth{height:100vh;overflow-x:hidden}#auth #auth-right{background:url(../../storage/auth-bg.jpg);background-size:cover;height:100%}#auth #auth-left{padding:3rem 4rem;}#auth #auth-left .auth-title{font-size:1.7rem;}#auth #auth-left .auth-subtitle{color:#000;font-size:1.2rem;}#auth #auth-left .auth-logo{margin-bottom:7rem}#auth #auth-left .auth-logo img{height:3rem}@media screen and (max-width:767px){#auth #auth-left{padding:5rem}}
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
                    <h4 class="auth-title text-black">Registrasi Reseller</h4>
                    <p class="auth-subtitle text-black">Daftarkan diri Anda agar dapat login sebagai reseller.</p>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name-vertical">Nama</label>
                                    <input type="text" id="name-vertical" class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email-vertical">Email</label>
                                    <input type="email" id="email-vertical" class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>  
                            </div>
                            <!-- <div class="col-12">
                                <div class="form-group">
                                    <label for="phone-number-vertical">Nomor Telepon</label>
                                    <input type="text" id="phone-number-vertical" class="form-control @error('phone_number') is-invalid @enderror"
                                                        name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address-vertical">Alamat</label>
                                    <textarea id="address-vertical" class="form-control @error('address') is-invalid @enderror"
                                                        name="address">{{ old('address') }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div> -->
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password-vertical">Password</label>
                                    <input type="password" id="password-vertical" class="form-control @error('password') is-invalid @enderror"
                                                        name="password" value="{{ old('password') }}">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password-confirmation-vertical">Konfirmasi Password</label>
                                    <input type="password" id="password-confirmation-vertical" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-1">Daftar Sekarang</button>
                    </form>
                    <div class="mt-3 text-lg fs-6 text-center">
                        <p class='text-black'>Sudah punya akun reseller? <a href="{{ route('login') }}" class="font-bold">Klik di sini</a> untuk login.</p>
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
</html>
