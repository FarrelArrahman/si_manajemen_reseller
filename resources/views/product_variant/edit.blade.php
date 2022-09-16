@extends('layouts.template')

@section('title')
Ubah Varian Produk
@endsection

@section('sub-title')
Mengubah data varian produk.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($productVariant->trashed())
            <div class="alert alert-danger">
                Varian ini telah dihapus sementara. Untuk memulihkan dan menampilkan produk ini, <a href="{{ route('product_variant.restore', ['product_variant' => $product_variant->id, 'ref' => 'edit']) }}">klik di sini</a>.
            </div>
            @endif
            <form class="form form-horizontal" method="POST" action="{{ route('product_variant.update', ['product' => $productVariant->product->sku, 'productVariant' => str_replace('#', '', $productVariant->color)]) }}" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header pb-2">
                            <small>Produk</small> 
                            <h4 class="card-title">{{ $productVariant->product->product_name }}</h4>
                        </div>
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
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Varian <span class="text-danger">*</span></label>
                                            <!-- <input type="text" class="form-control @error('product_variant_name') is-invalid @enderror" name="product_variant_name"> -->
                                            <select name="product_variant_name" class="form-control @error('product_variant_name') is-invalid @enderror" id="product_variant_name" required>
                                            <option value="" disabled selected>Pilih varian atau ketik varian baru...</option>
                                            @foreach($productVariantColor as $variant)
                                            <option @if($variant->product_variant_name == $productVariant->product_variant_name) selected @endif value="{{ $variant->product_variant_name }}">{{ $variant->product_variant_name }}</option>
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
                                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="color" name="color" list="preset-colors" value="{{ $productVariant->color }}">
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
                                            <br>
                                            <a class="image-preview-old image-popup" href="{{ Storage::url($productVariant->photo) }}"><img src="{{ Storage::url($productVariant->photo) }}" alt="" width="192"></a>
                                            <img class="image-preview-new mb-2" src="#" alt="" width="192">
                                            <input type="file" class="form-control @error('unit') is-invalid @enderror mt-2 image-preview-edit" name="photo" id="">
                                            <small class="fw-bold">Kosongkan isian ini jika tidak ingin mengubah foto varian.</small>
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
                                            <input type="number" min="1" class="form-control @error('weight') is-invalid @enderror" name="weight" value="{{ $productVariant->weight }}">
                                            @error('weight')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="stock-vertical">Stok</label>
                                            <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" name="stock" value="{{ $productVariant->stock }}">
                                            <small class="fw-bold">PERHATIAN! Stok yang diubah pada halaman ini tidak dicatat pada log perubahan stok. Harap ubah stok pada halaman varian produk.</small>
                                            @error('stock')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Pokok <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('base_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="base_price" value="{{ $productVariant->base_price ?? 0 }}">
                                                @error('base_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Umum <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('base_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="general_price" value="{{ $productVariant->general_price ?? 0 }}">
                                                @error('general_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Harga Reseller <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="rupiah-prefix">Rp.</span>
                                                <input type="text" class="form-control @error('reseller_price') is-invalid @enderror money" aria-describedby="rupiah-prefix" name="reseller_price" value="{{ $productVariant->reseller_price ?? 0 }}">
                                                @error('reseller_price')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class='form-check form-switch'>
                                            <div class="checkbox">
                                                <input checked {{ $productVariant->product_variant_status == 'on' ? 'checked' : '' }} type="checkbox" id="checkbox3" class='form-check-input' name="product_variant_status">
                                                <label for="checkbox3">Tampilkan produk ini pada daftar produk</label>
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
    var url = "{{ route('product_variant.checkVariant', ['product' => $productVariant->product->sku, 'productVariant' => 'x']) }}/".replace("x", variant)
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
    let currentVariant = '{{ $productVariant->product_variant_name }}'
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
            if(json.data.exists && currentVariant != variant) {
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