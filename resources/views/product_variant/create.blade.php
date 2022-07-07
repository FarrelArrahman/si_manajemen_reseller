@extends('layouts.template')

@section('title')
Tambah Varian Produk
@endsection

@section('sub-title')
Menambah varian baru dari master produk.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <form class="form form-horizontal" method="POST" action="{{ route('product_variant.store', $product->id) }}" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header pb-2">
                            <small>Produk</small> 
                            <h4 class="card-title">{{ $product->product_name }}</h4>
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
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Varian <span class="text-danger">*</span></label>
                                            <!-- <input type="text" class="form-control @error('product_variant_name') is-invalid @enderror" name="product_variant_name"> -->
                                            <select name="product_variant_name" class="form-control @error('product_variant_name') is-invalid @enderror" id="product_variant_name" required>
                                            <option value="" disabled selected>Pilih varian atau ketik varian baru...</option>
                                            @foreach($productVariant as $variant)
                                            <option value="{{ $variant->product_variant_name }}">{{ $variant->product_variant_name }}</option>
                                            @endforeach
                                            </select>
                                            <small class="text-danger" id="variant-exists-text" style="display: none"><i class="fa fa-exclamation-circle me-1"></i> Varian ini telah ditambahkan pada produk ini.</small>
                                            @error('product_variant_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="color-vertical">Warna <span class="text-danger">*</span></label>
                                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="color" name="color" list="preset-colors">
                                            <datalist id="preset-colors">

                                            </datalist>
                                            @error('color')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="photo-vertical">Foto Produk Varian</label>
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
                                            <label for="weight-vertical">Berat Produk (gr) <span class="text-danger">*</span></label>
                                            <input type="number" min="1" class="form-control @error('weight') is-invalid @enderror" name="weight" value="1">
                                            @error('weight')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="stock-vertical">Stok Awal <span class="text-danger">*</span></label>
                                            <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" name="stock" value="1">
                                            @error('stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Pokok <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('base_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="base_price" value="{{ $product->productVariants->last()->base_price ?? 0 }}">
                                            </div>
                                            @error('base_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Umum <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('base_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="general_price" value="{{ $product->productVariants->last()->general_price ?? 0 }}">
                                            </div>
                                            @error('general_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Reseller <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('reseller_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="reseller_price" value="{{ $product->productVariants->last()->reseller_price ?? 0 }}">
                                            </div>
                                            <small class="fw-bold">*Referensi harga pokok, umum dan reseller di atas merujuk pada harga varian lain dari produk yang sama.</small>
                                            @error('reseller_price')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class='form-check form-switch'>
                                            <div class="checkbox">
                                                <input checked {{ old('product_variant_status') == 'on' ? 'checked' : '' }} type="checkbox" id="checkbox3" class='form-check-input' name="product_variant_status">
                                                <label for="checkbox3">Tampilkan varian ini pada daftar produk</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1" id="save-button">Simpan</button>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#product_variant_name').select2({
        tags: true
    })
})

let checkIfVariantExists = (variant) => {
    var url = "{{ route('product_variant.checkVariant', ['product' => $product->id, 'productVariant' => 'x']) }}/".replace("x", variant)
    return fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=UTF-8',
            'X-CSRF-Token': csrfToken
        },
    }).then(response => response.json())
}

let getVariantColors = (variant) => {
    var url = "{{ route('product_variant.color', 'x') }}/".replace("x", variant)
    return fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=UTF-8',
            'X-CSRF-Token': csrfToken
        },
    }).then(response => response.json())
}

$('#product_variant_name').on('change', function() {
    let variant = $(this).val()
    let variantExistsText = $('#variant-exists-text')
    let saveButton = $('#save-button')

    getVariantColors(variant)
        .then((json) => {
            let list = ""
            let presetColors = $('#preset-colors')

            $('#color').val("#000000")
            presetColors.empty()
            
            json.data.forEach(function(element, index) {
                if(index == 0) $('#color').val(element)

                list += `<option>${element}</option>`
            })

            presetColors.append(list)
        })
        .catch(error => error)
    
    checkIfVariantExists(variant)
        .then((json) => {
            if(json.data.exists) {
                saveButton.prop('disabled', true)
                variantExistsText.show()
            } else {
                saveButton.prop('disabled', false)
                variantExistsText.hide()
            }
        })
        .catch(error => error)
})
</script>
@endsection