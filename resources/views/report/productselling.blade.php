@extends('layouts.template')

@section('title')
Laporan Penjualan Produk
@endsection

@section('sub-title')
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="interval_card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6" id="product">
                            <small>Varian Produk</small>
                            <select class="form-select" id="product_select">
                                <option selected disabled value="">Pilih varian produk...</option>
                            </select>
                        </div>
                        <div class="col-6" id="date">
                            <small>Rentang Tanggal</small>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" id="start_date" value="{{ date('Y-m-01') }}">
                                <span class="input-group-text" id="basic-addon2">s/d</span>
                                <input type="date" class="form-control" id="end_date" value="{{ date('Y-m-d') }}">
                                <button class="btn btn-primary reload_button"><i class="fa fa-sync-alt"></i> Reload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" id="loading_card" style="display: none">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="spinner-border text-primary my-3 fs-1" style="width: 4rem; height: 4rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h4>Sedang memproses laporan</h4>
                                <p>Harap tunggu sampai proses pengolahan laporan selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card" id="product_selling_report_card" style="display: none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered fs-6" id="product_selling">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="text-center" width="5%">No</th>
                                        <th width="15%">Tanggal</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center" width="15%">Jumlah Qty</th>
                                        <th class="text-end" width="15%">Total (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{ url('js/app.js') }}"></script>
<script>
    $(function() {
        let productSelect = $('#product_select')
        let intervalCard = $('#interval_card')
        let productSellingReportCard = $('#product_selling_report_card')
        let loadingCard = $('#loading_card')
        let reloadButton = $('.reload_button')
        
        productSelect.select2({
            minimumInputLength: 2,
            placeholder:'Pilih varian produk...',
            ajax: {
                delay: 250,
                url: "{{ route('product_variant.search') }}",
                dataType: 'json',
                data: function(params){
                    var query = {
                        q: params.term
                    }

                    return query
                },
                processResults: function (data) {
                    return {
                        results: data.data
                    }
                }
            },
        })

        let generateReport = (url, product, start, end) => {
            let data = { product, start, end }

            return fetch(url, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        let generateTable = (id, result) => {
            let total_quantity = total_grand_total = 0

            let no = 1
            let el = "";
            let tbody = $(id + ' tbody')
            tbody.empty()

            if(result.data.length < 1) {
                el += `<tr>`
                el += `<td class="text-center" colspan="5">Tidak ada data.</td>`
                el += `</tr>`
            }

            for(const element of result.data) {
                let quantity = parseInt(element.quantity)
                let grand_total = parseInt(element.grand_total)

                total_quantity += quantity
                total_grand_total += grand_total

                el += `<tr>`
                el += `<td class="text-center">${no++}</td>`
                el += `<td>${element.date}</td>`
                el += `<td>${element.product_name} (${element.product_variant_name})</td>`
                el += `<td class="text-center">${quantity.toLocaleString('id-ID')}</td>`
                el += `<td class="text-end">${grand_total.toLocaleString('id-ID')}</td>`
                el += `</tr>`
            }

            el += `<tr class="fw-bold table-primary">`
            el += `<td colspan="3" class="text-end">Total</td>`
            el += `<td class="text-center">${total_quantity.toLocaleString('id-ID')}</td>`
            el += `<td class="text-end">${total_grand_total.toLocaleString('id-ID')}</td>`
            el += `</tr>`

            tbody.append(el)
        }

        reloadButton.on('click', function() {
            intervalCard.css('display', 'none')
            loadingCard.css('display', 'block')
            productSellingReportCard.css('display', 'none')

            let url = "{{ route('report.productSellingReport') }}"

            let product = productSelect.val()
            let start = $('#start_date').val()
            let end = $('#end_date').val()

            generateReport(url, product, start, end).then(json => {
                console.log(json)
                generateTable('#product_selling', json.data.table.product_selling)

                intervalCard.css('display', 'block')
                loadingCard.css('display', 'none')
                productSellingReportCard.css('display', 'block')
            })
        })
    })
</script>
@endsection