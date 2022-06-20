@extends('layouts.template')

@section('title')
Tambah {{ $role }}
@endsection

@section('sub-title')
Menambah {{ $role }} baru.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <form class="form form-horizontal" method="POST" action="{{ route('user.store', $role) }}" enctype="multipart/form-data">
                <!-- User detail -->
                <div class="card">
                    <div class="card-content">
                        <div class="card-header pb-2">
                            <h4 class="card-title">Data user</h4>
                        </div>
                        <div class="card-body">
                            <!-- @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif -->
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name-vertical">Nama <span class="text-danger">*</span></label>
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
                                            <label for="photo-vertical">Foto Profil</label>
                                            <br class="image-preview-spacer" style="display: none;">
                                            <img class="image-preview mb-2" src="#" alt="" width="192">
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror image-preview-input" name="photo" id="">
                                            @error('photo')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="email-vertical">Email <span class="text-danger">*</span></label>
                                            <input type="text" id="email-vertical" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}">
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="password-confirmation-vertical">Password <span class="text-danger">*</span></label>
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
                                            <label for="password-confirmation-vertical">Konfirmasi Password <span class="text-danger">*</span></label>
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
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1">Simpan</button>
                                <button type="reset" class="btn btn-light-secondary me-1">Kosongkan Isian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- // Basic Vertical form layout section end -->
@endsection

@section('js')
@endsection