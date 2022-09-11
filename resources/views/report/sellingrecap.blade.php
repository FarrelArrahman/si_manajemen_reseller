@extends('layouts.template')

@section('title')
Laporan Rekap Penjualan
@endsection

@section('sub-title')
Menampilkan laporan rekap penjualan pada periode tertentu.
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="interval_card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 interval_filter" id="date">
                            <small>Rentang Tanggal</small>
                            <form id="sellingRecapExcel" action="{{ route('report.sellingRecapExcel') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" name="start_date" id="start_date" value="{{ date('Y-m-01') }}">
                                <span class="input-group-text" id="basic-addon2">s/d</span>
                                <input type="date" class="form-control" name="end_date" id="end_date" value="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-primary reload_button"><i class="fa fa-sync-alt"></i> Reload</button>
                            </div>
                            </form>
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
            
            <div class="card" id="selling_recap_report_card" style="display: none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <button onclick="$('#sellingRecapExcel').submit()" class="btn btn-primary mb-2 float-end">Download Excel</button>
                            <table class="table table-bordered fs-6" id="selling_recap">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="text-center">No</th>
                                        <th>Tanggal</th>
                                        <th>No. Pemesanan</th>
                                        <th>Reseller</th>
                                        <th>Staf</th>
                                        <th class="text-end">Jumlah Penjualan</th>
                                        <th class="text-end">Biaya Pengiriman</th>
                                        <th class="text-end">Total</th>
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
        let intervalSelect = $('#interval_select')
        let intervalCard = $('#interval_card')
        let sellingRecapReportCard = $('#selling_recap_report_card')
        let loadingCard = $('#loading_card')
        let reloadButton = $('.reload_button')

        intervalSelect.on('change', function() {
            $('#' + $(this).val()).css('display', 'block')
        })

        let generateReport = (url, start, end) => {
            let data = { start, end }

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
            let total_selling_price = total_shipping_price = total_grand_total = 0

            let no = 1
            let el = "";
            let tbody = $(id + ' tbody')
            tbody.empty()

            if(result.data.length < 1) {
                el += `<tr>`
                el += `<td class="text-center" colspan="8">Tidak ada data.</td>`
                el += `</tr>`
            }

            for(const element of result.data) {
                let selling_price = parseInt(element.selling_price)
                let shipping_price = parseInt(element.shipping_price)
                let grand_total = parseInt(element.grand_total)

                total_selling_price += selling_price
                total_shipping_price += shipping_price
                total_grand_total += grand_total

                el += `<tr>`
                el += `<td class="text-center">${no++}</td>`
                el += `<td>${element.date}</td>`
                el += `<td>${element.code}</td>`
                el += `<td>${element.reseller}</td>`
                el += `<td>${element.staff}</td>`
                el += `<td class="text-end">${selling_price.toLocaleString('id-ID')}</td>`
                el += `<td class="text-end">${shipping_price.toLocaleString('id-ID')}</td>`
                el += `<td class="text-end">${grand_total.toLocaleString('id-ID')}</td>`
                el += `</tr>`
            }

            el += `<tr class="fw-bold table-primary">`
            el += `<td colspan="5" class="text-end">Total</td>`
            el += `<td class="text-end">${total_selling_price.toLocaleString('id-ID')}</td>`
            el += `<td class="text-end">${total_shipping_price.toLocaleString('id-ID')}</td>`
            el += `<td class="text-end">${total_grand_total.toLocaleString('id-ID')}</td>`
            el += `</tr>`

            tbody.append(el)
        }

        reloadButton.on('click', function() {
            intervalCard.css('display', 'none')
            loadingCard.css('display', 'block')
            sellingRecapReportCard.css('display', 'none')

            let url = "{{ route('report.sellingRecapReport') }}"

            let start = $('#start_date').val()
            let end = $('#end_date').val()

            generateReport(url, start, end).then(json => {
                console.log(json)
                generateTable('#selling_recap', json.data.table.selling_recap)

                intervalCard.css('display', 'block')
                loadingCard.css('display', 'none')
                sellingRecapReportCard.css('display', 'block')
            })
        })
    })
</script>
@endsection