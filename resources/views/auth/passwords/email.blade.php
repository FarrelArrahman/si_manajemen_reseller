@extends('auth.layouts.template')

@section('title')
Lupa Password
@endsection

@section('content')
<body>
    <div id="auth">    
        <div class="row h-100">
            <div class="col-lg-6 col-12">
                <div id="auth-left">
                    <div class="auth-logo mb-5">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" srcset=""/>
                        </a>
                    </div>
                    <h1 class="auth-title text-black">Lupa Password?</h1>
                    <p class="auth-subtitle mb-4">Silahkan masukkan email yang Anda gunakan untuk login ke dalam website Reseller Laudable.me.</p>
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group position-relative mb-4">
                            <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg">Kirim Link Ubah Password</button>
                    </form>
                    <div class="mt-3 text-lg fs-6 text-center">
                        <a href="{{ route('login') }}">Kembali ke halaman login</a>
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