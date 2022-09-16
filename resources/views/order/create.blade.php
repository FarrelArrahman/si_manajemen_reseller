@extends('layouts.template')

@section('title')
Buat Pesanan Baru
@endsection

@section('sub-title')
Memesan barang yang telah dimasukkan pada keranjang.
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
@if($cart && count($cart->cartDetail) > 0)
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
            @endif
            <form class="form form-horizontal" method="POST" action="{{ route('order.store') }}">
                <div class="card">
                    <div class="card-header mb-0 pb-0">
                        <h4 class="card-title mb-0 pb-0">Daftar Produk</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered mt-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th class="text-center" width="10%">Foto</th>
                                            <th width="35%">Varian</th>
                                            <th class="text-end" width="22.5%">Harga per pcs (Rp.)</th>
                                            <th class="text-center" width="5%">Jumlah</th>
                                            <th class="text-end" width="22.5%">Total Harga (Rp.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalPrice = 0 @endphp
                                        @foreach($cart->cartDetail as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center"><img src="{{ Storage::url($item->productVariant->photo) }}" alt="" style="object-fit: cover; width: 32px; height: 32px;"></td>
                                            <td>
                                                {{ $item->productVariant->product->product_name . ' (' . $item->productVariant->product_variant_name . ')' }}
                                                <br>
                                                @if($item->quantity_less_or_equal_than_stock === false)
                                                <small class="text-danger fw-bold">Stok tidak mencukupi. Stok Tersedia: {{ $item->productVariant->stock }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($item->productVariant->reseller_price, 0, '', '.') }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->quantity * $item->productVariant->reseller_price, 0, '', '.') }}</td>
                                        @php $totalPrice += ($item->quantity * $item->productVariant->reseller_price) @endphp
                                        @endforeach
                                        </tr>
                                        <tr>
                                            <th class="text-end" colspan="5">Sub Total</th>
                                            <th id="sub_total_price" data-sub-total-price="{{ $totalPrice }}" class="text-end">{{ number_format($totalPrice, 0, '', '.') }}</th>
                                        </tr>
                                        <tr id="shipping_price_row">
                                            <th class="text-end" colspan="5">Biaya Pengiriman</th>
                                            <th id="shipping_price" class="text-end">-</th>
                                        </tr>
                                        <tr id="grand_total_row">
                                            <th class="text-end" colspan="5">Grand Total</th>
                                            <th id="grand_total" class="text-end">-</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-header pb-2">
                            <h4 class="card-title">Pengiriman</h4>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="courier-vertical">Kurir</label>
                                            <select name="courier" class="form-select @error('courier') is-invalid @enderror" id="courier" required>
                                                <option value="" disabled selected>Pilih kurir...</option>
                                                @foreach($courier as $item)
                                                <option value="{{ $item->code }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('courier')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="service-type-vertical">Jenis Layanan</label>
                                            <select disabled name="service" class="form-select" id="service" required>
                                                <option value="" disabled selected>Pilih layanan...</option>
                                            </select>
                                            @error('service')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group">
                                            <label for="service-type-vertical">Estimasi Pengiriman</label>
                                            <p class="text-primary fs-4 fw-bold" id="etd">-</p>
                                            @error('service')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="send-from-vertical">Dikirim dari</label>
                                            <p class="text-primary fw-bold">
                                                <span id="send-from-address">{{ $configuration::configName('address') }}</span><br>
                                                <span id="send-from-city">{{ $configuration::configName('city') }}</span>, <span id="send-from-province">{{ $configuration::configName('province') }}</span><br>
                                                <span id="send-from-postal-code">{{ $configuration::configName('postal_code') }}</span><br>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="send-to-vertical">Alamat tujuan</label>
                                            <p class="text-primary fw-bold" id="send-to-vertical">
                                                <span id="send-to-address"></span><br>
                                                <span id="send-to-city"></span>, <span id="send-to-province"></span><br>
                                                <span id="send-to-postal-code"></span><br>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary btn-block" id="place-order">Ajukan Pesanan</button>
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
@else
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center" id="not_available">
                                <i class="fa fa-shopping-cart text-muted fa-4x my-3"></i>
                                <h4>Oops, keranjang Anda kosong!</h4>
                                <p>Silahkan pilih produk pada menu Inventori agar dapat mengajukan pemesanan.</p>
                                <a href="{{ route('inventory.index') }}" class="btn btn-primary">Kembali ke Inventori</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!-- // Basic Vertical form layout section end -->
@endsection

@section('js')
<script src="{{ asset('js/rajaongkir-shipping-cost.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    let provinceList, 
    cityList, 
    resellerId, 
    resellerAddress, 
    resellerProvince, 
    resellerCity, 
    resellerProvinceId, 
    resellerCityId, 
    resellerPostalCode,
    ownerProvince,
    ownerCity,
    ownerProvinceId,
    ownerCityId

    provinces().then(json => {
        provinceList = json.rajaongkir.results
        let provinces = `<option value="" disabled selected>Pilih provinsi...</option>`
        $('#province').empty()
        for(const element of json.rajaongkir.results) {
            provinces += `<option value="${element.province_id}">${element.province}</option>`
        }
        $('#province').append(provinces)

        @if(auth()->user()->reseller && auth()->user()->reseller->province != NULL)
        let provinceId = {{ auth()->user()->reseller->province }}
        $('#province').val(provinceId).trigger('change')
        @endif

        cities().then(json => {
            cityList = json.rajaongkir.results

            getResellerDetail(resellerId).then(json => {
                let p2 = provinceList.find(obj => obj.province_id == json.data.province)
                let c2 = cityList.find(obj => obj.city_id == json.data.city)

                resellerAddress = json.data.shop_address
                resellerProvince = p2.province
                resellerCity = c2.city_name
                resellerPostalCode = json.data.postal_code
                resellerCityId = json.data.city
                resellerProvinceId = json.data.province

                setResellerAddress()
                setOwnerAddress()
            })
        })
    })

    let setOwnerAddress = () => {
        ownerProvinceId = $('#send-from-province').text()
        ownerCityId = $('#send-from-city').text()

        ownerProvince = provinceList.find(obj => obj.province_id == ownerProvinceId)
        ownerCity = cityList.find(obj => obj.city_id == ownerCityId)
        
        $('#send-from-city').text(ownerCity.city_name)
        $('#send-from-province').text(ownerProvince.province)
    }

    let setResellerAddress = () => {
        $('#send-to-address').text(resellerAddress)
        $('#send-to-city').text(resellerCity)
        $('#send-to-province').text(resellerProvince)
        $('#send-to-postal-code').text(resellerPostalCode)
    }

    resellerId = {{ auth()->user()->reseller->id }}
    let getResellerDetail = (resellerId) => {
        var url = "{{ route('reseller.detail', 'x') }}/".replace("x", resellerId)
        return fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    $('#province').on('change', function() {
        resetPrice()
        
        $('#postal_code').val("")
        $('#city').prop('disabled', true)
        
        cities($(this).val()).then(json => {
            $('#city').empty()
            let cities = `<option value="" disabled selected>Pilih kabupaten/kota...</option>`
            for(const element of json.rajaongkir.results) {
                cities += `<option data-postal-code="${element.postal_code}" value="${element.city_id}">${element.type} ${element.city_name}</option>`
            }
            $('#city').append(cities)
            $('#city').prop('disabled', false)

            @if(auth()->user()->reseller && auth()->user()->reseller->city != NULL)
            let cityId = {{ auth()->user()->reseller->city }}
            $('#city').val(cityId).trigger('change')
            @endif
        })

        resellerProvinceId = $(this).val()
    })
    
    $('#city').on('change', function() {
        resetPrice()

        if($(this).data('postal-code') == "") {
            $('#postal_code').val("")
            $('#postal_code').val($(this).find(':selected').data('postal-code'))
        } else {
            $('#postal_code').val($(this).data('postal-code'))
            $(this).data('postal-code', "")
        }
        resellerCityId = $(this).val()
    })

    let costs = (origin, destination, weight, courier) => {
        const API_KEY = `e22f1c6f62ab0ff49b35f91cf61a3362`
        let data = { origin, destination, weight, courier, key: API_KEY }
        let url = "{{ route('rajaongkir.cost') }}"

        return fetch(url, {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            }
        })
        .then(response => response.json())
    }

    $('#courier').on('change', function() {
        let courier = $(this).val()
        let weight = {{ $totalWeight }}

        resetPrice()
        $('#service').prop('disabled', true)
        $('#etd').text('-')

        costs(ownerCityId, resellerCityId, weight, courier).then(json => {
            $('#service').empty()
            let services = `<option value="" disabled selected>Pilih layanan...</option>`
            for(const item of json.data.rajaongkir.results[0].costs) {
                console.log(`${courier} ${item.service} Cost: ${item.cost[0].value}`)
                services += `<option data-etd="${item.cost[0].etd}" data-price="${item.cost[0].value}" value="${item.service}">${item.service}</option>`
            }
            $('#service').append(services)
            $('#service').prop('disabled', false)
        })
    })

    $('#service').on('change', function() {
        let etd = $(this).find(':selected').data('etd').toString().toLowerCase()

        let cost = $(this).find(':selected').data('price')
        let subTotal = $('#sub_total_price').data('sub-total-price')
        let grandTotal = cost + subTotal

        $('#etd').text(etd.includes("hari") ? etd : etd + " hari")
        $('#shipping_price').text(cost.toLocaleString('id'))
        $('#grand_total').text(grandTotal.toLocaleString('id'))
    })

    let resetPrice = () => {
        $('#shipping_price').text('-')
        $('#grand_total').text('-')
    }
})
</script>
@endsection