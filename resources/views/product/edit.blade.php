@extends('layouts.template')

@section('title')
Ubah Master Produk
@endsection

@section('sub-title')
Mengubah data master produk.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($product->trashed())
            <div class="alert alert-danger">
                Produk ini telah dihapus sementara. Untuk memulihkan dan menampilkan produk ini, <a href="{{ route('product.restore', ['product' => $product->id, 'ref' => 'edit']) }}">klik di sini</a>.
            </div>
            @endif
            <form class="form form-horizontal" method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
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
                                            <label for="product-name-vertical">Nama Produk</label>
                                            <input type="text" id="product-name-vertical" class="form-control @error('product_name') is-invalid @enderror"
                                                name="product_name" value="{{ old('product_name') ?? $product->product_name }}">
                                            @error('product_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="category-vertical">Kategori</label>
                                            <select name="category_id" class="form-control @error('category') is-invalid @enderror select2">
                                                @foreach($categories as $item)
                                                <option @if($product->category_id == $item->id) selected @endif value="{{ $item->id }}">{{ $item->category_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="unit-vertical">Satuan / Unit</label>
                                            <select name="unit_id" class="form-control @error('unit') is-invalid @enderror select2">
                                                @foreach($units as $item)
                                                <option @if($item->id == $product->unit_id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('unit_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="unit-vertical">Foto Utama Produk</label>
                                            <br>
                                            <a class="image-preview-old" href="{{ Storage::url($product->default_photo) }}"><img src="{{ Storage::url($product->default_photo) }}" alt="" width="192"></a>
                                            <img class="image-preview-new mb-2" src="#" alt="" width="192">
                                            <input type="file" class="form-control @error('unit') is-invalid @enderror mt-2 image-preview-edit" name="default_photo" id="">
                                            <small class="fw-bold">Kosongkan isian ini jika tidak ingin mengubah foto utama.</small>
                                            @error('default_photo')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description-vertical">Deskripsi</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description-vertical" rows="3" name="description">{{ old('description') ?? $product->description }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class='form-check form-switch'>
                                            <div class="checkbox">
                                                <input checked {{ old('product_status') == 'on' || $product->product_status == 1 ? 'checked' : '' }} type="checkbox" id="checkbox3" class='form-check-input' name="product_status">
                                                <label for="checkbox3">Tampilkan produk ini beserta seluruh variannya pada daftar produk</label>
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