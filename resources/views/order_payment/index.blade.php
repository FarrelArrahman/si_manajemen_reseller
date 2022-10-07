@extends('layouts.template')

@section('title')
Pembayaran
@endsection

@section('sub-title')
Daftar pembayaran dari pesanan reseller.
@endsection

@section('action-button')
@endsection

@if((auth()->user()->isReseller() && auth()->user()->reseller && auth()->user()->reseller->isActive()) || auth()->user()->isAdmin() || auth()->user()->isStaff())
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
                    <small>Status Pembayaran</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="status">
                            <option selected value="">(Semua Status)</option>
                            <option value="BELUM">Belum Dibayar</option>
                            <option value="PENDING">Menunggu Persetujuan</option>
                            <option value="DITOLAK">Ditolak</option>
                            <option value="DITERIMA">Diterima</option>
                        </select>
                    </div>
                </div>
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                <div class="col-md-6">
                    <small>Tanggal Pesan</small>
                    <div class="input-group mb-1">
                        <input type="date" class="form-control filter" id="begin_date" value="{{ date('Y-m-d') }}">
                        <span class="input-group-text" id="basic-addon2">s/d</span>
                        <input type="date" class="form-control filter" id="end_date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="input-group">
                        <div class="form-check">
                            <input class="form-check-input me-2 filter" type="checkbox" id="show_all">
                            <label class="form-check-label" for="show_all">
                                Tampilkan semua tanggal
                            </label>
                        </div>
                    </div>
                </div>
                @endif
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
                            <th>Tanggal Bayar</th>
                            <th>Total Harga (Rp.)</th>
                            <th>Status Bayar</th>
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
<div class="modal fade text-left w-100" id="order_payment_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Verifikasi Pembayaran</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="order_detail">
                        <div class="form-group row align-items-center pb-0 mb-0">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">No. Order</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="order_code"></p>
                            </div>
                        </div>
                        <div class="form-group row align-items-center pb-0 mb-0" id="total_price">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Total Pembayaran</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label" id="total_price_text"></p>
                            </div>
                            @if(auth()->user()->isReseller())
                            <p class="text-success">Silakan transfer ke rekening <strong>{{ $configuration::configName('bank_name') }} {{ $configuration::configName('account_number') }} (A.N {{ $configuration::configName('account_holder_name') }})</strong> sesuai dengan nominal di atas.</p>
                            @endif
                        </div>
                        @if(auth()->user()->isReseller())
                        <div class="form-group row align-items-center pb-0 mb-0" id="total_price">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Rekening Saya</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label text-primary fw-bold">{{ auth()->user()->reseller->bank_name }} {{ auth()->user()->reseller->account_number }} (A.N {{ auth()->user()->reseller->account_holder_name }})</p>
                            </div>
                        </div>
                        @else
                        <div class="form-group row align-items-center pb-0 mb-0" id="total_price">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Rekening Reseller</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <p class="col-form-label text-primary fw-bold" id="reseller_account"></p>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row align-items-center pb-0 mb-0" id="proof_of_payment">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Bukti Pembayaran</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <a href="" class="btn btn-sm btn-success" id="download_button" target="_blank">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <div class="form-group row align-items-center pb-0 mb-0" id="verification_status">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Status</label>
                            </div>
                            <div class="col-lg-8 col-8" id="payment_status">
                                <select class='form-select' id='payment_verification_status'>";
                                    <option value="DITERIMA" class="form-control">TERIMA</option>";
                                    <option value="DITOLAK" class="form-control">TOLAK</option>";
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row align-items-center py-2 my-2" id="admin_notes">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Alasan Penolakan</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                <textarea class="form-control" id="admin_notes_input" rows="2"></textarea>
                                @else
                                <p class="col-form-label" id="admin_notes_text"></p>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->isReseller())
                        <div class="form-group row align-items-center pb-0 mb-0" id="upload_proof_of_payment">
                            <div class="col-lg-4 col-4">
                                <label class="col-form-label fw-bold">Upload Bukti Pembayaran</label>
                            </div>
                            <div class="col-lg-8 col-8">
                                <input class="form-control" type="file" id="proof_of_payment_input">
                            </div>
                        </div>
                        @endif                            
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Kembali</span>
                </button>
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                <button id="verify_button" type="button" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="verify_button_label">Simpan</span>
                </button>
                @else
                <button id="upload_payment" type="button" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="upload_payment_label">Upload</span>
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
    let orderId = 0
    let adminNotes = $('#admin_notes')
    let adminNotesInput = $('#admin_notes_input')
    let adminNotesText = $('#admin_notes_text')

    adminNotes.hide()

    // Jquery Datatable
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: "{{ route('order_payment.index_dt') }}",
            data: function (d) {
                d.status = $('#status').val(),
                d.begin_date = $('#begin_date').val(),
                d.end_date = $('#end_date').val(),
                d.show_all = $('#show_all').is(':checked') ? "yes" : "no",
                d.search = $('input[type="search"]').val()
            }
        },
        columnDefs: [
            {
                targets: 0,
                className: 'text-center'
            },
            {
                targets: 4,
                className: 'text-end'
            },
            {
                targets: 5,
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
            {data: 'payment_date', name: 'payment_date'},
            {data: 'total_price', name: 'total_price'},
            {data: 'payment_status', name: 'payment_status'},
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

    $('#show_all').on('change', function() {
        let isChecked = $(this).is(':checked')
        $('#begin_date').prop('disabled', isChecked)
        $('#end_date').prop('disabled', isChecked)
    })

    var orderPaymentModal = document.getElementById('order_payment_modal')
    orderPaymentModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        orderId = button.getAttribute('data-order-id')
        orderCode = button.getAttribute('data-order-code')
        proofOfPayment = button.getAttribute('data-proof-of-payment')
        paymentStatus = button.getAttribute('data-payment-status')
        totalPrice = button.getAttribute('data-total-price')
        resellerAccount = button.getAttribute('data-reseller-account')
        adminNotesInfo = button.getAttribute('data-admin-notes')

        $('#order_code').text(orderCode)
        $('#total_price_text').text(totalPrice)
        $('#reseller_account').text(resellerAccount)

        if(proofOfPayment != "") {
            $('#proof_of_payment').show()
            $('#download_button').attr('href', proofOfPayment)
        } else {
            $('#proof_of_payment').hide()
            $('#download_button').attr('href', '#')
        }

        if(paymentStatus == "DITERIMA") {
            adminNotes.hide()
            $("#verification_status").hide()
            $("#upload_proof_of_payment").hide()
            $("#verify_button").hide()
            $("#upload_payment").hide()
        } else {
            if(paymentStatus == "DITOLAK") {
                adminNotes.show()
                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                adminNotesInput.val(adminNotesInfo)
                @else
                adminNotesText.text(adminNotesInfo)
                @endif
            }

            $("#verification_status").show()
            $("#upload_proof_of_payment").show()
            $("#verify_button").show()
            $("#upload_payment").show()
        }
    })

    orderPaymentModal.addEventListener('hidden.bs.modal', function (event) {
        $('#payment_verification_status').val('DITERIMA')
        $('#verify_button').prop('disabled', false)
        $('#verify_button_label').text("Simpan")
        $('#upload_payment').prop('disabled', false)
        $('#upload_payment_label').text("Upload")
        adminNotes.slideUp()
    })

    $('#payment_status').on('change', '#payment_verification_status', function() {
        let status = $(this).val()
        
        if(status == "DITOLAK") {
            adminNotes.slideDown()
        } else {
            adminNotes.slideUp()
        }
    })

    let verifyPayment = (orderId, data) => {
        var url = "{{ route('order_payment.verify', 0) }}/".replace("0", orderId)
        
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

        let verificationStatus = $('#payment_verification_status')
        
        let data = {
            status: verificationStatus.val(),
        }

        if(verificationStatus.val() == "DITOLAK") {
            data.admin_notes = adminNotesInput.val()
        }

        verifyPayment(orderId, data).then(json => {
            // console.log(data)
            toast(json.success, json.message)
            adminNotesInput.val("")
            table.draw()

            pendingPayment().then(json => {
                if(json.data.count < 1) {
                    $('#pending_payment_count').hide()
                } else {
                    $('#pending_payment_count').text(json.data.count)
                }

                $('.close').click()
            })
        })
    })

    let uploadPayment = (orderId, file) => {
        var url = "{{ route('order_payment.upload', 0) }}/".replace("0", orderId)
        var data = new FormData()
        data.append('file', file)

        return fetch(url, {
            method: 'POST',
            body: data,
            headers: {
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    $('#upload_payment').on('click', function() {
        $(this).attr('disabled', 'disabled')
        $('#upload_payment_label').text("Loading...")

        let file = $('#proof_of_payment_input').prop('files')[0]
        // console.log(file)

        uploadPayment(orderId, file).then(json => {
            // console.log(json)
            toast(json.success, json.message)
            $('.close').click()
            table.draw()
        })
    })
</script>
@endsection