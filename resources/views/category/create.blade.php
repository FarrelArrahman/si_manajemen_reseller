@extends('layouts.template')

@section('title')
Tambah Kategori
@endsection

@section('sub-title')
Menambah kategori baru.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <form class="form form-horizontal" method="POST" action="{{ route('category.store') }}">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header pb-2">
                            <h4 class="card-title">Kategori</h4>
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
                                            <label for="category-name-vertical">Nama Kategori</label>
                                            <input type="text" id="category-name-vertical" class="form-control @error('category_name') is-invalid @enderror"
                                                name="category_name" value="{{ old('category_name') }}">
                                            @error('category_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description-vertical">Deskripsi</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description-vertical" rows="3" name="description">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
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