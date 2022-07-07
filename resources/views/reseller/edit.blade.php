@extends('layouts.template')

@section('title')
Isi data reseller
@endsection

@section('sub-title')
Mengisi data reseller agar dapat dilakukan verifikasi oleh Admin.
@endsection

@section('action-button')
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <form class="form form-horizontal" method="POST" action="{{ route('reseller.update', ['reseller' => $reseller->id ?? '']) }}" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                @if($reseller && ! $reseller->isActive() && $reseller->isPending())
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-1"></i> Data reseller telah dikirimkan. Harap tunggu verifikasi oleh Admin.
                </div>
                @elseif(! $reseller || ! $reseller->isActive())
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-circle me-1"></i> Data reseller belum terverifikasi oleh Admin.
                </div>
                @elseif($reseller && $reseller->isRejected())
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle me-1"></i> Data reseller tidak valid. Silakan perbaiki data yang diperlukan. Alasan: {{ $reseller->rejection_reason }}.
                </div>
                @else
                <div class="alert alert-success">
                    <i class="fa fa-check me-1"></i> Data reseller telah terverifikasi oleh Admin.
                </div>
                @endif
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 class="card-title mb-0 pb-0">Informasi Dasar</h4>
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
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="shop-name-vertical">Nama Toko <span class="text-danger">*</span></label>
                                            <input type="text" id="shop-name-vertical" class="form-control @error('shop_name') is-invalid @enderror"
                                                name="shop_name" value="{{ $reseller->shop_name ?? old('shop_name') }}" placeholder="contoh: My Hijab Shop">
                                            @error('shop_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="shop-address-vertical">Alamat <span class="text-danger">*</span></label>
                                            <textarea name="shop_address" class="form-control @error('shop_address') is-invalid @enderror" id="shop_address" rows="3" placeholder="contoh: Jl. Pegangsaan Timur No. 56">{{ $reseller->shop_address ?? old('shop_address') }}</textarea>
                                            @error('shop_address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="province-vertical">Provinsi <span class="text-danger">*</span></label>
                                            <select id="province" name="province" class="form-control @error('province') is-invalid @enderror select2">
                                                <option value="" disabled selected>Pilih provinsi...</option>
                                            </select>
                                            @error('province')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="city-vertical">Kabupaten/Kota <span class="text-danger">*</span></label>
                                            <select data-postal-code="{{ $reseller->postal_code ?? '' }}" id="city" name="city" class="form-control @error('city') is-invalid @enderror select2">
                                                <option value="" disabled selected>Pilih kabupaten/kota...</option>
                                            </select>
                                            @error('city')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="postal_code">Kode Pos <small class="text-muted fw-bold">contoh: 80226</small> <span class="text-danger">*</span></label>
                                            <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                                name="postal_code" value="{{ $reseller->postal_code ?? old('postal_code') }}" placeholder="xxxxx">
                                            @error('postal_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="phone-number-vertical">Nomor Telepon <small class="text-muted fw-bold">contoh: 085123xxxxxx</small><span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <input type="text" id="phone-number-vertical" class="form-control @error('phone_number') is-invalid @enderror"
                                                    name="phone_number" value="{{ $reseller->phone_number ?? old('phone_number') }}" placeholder="085123xxxxxx">
                                                @error('phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="shopee-link-vertical">Toko Shopee <small class="text-muted fw-bold">contoh: http://www.shopee.co.id/namatokoanda</small><span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="shopee-link-prefix"><i class="fas fa-shopping-bag fa-fw"></i></span>
                                                <input type="text" id="shopee-link-vertical" class="form-control @error('shopee_link') is-invalid @enderror"
                                                    name="shopee_link" value="{{ $reseller->shopee_link ?? old('shopee_link') }}" placeholder="URL atau Link Shopee">
                                            </div>
                                            @error('shopee_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="social-media-vertical">Media Sosial <small class="text-muted fw-bold">contoh: http://www.instagram.co.id/username</small></label>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text" id="phone-number-prefix"><i class="fab fa-facebook-f fa-fw"></i></span>
                                                <input type="text" id="phone-number-vertical" class="form-control @error('social_media.facebook') is-invalid @enderror"
                                                    name="social_media[facebook]" value="{{ $reseller->social_media['facebook'] ?? old('social_media.facebook') }}" placeholder="Facebook">
                                            </div>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text" id="phone-number-prefix"><i class="fab fa-instagram fa-fw"></i></span>
                                                <input type="text" id="phone-number-vertical" class="form-control @error('social_media.instagram') is-invalid @enderror"
                                                    name="social_media[instagram]" value="{{ $reseller->social_media['instagram'] ?? old('social_media.instagram') }}" placeholder="Instagram">
                                            </div>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text" id="phone-number-prefix"><i class="fab fa-twitter fa-fw"></i></span>
                                                <input type="text" id="phone-number-vertical" class="form-control @error('social_media.twitter') is-invalid @enderror"
                                                    name="social_media[twitter]" value="{{ $reseller->social_media['twitter'] ?? old('social_media.twitter') }}" placeholder="Twitter">
                                            </div>
                                            <div class="input-group mb-1">
                                                <span class="input-group-text" id="phone-number-prefix"><i class="fab fa-tiktok fa-fw"></i></span>
                                                <input type="text" id="phone-number-vertical" class="form-control @error('social_media.tiktok') is-invalid @enderror"
                                                    name="social_media[tiktok]" value="{{ $reseller->social_media['tiktok'] ?? old('social_media.tiktok') }}" placeholder="TikTok">
                                            </div>
                                            <small class="text-muted fw-bold">Anda dapat mengosongkan isian di atas jika tidak mempunyai media sosial tertentu.</small>
                                            @error('social_media')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <h4 class="card-title mb-0 pb-0">Scan / Foto Bukti Pembelian Pertama </h4>
                        </div>
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        @if(! $reseller || $reseller->isPending() && $reseller->isRejected())
                                        <div class="form-group">
                                            <label for="reseller-registration-proof-of-payment-vertical">Upload File <small class="text-muted fw-bold">(.jpg / .png / .pdf)</small> <span class="text-danger">*</span></label>
                                            <input type="file" id="reseller-registration-proof-of-payment-vertical" class="form-control @error('reseller_registration_proof_of_payment') is-invalid @enderror"
                                                name="reseller_registration_proof_of_payment" placeholder="cth: My Hijab Shop">
                                            @error('reseller_registration_proof_of_payment')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        @else
                                        <div class="form-group">
                                            <a download href="{{ $reseller ? Storage::url($reseller->reseller_registration_proof_of_payment) : '' }}" class="btn btn-success"><i class="fa fa-download me-1"></i> Download</a>
                                        </div>
                                        @endif
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
<script src="{{ asset('js/rajaongkir-shipping-cost.js') }}"></script>
<script>
    $(document).ready(function() {
        provinces().then(json => {
            let provinces = `<option value="" disabled selected>Pilih provinsi...</option>`
            $('#province').empty()
            for(const element of json.rajaongkir.results) {
                provinces += `<option value="${element.province_id}">${element.province}</option>`
            }
            $('#province').append(provinces)

            @if($reseller && $reseller->province != NULL)
            let provinceId = {{ $reseller->province }}
            $('#province').val(provinceId).trigger('change')
            @endif
        })

        $('#province').on('change', function() {
            $('#postal_code').val("")
            cities($(this).val()).then(json => {
                $('#city').prop('disabled', true)
                $('#city').empty()
                let cities = `<option value="" disabled selected>Pilih kabupaten/kota...</option>`
                for(const element of json.rajaongkir.results) {
                    cities += `<option data-postal-code="${element.postal_code}" value="${element.city_id}">${element.type} ${element.city_name}</option>`
                }
                $('#city').append(cities)
                $('#city').prop('disabled', false)

                @if($reseller && $reseller->city != NULL)
                let cityId = {{ $reseller->city }}
                $('#city').val(cityId).trigger('change')
                @endif
            })
        })
        
        $('#city').on('change', function() {
            if($(this).data('postal-code') == "") {
                $('#postal_code').val("")
                $('#postal_code').val($(this).find(':selected').data('postal-code'))
            } else {
                $('#postal_code').val($(this).data('postal-code'))
                $(this).data('postal-code', "")
            }
        })
    })
</script>
@endsection