@extends('layouts.template')

@section('title')
Daftar Reseller
@endsection

@section('sub-title')
Daftar reseller yang ada pada sistem.
@endsection

@section('action-button')
@endsection

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
                <div class="col-md-4">
                    <small>Status Reseller</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="reseller_status">
                            <option value="">(Semua Status)</option>
                            <option selected value="PENDING">Menunggu Verifikasi</option>
                            <option value="DITOLAK">Ditolak</option>
                            <option value="AKTIF">Aktif</option>
                            <option value="NONAKTIF">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Provinsi</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="province">
                            <option value="">(Semua Provinsi)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Kabupaten/Kota</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="city">
                            <option value="">(Semua Kabupaten/Kota)</option>
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
                            <th>Foto</th>
                            <th>Nama User</th>
                            <th>Nomor Telepon</th>
                            <th>Pemesanan Terakhir</th>
                            <th>Status</th>
                            <th width="20%">Ubah Status</th>
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
<form id="delete-reseller" action="" method="POST">
    @csrf
    @method('DELETE')
</form>

<!--Extra Large Modal -->
<div class="modal fade text-left w-100" id="reseller_detail_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Detail Reseller</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
                <a id="reseller_registration_proof_of_payment" href="#" class="btn btn-sm btn-info"><i class="fa fa-download me-1"></i> Download Bukti Pembelian Pertama</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center" id="spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-12" id="reseller_detail">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="#" class="rounded" width="168" id="photo">
                            </div>
                            <div class="col-md-10">
                                <h4 class="pb-0 mb-0">
                                    <span id="shop_name"></span>
                                    <small class="fw-light" id="reseller_name">(Reseller)</small>
                                </h4>
                                <small id="email">reseller@email.com</small>
                                <span id="status_badge"></span>
                                <br>
                                <div class="icon-block mt-2 mb-0">
                                    <a href="#" class="p-1 social-media-link" id="facebook"><i class="fab fa-facebook text-primary fa-fw"></i></a>
                                    <a href="#" class="p-1 social-media-link" id="twitter"><i class="fab fa-twitter text-info fa-fw"></i></a>
                                    <a href="#" class="p-1 social-media-link" id="tiktok"><i class="fab fa-tiktok text-dark fa-fw"></i></a>
                                    <a href="#" class="p-1 social-media-link" id="instagram"><i class="fab fa-instagram text-danger fa-fw"></i></a>
                                    <a href="#" class="p-1" id="shopee"><i class="fa fa-shopping-bag fa-fw" style="color: #ff6600"></i></a>
                                </div>
                                <div class="col-md-12 pb-0 mb-0">
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Alamat</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <p class="col-form-label" id="shop_address">Alamat</p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Provinsi</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <p class="col-form-label" id="reseller_province">Provinsi</p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Kota</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <p class="col-form-label" id="reseller_city">Kabupaten/Kota</p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Kode Pos</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <p class="col-form-label" id="postal_code">Kode Pos</p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Nomor Telepon</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <p class="col-form-label" id="phone_number">Nomor Telepon</p>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Status Verifikasi</label>
                                        </div>
                                        <div class="col-lg-8 col-8" id="verification_status">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0" id="approval_date">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Tgl. Verif. Diterima</label>
                                        </div>
                                        <div class="col-lg-8 col-8" id="approval_date_label">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0" id="approved_by">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Diverifikasi oleh</label>
                                        </div>
                                        <div class="col-lg-8 col-8" id="approved_by_label">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center pb-0 mb-0 mt-3" id="rejection_reason">
                                        <div class="col-lg-4 col-4">
                                            <label class="col-form-label fw-bold">Alasan Penolakan</label>
                                        </div>
                                        <div class="col-lg-8 col-8">
                                            <textarea class="form-control" id="rejection_reason_input" rows="2" name="rejection_reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <button id="verify_button" type="button" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="verify_button_label">Verifikasi</span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/rajaongkir-shipping-cost.js') }}"></script>
<script>
    $(document).ready(function() {
        let provinceList, cityList, resellerId
        let resellerDetail = $('#reseller_detail')
        let spinner = $('#spinner')
        let rejectionReason = $('#rejection_reason')
        let proofOfPayment = $('#reseller_registration_proof_of_payment')

        // Jquery Datatable
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 1000,
            ajax: {
                url: "{{ route('reseller.index_dt') }}",
                data: function (d) {
                    d.reseller_status = $('#reseller_status').val(),
                    d.province = $('#province').val(),
                    d.city = $('#city').val(),
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
                {data: 'photo', name: 'photo'},
                {data: 'name', name: 'name'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'last_order_date', name: 'last_order_date'},
                {data: 'reseller_status', name: 'reseller_status'},
                {
                    data: 'switch_button', 
                    name: 'switch_button',
                    orderable: false, 
                    searchable: false
                },
            ],
            fnDrawCallback: () => {
                $('.image-popup').magnificPopup({
                    type: 'image'
                    // other options
                })
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
        
            function changeResellerStatus(id, status) {
                var url = "{{ route('reseller.changeStatus', 0) }}/".replace("0", id)
                var data = {
                    reseller_status: status
                }
                
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

        $('.yajra-datatable').on('change', '.switch-button', function() {
            let id = $(this).data('id')
            let status = $(this).is(':checked')
            
            changeResellerStatus(id, status)
                .then((json) => {
                    // toast(json.success, json.message)
                    table.draw()
                })
                .catch(error => error)
        })

        $('.filter').on('change', function() {
            table.draw()
        })

        resellerDetail.hide()
        rejectionReason.hide()
        proofOfPayment.hide()

        provinces().then(json => {
            provinceList = json.rajaongkir.results
            let provinces = `<option value="" selected>(Semua Provinsi)</option>`
            $('#province').empty()
            for(const element of provinceList) {
                provinces += `<option value="${element.province_id}">${element.province}</option>`
            }
            $('#province').append(provinces)
        })

        cities().then(json => {
            cityList = json.rajaongkir.results
        })

        $('#province').on('change', function() {
            $('#city').empty()
            let city = `<option value="" selected>(Semua Kabupaten/Kota)</option>`
            let provinceId = $(this).val()
            let cities = cityList
            
            if(provinceId != null) {
                cities = cityList.filter(obj => obj.province_id == provinceId)
            }
            
            for(const element of cities) {
                city += `<option value="${element.city_id}">${element.type} ${element.city_name}</option>`
            }

            $('#city').append(city)
        })

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

        var resellerDetailModal = document.getElementById('reseller_detail_modal')
        resellerDetailModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            resellerId = button.getAttribute('data-reseller-id')
            $('.social-media-link').hide()

            getResellerDetail(resellerId).then(json => {
                spinner.fadeOut()
                
                let reseller = json.data
                let user = json.data.user

                let p = provinceList.find(obj => obj.province_id == reseller.province)
                let c = cityList.find(obj => obj.city_id == reseller.city)
                
                $('#shop_name').text(reseller.shop_name)
                $('#shop_address').text(reseller.shop_address)
                $('#reseller_name').text(`(${reseller.user.name})`)
                $('#email').text(reseller.user.email)
                $('#reseller_province').text(p.province)
                $('#reseller_city').text(c.type + ' ' + c.city_name)
                $('#phone_number').text(reseller.phone_number)
                $('#postal_code').text(reseller.postal_code)
                $('#reseller_registration_proof_of_payment').attr('href', reseller.reseller_registration_proof_of_payment)
                $('#reseller_registration_proof_of_payment').show()
                $('#photo').attr('src', reseller.user.photo)
                $('#status_badge').html(reseller.status_badge)
                $('#verification_status').html(reseller.verification_status)
                if(reseller.verification_status == "AKTIF") {
                    $('#approval_date').show()
                    $('#approval_date_label').text(reseller.approval_date)
                    $('#approved_by').show()
                    $('#approved_by_label').text(reseller.approved_by.name)
                    $('#verify_button').hide()
                } else {
                    $('#approval_date').hide()
                    $('#approved_by').hide()
                    $('#verify_button').show()
                }

                for(var k in reseller.social_media) {
                    $('#' + k).attr('href', reseller.social_media[k])
                    $('#' + k).show()
                }
                
                resellerDetail.slideDown()
            }).catch(error => error)
        })

        resellerDetailModal.addEventListener('hidden.bs.modal', function (event) {
            resellerDetail.hide()
            proofOfPayment.hide()
            spinner.show()
            rejectionReason.hide()
            $('#verify_button').prop('disabled', false)
            $('#verify_button_label').text("Verifikasi")
        })

        $('#verification_status').on('change', '#reseller_verification_status', function() {
            let status = $(this).val()
            
            if(status == "DITOLAK") {
                rejectionReason.slideDown()
            } else {
                rejectionReason.slideUp()
            }
        })

        let verifyReseller = (resellerId, data) => {
            var url = "{{ route('reseller.verify', 0) }}/".replace("0", resellerId)
            
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

            let verificationStatus = $('#reseller_verification_status')
            let rejectionReason = $('#rejection_reason_input')

            let data = {
                reseller_status: verificationStatus.val(),
            }

            if(verificationStatus.val() == "DITOLAK") {
                data.rejection_reason = rejectionReason.val()
            }

            verifyReseller(resellerId, data).then(json => {
                toast(json.success, json.message)
                rejectionReason.val("")
                table.draw()

                pendingReseller().then(json => {
                    if(json.data.count < 1) {
                        $('#pending_reseller_count').hide()
                        $('#pending_reseller_dots').hide()
                    } else {
                        $('#pending_reseller_count').text(json.data.count)
                        $('#pending_reseller_dots').show()
                    }

                    $('.close').click()
                })
            })
        })
    })
</script>
@endsection