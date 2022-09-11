@extends('layouts.template')

@section('title')
Laporan Umum
@endsection

@section('sub-title')
Menampilkan laporan penjualan berdasarkan harga dan kuantitas.
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="interval_card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <small>Interval</small>
                            <div class="input-group mb-3">
                                <select class="form-select filter" id="interval_select">
                                    <option selected disabled value="">Pilih interval laporan...</option>
                                    <option value="date">Tanggal</option>
                                    <option value="month">Bulanan</option>
                                    <option value="year">Tahunan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12 interval_filter" id="date" style="display: none">
                            <small>Rentang Tanggal</small>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control" id="start_date" value="{{ date('Y-m-01') }}">
                                <span class="input-group-text" id="basic-addon2">s/d</span>
                                <input type="date" class="form-control" id="end_date" value="{{ date('Y-m-d') }}">
                                <button class="btn btn-primary reload_button"><i class="fa fa-sync-alt"></i> Reload</button>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12 interval_filter" id="month" style="display: none">
                            <small>Rentang Bulan</small>
                            <div class="input-group mb-3">
                                <input type="month" class="form-control" id="start_month" value="{{ date('Y-m') }}">
                                <span class="input-group-text" id="basic-addon2">s/d</span>
                                <input type="month" class="form-control" id="end_month" value="{{ date('Y-m') }}">
                                <button class="btn btn-primary reload_button"><i class="fa fa-sync-alt"></i> Reload</button>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12 interval_filter" id="year" style="display: none">
                            <small>Rentang Tahun</small>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="start_year" value="{{ date('Y') }}">
                                <span class="input-group-text" id="basic-addon2">s/d</span>
                                <input type="number" class="form-control" id="end_year" value="{{ date('Y') }}">
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
            
            <div class="card" id="general_report_card" style="display: none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h5 class="mt-2">Total Penjualan (Harga)</h5>
                            <div id="selling_price"></div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h5 class="mt-2">Total Penjualan (Qty)</h5>
                            <div id="selling_quantity"></div>
                        </div>
                        <div class="col-md-6 col-sm-12 mt-3">
                            <h5 class="mt-2">Produk Terjual Terbanyak (Harga)</h5>
                            <table class="table table-bordered table-striped" id="top_selling_price">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Varian</th>
                                        <th class="text-end">Total Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-12 mt-3">
                            <h5 class="mt-2">Produk Terjual Terbanyak (Qty)</h5>
                            <table class="table table-bordered table-striped" id="top_selling_quantity">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Varian</th>
                                        <th class="text-end">Total Qty (pcs)</th>
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
        let generalReportCard = $('#general_report_card')
        let loadingCard = $('#loading_card')

        let intervalFilter = $('.interval_filter')
        let reloadButton = $('.reload_button')

        let generateChart = (id, seriesName, chartType, xaxisType, colors) => {
            var options = {
                colors: colors,
                chart: {
                    height: 280,
                    type: chartType
                },
                dataLabels: {
                    enabled: false
                },
                series: [
                    {
                        name: seriesName,
                        data: []
                    }
                ],
                xaxis: {
                    type: xaxisType,
                }
            }

            var chart = new ApexCharts(document.querySelector(id), options)
            return chart
        }

        let sellingPriceChart = generateChart("#selling_price", "Penjualan (Harga)", "area", "datetime", ['#2E93fA'])
        sellingPriceChart.render()

        let sellingQuantityChart = generateChart("#selling_quantity", "Penjualan (Quantity)", "area", "datetime", ['#66DA26'])
        sellingQuantityChart.render()

        intervalSelect.on('change', function() {
            intervalFilter.css('display', 'none')
            $('#' + $(this).val()).css('display', 'block')
        })

        let generateReport = (url, interval, start, end) => {
            let data = { interval, start, end }

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
            let no = 1
            let el = "";
            let tbody = $(id + ' tbody')
            tbody.empty()

            if(result.data.length < 1) {
                el += `<tr>`
                el += `<td class="text-center" colspan="3">Tidak ada data.</td>`
                el += `</tr>`
            }

            for(const element of result.data) {
                el += `<tr>`
                el += `<td class="text-center">${no++}</td>`
                el += `<td>${element.product_name} (${element.product_variant_name})</td>`
                el += `<td class="text-end">${parseInt(element.total).toLocaleString('id-ID')}</td>`
                el += `</tr>`
            }

            tbody.append(el)
        }

        reloadButton.on('click', function() {
            intervalCard.css('display', 'none')
            loadingCard.css('display', 'block')
            generalReportCard.css('display', 'none')

            let url = "{{ route('report.generalSellingReport') }}"

            let interval = intervalSelect.val()
            let start = $('#start_' + interval).val()
            let end = $('#end_' + interval).val()

            generateReport(url, interval, start, end).then(json => {
                sellingPriceChart.updateSeries(json.data.chart.selling_price)
                sellingQuantityChart.updateSeries(json.data.chart.selling_quantity)
                
                let options = {}
                if(json.data.type != "datetime") {
                    options.xaxis = {
                        categories: json.data.categories,
                        tickPlacement: 'between'
                    }
                } else {
                    options.xaxis = {
                        type: json.data.type,
                    }
                }

                sellingPriceChart.updateOptions(options)
                sellingQuantityChart.updateOptions(options)

                generateTable('#top_selling_price', json.data.table.top_selling_price)
                generateTable('#top_selling_quantity', json.data.table.top_selling_quantity)

                intervalCard.css('display', 'block')
                loadingCard.css('display', 'none')
                generalReportCard.css('display', 'block')
            })
        })
    })
</script>
@endsection