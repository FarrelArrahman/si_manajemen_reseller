@extends('layouts.template')

@section('title')
Ubah profil
@endsection

@section('sub-title')
Mengubah profil dari {{ $role }}.
@endsection

@section('action-button')
@if($user->isReseller() && auth()->user()->isReseller())
<a href="{{ route('reseller.edit') }}" class="btn btn-warning">
<i class="fa fa-cog me-2"></i> Ubah Data Reseller
</a>
@endif
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if(!$user->isActive())
            <div class="alert alert-danger">
                User ini telah dinonaktifkan. Untuk mengaktifkan kembali user ini, <a href="{{ route('user.restore', ['role' => $role, 'user' => $user->id, 'ref' => 'edit']) }}">klik di sini</a>.
            </div>
            @endif
            <form class="form form-horizontal" method="POST" action="{{ route('user.update', ['role' => $role, 'user' => $user->id]) }}" enctype="multipart/form-data">
                <input type="hidden" name="ref" value="{{ $ref ?? '' }}">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif -->
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name-vertical">Nama <span class="text-danger">*</span></label>
                                            <input type="text" id="name-vertical" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ $user->name ?? old('name') }}">
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
                                            <br>
                                            <a class="image-preview-old image-popup" href="{{ Storage::url($user->photo) }}"><img src="{{ Storage::url($user->photo) }}" alt="" width="192"></a>
                                            <img class="image-preview-new mb-2" src="#" alt="" width="192">
                                            <input type="file" class="form-control @error('photo') is-invalid @enderror mt-2 image-preview-edit" name="photo" id="">
                                            <small class="fw-bold">Kosongkan isian ini jika tidak ingin mengubah foto profil.</small>
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
                                                name="email" value="{{ $user->email }}" readonly>
                                            @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="password-vertical">Password <span class="text-danger">*</span></label>
                                                <input type="password" id="password-vertical" class="form-control @error('password') is-invalid @enderror"
                                                    name="password" placeholder="(tidak diubah)" value="">
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
                                                    name="password_confirmation" placeholder="(tidak diubah)" value="{{ old('password_confirmation') }}">
                                                @error('password_confirmation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mt-0 pt-0">
                                            <div class="form-group">
                                                <small class="fw-bold">Kosongkan kolom password dan password konfirmasi jika tidak ingin mengubah password.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Kosongkan Isian</button>
                                    </div>
                                </div>
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