@extends('auth.layouts.template')

@section('title')
Login
@endsection

@section('content')
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
                        {!! $errors->first('error') !!}
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
                        <div class="form-group position-relative mb-2">
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password" name="password">
                            <p class="mt-2"><a class="font-bold" href="{{ route('password.request') }}">Lupa password?</a></p>
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
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-4">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-6">
                        <p class="text-black">Belum punya akun reseller? <a href="{{ route('register') }}" class="font-bold">Daftar sekarang</a>.</p>
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
@endsection