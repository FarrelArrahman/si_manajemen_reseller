@extends('layouts.template')

@section('title')
Pesanan
@endsection

@section('sub-title')
Daftar pesanan produk dari reseller.
@endsection

@section('action-button')
@endsection

@if((auth()->user()->isReseller() && auth()->user()->reseller && auth()->user()->reseller->isActive()) || auth()->user()->isAdmin())
@section('content')
<!-- Basic Tables start -->
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"></h4>
            <div class="row">
                <div class="col-md-12">
                    <h6 class="mb-1">Filter Data</h6>
                </div>
                <div class="col-md-6">
                    <small>Metode Pemesanan</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="order_type_id">
                            <option selected value="">(Semua Metode)</option>
                            @foreach($orderType as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <small>Status Pesanan</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="status">
                            <option value="">(Semua Status)</option>
                            <option selected value="PENDING">Menunggu Persetujuan</option>
                            <option value="DITOLAK">Ditolak</option>
                            <option value="DITERIMA">Diterima</option>
                            <option value="DIBATALKAN">Dibatalkan</option>
                            <option value="SELESAI">Telah Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table yajra-datatable">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Kode Pesanan</th>
                            <th>Tanggal Pesan</th>
                            <th>Metode Pemesanan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
<!-- Basic Tables end -->

<!--Extra Large Modal -->
<div class="modal fade text-left w-100" id="order_detail_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Detail Order</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center" id="spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="order_detail">
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">No. Order</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="order_code"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Dipesan oleh</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="ordered_by"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Tanggal</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="date"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Jenis Pemesanan</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="order_type"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0" id="expedition">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Kurir / Layanan</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label">
                                    <span id="courier"></span> / 
                                    <span id="service"></span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Alamat</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="shop_address"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Status</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="order_status"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center" id="shopee">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Link Pesanan (Shopee)</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <input @if(auth()->user()->isReseller()) readonly @endif type="url" id="link" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row align-items-center py-2 my-2" id="admin_notes">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Alasan Penolakan</label>
                            </div>
                            <div class="col-lg-8 col-8">
                            <textarea class="form-control" id="admin_notes_input" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 pb-0 mb-0">
                            <table class="table table-bordered mt-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th width="40%">Varian</th>
                                        <th class="text-end" width="25%">Harga per pcs (Rp.)</th>
                                        <th class="text-center" width="5%">Jumlah</th>
                                        <th class="text-end" width="25%">Total Harga (Rp.)</th>
                                    </tr>
                                </thead>
                                <tbody id="order_detail_table_tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                            
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Kembali</span>
                </button>
                @if(auth()->user()->isAdmin())
                <button id="verify_button" type="button" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="verify_button_label">Simpan</span>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@endif

@section('js')
<script src="{{ asset('js/rajaongkir-shipping-cost.js') }}"></script>
<script type="text/javascript">
    let provinceList, cityList, orderTypeId
    let orderDetail = $('#order_detail')
    let spinner = $('#spinner')
    let shopee = $('#shopee')
    let expedition = $('#expedition')
    let adminNotes = $('#admin_notes')

    orderDetail.hide()
    shopee.hide()
    adminNotes.hide()

    provinces().then(json => {
        provinceList = json.rajaongkir.results
    })

    cities().then(json => {
        cityList = json.rajaongkir.results
    })

    let deleteOrder = (id) => {
        var url = "{{ route('order.destroy', 'x') }}/".replace('x', id)
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    // Jquery Datatable
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: "{{ route('order.index_dt') }}",
            data: function (d) {
                d.status = $('#status').val(),
                d.order_type_id = $('#order_type_id').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columnDefs: [
            {
                targets: 0,
                className: 'text-center'
            }
        ],
        columns: [
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
            {data: 'code', name: 'code'},
            {data: 'date', name: 'date'},
            {data: 'order_type', name: 'order_type'},
            {data: 'status', name: 'status'},
        ],
        fnDrawCallback: () => {

        }
    });

    var dTable = $('.yajra-datatable').dataTable().api()

    $('.dataTables_filter input')
        .unbind()
        .bind("input", function(e) {
            if(this.value.length >= 3 || e.keyCode == 13) {
                dTable.search(this.value).draw()
            }

            if(this.value == "") {
                dTable.search("").draw()
            }
            return
        })

    $('.filter').on('change', function() {
        table.draw()
    })

    $('.yajra-datatable').on('click', '.deleteorder-button', function() {
        let id = $(this).data('order-id')

        Swal.fire({
            title: 'Batalkan Pesanan',
            text: "Apakah Anda ingin membatalkan pesanan ini?",
            footer: "<small>Seluruh barang pesanan yang dibatalkan akan terhapus.</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteOrder(id)
                    .then((json) => {
                        toast(json.success, json.message)
                        table.draw()
                    })
                    .catch(error => error)
            }
        })
    })

    let getOrderDetail = (orderId) => {
        var url = "{{ route('order.detail', 'x') }}/".replace("x", orderId)
        return fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    let setOrderDetailTable = (orderDetailItems, shippingCost) => {
        let no = 1
        let el = ""
        let subTotal = 0
        let grandTotal = 0

        let orderDetailTableBody = $('#order_detail_table_tbody')
        orderDetailTableBody.empty()

        for(let item of orderDetailItems) {
            totalPrice = item.product_variant.reseller_price * item.quantity
            el += `<tr>
                <td class="text-center">${no++}</td>
                <td>${item.product_variant.product.product_name} (${item.product_variant.product_variant_name})</td>
                <td class="text-end">${item.product_variant.reseller_price.toLocaleString('id-ID')}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end">${totalPrice.toLocaleString('id-ID')}</td>
            </tr>`
            subTotal += totalPrice
        }

        grandTotal = subTotal + shippingCost

        el += `<tr>
            <th class="text-end" colspan="4">Sub Total</th>
            <th id="sub_total_price" class="text-end">${subTotal.toLocaleString('id-ID')}</th>
        </tr>`

        if(shippingCost > 0) {
            el += `<tr id="shipping_price_row" style="display: none">
                <th class="text-end" colspan="4">Biaya Pengiriman</th>
                <th id="shipping_price" class="text-end">${shippingCost.toLocaleString('id-ID')}</th>
            </tr>
            <tr id="grand_total_row" style="display: none">
                <th class="text-end" colspan="4">Grand Total</th>
                <th id="grand_total" class="text-end">${grandTotal.toLocaleString('id-ID')}</th>
            </tr>`
        }

        orderDetailTableBody.append(el)
    }

    var orderDetailModal = document.getElementById('order_detail_modal')
    orderDetailModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        orderId = button.getAttribute('data-order-id')

        getOrderDetail(orderId).then(json => {
            let order = json.data
            let reseller = json.data.reseller
            let orderDetailItems = json.data.order_detail
            let orderType = json.data.order_type
            let orderShipping = json.data.order_shipping
            let externalOrderLink = json.data.external_order_link

            let p = provinceList.find(obj => obj.province_id == reseller.province)
            let c = cityList.find(obj => obj.city_id == reseller.city)
            
            $('#shop_name').text(reseller.shop_name)
            $('#shop_address').text(`${reseller.shop_address}, ${c.city_name}, ${p.province} ${reseller.postal_code}`)
            $('#order_code').text(`${order.code}`)
            $('#order_type').html(`${orderType.status_badge}`)
            $('#ordered_by').text(`${reseller.shop_name}`)
            $('#date').text(`${order.date_formatted}`)
            $('#order_status').html(order.status_badge)
            $('#verification_status').html(order.verification_status)
            $('#link').val(order.external_order_link ? order.external_order_link.link : "")

            if(order.status == "DITERIMA") {
                $('#handled_by').show()
                $('#handled_by_label').text(order.handled_by.name)
                $('#verify_button').hide()
            } else {
                $('#approval_date').hide()
                $('#handled_by').hide()
                $('#verify_button').show()
            }

            orderTypeId = order.order_type_id
            if(orderTypeId == 1) {
                @if(auth()->user()->isReseller())
                if(order.status == "PENDING") {
                    shopee.hide()
                } else {
                    shopee.show()
                }
                @else
                shopee.show()
                @endif
                
                expedition.hide()
            } else if(orderTypeId == 2) {
                $('#courier').text(order.order_shipping.courier.name)
                $('#service').text(order.order_shipping.service)
                shopee.hide()
                expedition.show()
            }

            setOrderDetailTable(orderDetailItems, orderShipping != null ? orderShipping.total_price : 0)

            spinner.fadeOut().slideUp()
            orderDetail.slideDown()
        }).catch(error => error)
    })

    orderDetailModal.addEventListener('hidden.bs.modal', function (event) {
        orderDetail.hide()
        spinner.show()
        adminNotes.hide()
        $('#link').val("")
        $('#verify_button').prop('disabled', false)
        $('#verify_button_label').text("Simpan")
    })

    $('#order_status').on('change', '#order_verification_status', function() {
        let status = $(this).val()
        
        if(status == "DITOLAK") {
            shopee.slideUp()
            adminNotes.slideDown()
        } else {
            if(orderTypeId == 1) {
                shopee.slideDown()
            }
            adminNotes.slideUp()
        }
    })

    let verifyOrder = (orderId, data) => {
        var url = "{{ route('order.verify', 0) }}/".replace("0", orderId)
        
        return fetch(url, {
            method: 'PATCH',
            body: JSON.stringify(data),
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    $('#verify_button').on('click', function() {
        $(this).attr('disabled', 'disabled')
        $('#verify_button_label').text("Loading...")

        let verificationStatus = $('#order_verification_status')
        let adminNotesInput = $('#admin_notes_input')
        let link = $('#link')

        let data = {
            status: verificationStatus.val(),
        }

        if(verificationStatus.val() == "DITOLAK") {
            data.admin_notes = adminNotesInput.val()
        }

        if(orderTypeId == 1) {
            data.link = link.val()
        }

        verifyOrder(orderId, data).then(json => {
            toast(json.success, json.message)
            adminNotesInput.val("")
            table.draw()

            pendingOrder().then(json => {
                if(json.data.count < 1) {
                    $('#pending_order_count').hide()
                } else {
                    $('#pending_order_count').text(json.data.count)
                }

                $('.close').click()
            })
        })
    })
</script>
@endsection