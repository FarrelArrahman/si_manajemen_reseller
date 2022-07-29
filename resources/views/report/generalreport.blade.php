@extends('layouts.template')

@section('title')
Dashboard
@endsection

@section('sub-title')
@endsection

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Laporan Umum</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <small>Jenis Laporan</small>
                            <div class="input-group mb-3">
                                <select class="form-select filter" id="category_id">
                                    <option selected disabled value="">Pilih Jenis...</option>
                                    <option value="selling_price">Penjualan (Harga)</option>
                                    <option value="selling_quantity">Penjualan (Qty)</option>
                                    <option value="selling_quantity">Penjualan (Qty)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <small>Tanggal</small>
                            <div class="input-group mb-3">
                                <select class="form-select filter" id="product_id">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="line-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Bar Chart</h4>
                </div>
                <div class="card-body">
                    <div id="bar"></div>
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
        var options = {
            chart: {
                type: 'line'
            },
            series: [{
                name: 'sales',
                data: [30,40,35,50,49,60,70,91,125]
            }],
            xaxis: {
                categories: [1991,1992,1993,1994,1995,1996,1997,1998,1999]
            }
        }

        var chart = new ApexCharts(document.querySelector("#line-chart"), options);

        chart.render();
    })
</script>
@endsection