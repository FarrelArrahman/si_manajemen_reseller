@extends('layouts.template')

@section('title')
Konfigurasi
@endsection

@section('sub-title')
Konfigurasi sistem seperti email, alamat dan rekening yang digunakan oleh Laudable.me.
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendors/quill/quill.snow.css') }}">
@endsection

@section('content')
<form action="{{ route('configuration.update') }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')
<section class="section">
    <div class="card">
        <div class="card-header mb-0 pb-0">
            <h4 class="card-title">Lokasi dan Kontak</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $configuration::configName('address') }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="province-vertical">Provinsi</label>
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="city-vertical">Kabupaten/Kota</label>
                        <select data-postal-code="{{ $configuration::configName('postal_code') ?? '' }}" id="city" name="city" class="form-control @error('city') is-invalid @enderror select2">
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
                        <label for="postal_code">Kode Pos <small class="text-muted fw-bold">contoh: 80226</small></label>
                        <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                            name="postal_code" value="{{ $configuration::configName('postal_code') ?? old('postal_code') }}" placeholder="xxxxx">
                        @error('postal_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{ $configuration::configName('email') }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer_service_phone_number">Nomor Telepon Customer Service</label>
                        <input type="text" class="form-control" id="customer_service_phone_number" name="customer_service_phone_number" value="{{ $configuration::configName('customer_service_phone_number') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <div class="card-header">
                <h4 class="card-title mb-0 pb-0">Data Rekening Tujuan</h4>
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
                                <label for="account-holder-name-vertical">Nama Pemilik Rekening</label>
                                <input type="text" id="account-holder-name-vertical" class="form-control @error('account_holder_name') is-invalid @enderror"
                                    name="account_holder_name" value="{{ $configuration::configName('account_holder_name') ?? old('account_holder_name') }}" placeholder="Nama pemilik rekening">
                                @error('account_holder_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="bank-name-vertical">Nama Bank</label>
                                <input type="hidden" name="bank_name" value="{{ $configuration::configName('bank_name') ?? '' }}" id="bank_name">
                                <select data-bank-code="{{ $configuration::configName('bank_code') ?? 0 }}" id="bank_code" name="bank_code" class="form-control @error('bank_code') is-invalid @enderror select2">
                                    <option data-bank-name="" value="" disabled selected>Pilih bank...</option>
                                </select>
                                @error('bank_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="account-number-vertical">Nomor Rekening</label>
                                <input type="text" id="account-number-vertical" class="form-control @error('account_number') is-invalid @enderror"
                                    name="account_number" value="{{ $configuration::configName('account_number') ?? old('account_number') }}" placeholder="Nomor rekening">
                                @error('account_number')
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
                <h4 class="card-title mb-0 pb-0">Background Login dan Register <a target="_blank" href="{{ Storage::url($configuration::configName('auth_background_image')) }}" class="btn btn-sm btn-primary float-end">Lihat Foto Background</a></h4>
            </div>
            <div class="card-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="auth_background_image">Upload File <small class="text-muted fw-bold">(.jpg / .png)</small></label>
                                <div class="input-group">
                                    <input type="file" id="auth_background_image" class="form-control @error('auth_background_image') is-invalid @enderror" name="auth_background_image">
                                </div>
                                @error('auth_background_image')
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
                <h4 class="card-title mb-0 pb-0">Konten Halaman Bantuan</h4>
            </div>
            <div class="card-body">
                <div class="form-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div id="editor">{!! old('help') ?? $configuration::configName('help') !!}</div>
                                <input type="hidden" name="help" value="{{ old('help') ?? $configuration::configName('help') }}">
                                @error('help')
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

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Simpan Konfigurasi</button>
    </div>
</section>
</form>
@endsection

@section('js')
<script src="{{ url('js/app.js') }}"></script>
<script src="{{ asset('js/rajaongkir-shipping-cost.js') }}"></script>
<script src="{{ asset('vendors/quill/quill.min.js') }}"></script>
<script>
    $(document).ready(function() {
        let bankName = $('#bank_name')
        let bankCode = $('#bank_code')

        const getBank = () => {
            const url = "{{ asset('bank-name.js') }}"
            return fetch(url)
                .then(response => response.json())
        }

        getBank().then(json => {
            let banks = `<option value="" disabled selected>Pilih bank...</option>`
            bankCode.empty()
            for(const element of json) {
                banks += `<option data-bank-name="" value="${element.code}">${element.name}</option>`
            }
            bankCode.append(banks)
            
            if(bankCode.val() != "") {
                bankCode.val(bankCode.data('bank-code'))
            }
        })

        bankCode.on('change', function() {
            bankName.val($(this).find(':selected').text())
        })

        provinces().then(json => {
            let provinces = `<option value="" disabled selected>Pilih provinsi...</option>`
            $('#province').empty()
            for(const element of json.rajaongkir.results) {
                provinces += `<option value="${element.province_id}">${element.province}</option>`
            }
            $('#province').append(provinces)

            @if($configuration && $configuration::configName('province') != NULL)
            let provinceId = {{ $configuration::configName('province') }}
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

                @if($configuration && $configuration::configName('city') != NULL)
                let cityId = {{ $configuration::configName('city') }}
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

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['link', 'blockquote', 'code-block'],

        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
        ];

    var quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    quill.on('text-change', function(delta, oldDelta, source) {
        document.querySelector("input[name='help']").value = quill.root.innerHTML;
    });
</script>
@endsection