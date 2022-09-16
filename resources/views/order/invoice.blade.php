<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Invoice #{{ $order->code }}</title>
    
    <link rel="stylesheet" href="{{ public_path() . '/css/bootstrap.css' }}" media="all">    
    <link rel="stylesheet" href="{{ public_path() . '/css/app.css' }}" media="all">
    <link rel="shortcut icon" href="{{ public_path() . '/favicon.ico' }}" type="image/x-icon">
    
    <style>
        * {
            color: #000;
        }

        body {
            background-color: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .table-with-border td, .table-with-border th {
            border: 1px solid #000;
        }

        @media print {
            .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
            .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left;               
            }

            .col-sm-12 {
                width: 100%;
            }

            .col-sm-11 {
                width: 91.66666666666666%;
            }

            .col-sm-10 {
                width: 83.33333333333334%;
            }

            .col-sm-9 {
                width: 75%;
            }

            .col-sm-8 {
                width: 66.66666666666666%;
            }

            .col-sm-7 {
                width: 58.333333333333336%;
            }

            .col-sm-6 {
                width: 50%;
            }

            .col-sm-5 {
                width: 41.66666666666667%;
            }

            .col-sm-4 {
                width: 33.33333333333333%;
            }

            .col-sm-3 {
                width: 25%;
            }

            .col-sm-2 {
                width: 16.666666666666664%;
            }

            .col-sm-1 {
                width: 8.333333333333332%;
            }            
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <section class="card">
            <div class="card-body">
                <!-- Invoice Company Details -->
                <div id="invoice-company-details" class="row">
                    <div class="col-md-6 col-sm-6 text-start">
                        <img class="float-start me-2" src="{{ public_path() . '/laudable-logo.png' }}" alt="" width="110px">
                        <h2>LAUDABLE.ME</h2>
                        <ul class="px-0 list-unstyled">
                            <li>{{ $configuration::configName('address') }}</li>
                            <li>{{ $address->from->city_name }} {{ $address->from->postal_code }}, {{ $address->from->province }}</li>
                            <li>Telp: {{ $configuration::configName('customer_service_phone_number') }}</li>
                        </ul>
                    </div>
                </div>
                <!--/ Invoice Company Details -->
                <!-- Invoice Customer Details -->
                <hr>
                <div id="invoice-customer-details" class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <tr>
                                <th colspan="2"><small>Kepada Yth.</small></th>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <ul class="px-0 list-unstyled">
                                        <li class="fw-bold">{{ $order->reseller->user->name }}</li>
                                        <li>Alamat: {{ $order->orderShipping->address }}</li>
                                        <li>{{ $address->to->city_name }} {{ $address->to->postal_code }}, {{ $address->to->province }}</li>
                                        <li>Telp: {{ $order->reseller->phone_number }}</li>
                                    </ul>
                                </td>
                                <td width="50%" align="right">
                                    <ul class="px-0 list-unstyled">
                                        <li class="fw-bold">INVOICE #{{ $order->code }}</li>
                                        <li><span class="text-primary">Tanggal Invoice: </span> {{ $order->date->format('d-M-Y H:i:s') }}</li>
                                        <li><span class="text-primary">Jenis Pembayaran:</span> Transfer</li>
                                        <li>
                                            <span class="text-primary">Status:</span>
                                            {{ $order->orderPayment && $order->orderPayment->isApproved() ? 'LUNAS' : 'PENDING' }}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--/ Invoice Customer Details -->
                <!-- Invoice Items Details -->
                <div id="invoice-items-details" class="pt-2">
                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <table cellpadding="5" class="table table-with-border">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Varian Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga (Rp.)</th>
                                        <th class="text-end">Total (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subTotal = 0 @endphp
                                    @foreach($order->orderDetail as $item)
                                    @php $subTotal += $item->quantity * $item->price @endphp
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->productVariant->product->product_name }} ({{ $item->productVariant->product_variant_name }})</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price, 0, '', '.') }}</td>
                                        <td class="text-end">{{ number_format($item->quantity * $item->price, 0, '', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-7 col-sm-6 text-start text-md-start">
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <h5>Total Pembayaran</h5>
                            <div class="table-responsive">
                                <table cellpadding="5" class="table table-with-border">
                                    <tbody>
                                        <tr>
                                            <td class="text-end">Sub Total</td>
                                            <td class="text-end">Rp. {{ number_format($subTotal, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">Biaya Pengiriman</td>
                                            <td class="text-end">Rp. {{ number_format($order->orderShipping->total_price, 0, '', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-end">Grand Total</td>
                                            <td class="fw-bold text-end">Rp. {{ number_format($subTotal + $order->orderShipping->total_price, 0, '', '.') }}</td>
                                        </tr>
                                        @if($order->orderPayment && $order->orderPayment->isApproved())
                                        <tr>
                                            <td class="fw-bold text-primary">Terbayar</td>
                                            <td class="fw-bold text-end text-primary">Rp. {{ number_format($order->orderPayment->amount, 0, '', '.') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Invoice Footer -->
                <div id="invoice-footer">
                    <div class="row pt-3">
                        <div class="col-md-8 col-sm-8">

                        </div>
                        @if($order->orderPayment && $order->orderPayment->isApproved())
                        <div class="col-md-4 col-sm-4 text-center">
                            <div>
                                <p>Hormat Kami</p>
                                <h6 class="mt-5">{{ $order->admin->name }}</h6>
                                <p class="text-primary">{{ $order->admin->role }} Laudable.me</p>
                            </div>
                        </div>
                        @else
                        <div class="table-responsive">
                            <table cellpadding="5" class="table">
                                <tr>
                                    <td class="text-start">
                                        <b>Note:</b>
                                        <p>
                                            Silakan transfer ke rekening <strong>{{ $configuration::configName('bank_name') }} {{ $configuration::configName('account_number') }}</strong> A.N <strong>{{ $configuration::configName('account_holder_name') }}</strong> sesuai dengan nominal di atas agar pengiriman pesanan Anda dapat diproses.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                <!--/ Invoice Footer -->
            </div>
        </section>
    </div>    
</body>
</html>
