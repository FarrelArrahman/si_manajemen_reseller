@extends('auth.layouts.template')

@section('title')
Reset Password
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
                    <h4 class="auth-title text-black">Reset Password</h4>
                    <p class="auth-subtitle text-black">Masukkan password baru Anda dan konfirmasi password baru Anda pada isian di bawah ini.</p>
                    @if($errors->has('error'))
                    <div class="alert alert-danger">
                        {!! $errors->first('error') !!}
                    </div>
                    @endif
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email-vertical">Email</label>
                                    <input type="email" id="email-vertical" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" readonly>
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>  
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="password-vertical">Password Baru</label>
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
                                    <label for="password-confirmation-vertical">Konfirmasi Password Baru</label>
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
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-1">Simpan Password Baru</button>
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