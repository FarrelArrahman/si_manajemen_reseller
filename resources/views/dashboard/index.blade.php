@extends('layouts.template')

@section('title')
Dashboard
@endsection

@section('sub-title')
@endsection

@section('content')
<section class="section">
    <div class="row">
        
        @if(auth()->user()->isAdmin())
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="fa fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Reseller</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats->reseller }} reseller</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="fa fa-tag text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Varian Produk</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats->product_variant }} varian</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="fa fa-shopping-cart text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Pesanan</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats->order }} pesanan</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="fa fa-user text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Staf</h6>
                            <h6 class="font-extrabold mb-0">{{ $stats->staff }} staf</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <i class="fas fa-home text-muted fa-4x my-3"></i>
                                <h4>Selamat datang di Website Reseller Laudable.me!</h4>
                                @if(auth()->user()->isAdmin())
                                <p>Silakan pilih menu di sebelah kiri untuk berpindah ke halaman lain.</p>
                                @else
                                <p>Silakan pilih menu di sebelah kiri untuk berpindah ke halaman lain. Jika ada pertanyaan, keluhan atau saran, klik tombol di bawah ini untuk menghubungi Customer Service via WhatsApp.</p>
                                <a target="_blank" href="http://wa.me/{{ $customerServiceWhatsapp }}" class="btn btn-success"><i class="fab fa-whatsapp me-1"></i> Hubungi CS via Whatsapp</a>
                                @endif
                            </div>
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