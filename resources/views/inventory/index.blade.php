@extends('layouts.template')

@section('title')
Varian Produk
@endsection

@section('sub-title')
Daftar seluruh varian produk yang tersedia.
@endsection

@section('action-button')
@if((auth()->user()->isReseller() && auth()->user()->reseller && auth()->user()->reseller->isActive()))
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#show_cart_modal">
<i class="fa fa-shopping-cart me-2"></i> Lihat Keranjang
</button>
@endif
@endsection

@section('css')
<!-- jQuery UI CSS -->
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
<!-- Price Range Style CSS -->
<link rel="stylesheet" href="{{ asset('css/price_range_style.css') }}">
@endsection

@if((auth()->user()->isReseller() && auth()->user()->reseller && auth()->user()->reseller->isActive()) || (auth()->user()->isAdmin() || auth()->user()->isStaff()))
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
                    <small>Kategori</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter" id="category_id">
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Produk</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter" id="product_id">
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Harga (Rp.)</small>
                    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                    <input type="number" min="{{ $lowestPrice }}" max="{{ $highestPrice - 5000 }}" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field filter" readonly>
                    <input type="number" min="{{ $lowestPrice }}" max="{{ $highestPrice }}" oninput="validity.valid||(value='100000');" id="max_price" class="price-range-field filter" readonly>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table yajra-datatable">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Warna</th>
                        <th>Stok</th>
                        <th width="20%">Harga (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- Basic Tables end -->
<form id="delete-product" action="" method="POST">
    @csrf
    @method('DELETE')
</form>

<!-- Add to Cart Modal -->
<div class="modal fade text-left w-100" id="add_to_cart" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Tambahkan ke Keranjang</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="col-md-12 text-center" id="not_available">
                        <i class="fa fa-exclamation-circle text-muted fa-3x mb-2"></i>
                        <h4>Produk ini tidak tersedia.</h4>
                        <p>Silahkan pilih produk atau varian lain.</p>
                    </div>
                    <div class="col-md-12" id="product_variant_detail">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <img src="#" class="rounded" id="photo" style="max-width: 100%;">
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <h4 class="pb-0 mb-0">
                                    <span id="shop_name"></span>
                                    <small class="fw-light" id="product_variant_name">Nama Produk</small>
                                </h4>
                                <small>Stok: <span id="stock"></span></small>
                                <span id="status_badge"></span>
                                <div class="col-md-12 pb-0 mb-0">
                                    <div class="form-group row align-items-center pb-0 mb-0">
                                        <div class="col-lg-12 col-12">
                                            <label class="col-form-label fw-bold">Jumlah Pesan</label>
                                            <div class="input-group mb-2">
                                                <button class="btn btn-outline-secondary input-group-text" id="decrease">-</button>
                                                <input type="hidden" id="product_variant_id">
                                                <input type="number" class="form-control" id="qty" value="0" min="0" onfocus="this.select()" onmouseup="return false;">
                                                <button class="btn btn-outline-secondary input-group-text" id="increase">+</button>
                                            </div>
                                            <p class="text-success" id="in-cart-qty-label">Qty Keranjang: <span id="in-cart-qty"></span></p>
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
                <button id="add_to_cart_button" type="button" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block" id="add_to_cart_label">Tambahkan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cart Modal -->
<div class="modal fade text-left w-100" id="show_cart_modal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel16" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Keranjang Saya</h4>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
                <button id="remove_all_item" class="btn btn-sm btn-danger"><i class="fa fa-trash me-1"></i> Kosongkan Keranjang</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="cart_detail">
                        <table class="table table-responsive cart_detail_table" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th class="text-center">Foto</th>
                                    <th>Varian</th>
                                    <th class="text-end">Harga (pcs)</th>
                                    <th width="15%" class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="cart_detail_body">
                                <tr>
                                    <td class="text-center" colspan="5">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Kembali</span>
                </button>
                <a id="order_now" href="{{ route('order.create') }}" class="btn btn-success ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Pesan Sekarang</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@endif

@section('js')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $("#min_price,#max_price").on('change', function () {
            var min_price_range = parseInt($("#min_price").val());
            var max_price_range = parseInt($("#max_price").val());

            if (min_price_range > max_price_range) {
                $('#max_price').val(min_price_range);
            }

            $("#slider-range").slider({
                values: [min_price_range, max_price_range]
            })
        })

        $("#min_price, #max_price").on("paste keyup", function () {                                        
            var min_price_range = parseInt($("#min_price").val());
            var max_price_range = parseInt($("#max_price").val());
        
            if(min_price_range == max_price_range){
                max_price_range = min_price_range + 100;
                $("#min_price").val(min_price_range);		
                $("#max_price").val(max_price_range);
            }

            $("#slider-range").slider({
                values: [min_price_range, max_price_range]
            })
        })

        $(function () {
            let minPrice = parseInt($('#min_price').attr('min'))
            let maxPrice = parseInt($('#max_price').attr('max'))

            $("#slider-range").slider({
                range: true,
                orientation: "horizontal",
                min: minPrice,
                max: maxPrice,
                values: [minPrice, maxPrice],
                step: 5000,

                slide: function (event, ui) {
                    if (ui.values[0] == ui.values[1]) {
                        return false
                    }
                    
                    $("#min_price").val(ui.values[0])
                    $("#max_price").val(ui.values[1])
                },
                stop: function (event, ui) {
                    table.draw()
                }
        })

        $("#min_price").val($("#slider-range").slider("values", 0))
        $("#max_price").val($("#slider-range").slider("values", 1))
        })
    })
</script>
<script>
    let inCartQtyLabel = $('#in-cart-qty-label');
    inCartQtyLabel.hide();
    
    // Jquery Datatable
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: "{{ route('inventory.index_dt') }}",
            data: function (d) {
                d.category_id = $('#category_id').val(),
                d.product_id = $('#product_id').val(),
                d.min_price = $('#min_price').val(),
                d.max_price = $('#max_price').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columnDefs: [
            {
                targets: 0,
                className: 'dt-center',
            },
        ],
        columns: [
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false
            },
            {data: 'photo', name: 'photo'},
            {data: 'product_name', name: 'product_name'},
            {data: 'category', name: 'category'},
            {data: 'color', name: 'color'},
            {data: 'stock', name: 'stock'},
            {data: 'reseller_price', name: 'reseller_price'},
        ],
        fnDrawCallback: () => {
            $('.image-popup').magnificPopup({
                type: 'image'
                // other options
            })
        }
    })

    $(document).ready(function() {
        let productVariantDetail = $('#product_variant_detail')
        let notAvailable = $('#not_available')
        let cartDetail = $('#cart_detail')
        let cartDetailBody = $('#cart_detail_body')
        let spinner = $('.spinner')
        let qtyInput = $('#qty')
        let qtyMin = 0
        let qtyMax = qtyInput.attr('max')

        productVariantDetail.hide()
        notAvailable.hide()
        cartDetail.hide()

        let getCategories = () => {
            let category = $('#category_id')
            let url = "{{ route('category.index_api') }}"
            let html = ""

            fetch(url)
                .then(response => response.json())
                .then(json => {
                    html += '<option value="0">(Semua Kategori)</option>'
                    for(let item of json.data) {
                        html += '<option value="' + item.id + '">' + item.category_name + '</option>'
                    }
                    category.html(html)
                    category.select2({
                        placeholder: 'Pilih kategori...'
                    })
                })       
        }

        let getProducts = (categoryId) => {
            let product = $('#product_id')
            let url = "{{ route('product.index_api') }}?category_id=" + categoryId
            let html = ""

            fetch(url)
                .then(response => response.json())
                .then(json => {
                    html += '<option value="0">(Semua Produk)</option>'
                    for(let item of json.data) {
                        html += '<option value="' + item.id + '">' + item.product_name + '</option>'
                    }
                    product.html(html)
                    product.select2({
                        placeholder: 'Pilih produk...'
                    })
                })       
        }

        getCategories()
        getProducts()

        $('#category_id').on('change', function() {
            getProducts($(this).val())
        })

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

        @if(auth()->user()->isReseller() && auth()->user()->reseller && auth()->user()->reseller->isActive())
        let setCartDetailTable = (cartDetail) => {
            let el = ""
            let qtyError = false

            let cartDetailTableBody = $('#cart_detail_body')
            cartDetailTableBody.empty()

            if(cartDetail != null && cartDetail?.cart_detail.length > 0) {
                for(let item of cartDetail.cart_detail) {
                    if(item.quantity_less_or_equal_than_stock == false) {
                        qtyError = true
                    }

                    totalPrice = item.product_variant.reseller_price * item.quantity
                    el += `<tr>
                        <td class="text-center">
                            <button data-cart-detail-id="${item.id}" class="btn btn-link p-0 text-danger me-1 ms-1 removefromcart-button"><i class="fa fa-trash fa-sm"></i></button>
                        </td>
                        <td class="text-center">
                            <img style="object-fit: cover; width: 64px; height: 64px;" src="${item.product_variant.photo_storage_path}">
                        </td>
                        <td>
                            ${item.product_variant.product.product_name} (${item.product_variant.product_variant_name})
                            <br>
                            ${item.quantity_less_or_equal_than_stock == false ? '<small class="text-danger fw-bold">Stok tidak mencukupi. Stok Tersedia: ' + item.product_variant.stock + '</small>' : ''} 
                        </td>
                        <td class="text-end">
                            ${item.product_variant.reseller_price.toLocaleString('id-ID')}
                        </td>
                        <td class="text-center">
                            <input onfocus="this.select()" onmouseup="return false;" data-cart-detail-id="${item.id}" type="number" class="form-control change-quantity" value="${item.quantity}" min="1" max="${item.product_variant.stock}">
                        </td>
                    </tr>`
                }
            } else {
                el += `<tr>
                    <td class="text-center" colspan="5">Tidak ada produk dalam keranjang.</td>
                </tr>`
            }

            if(qtyError) {
                table.draw()
                $('#order_now').addClass('disabled')
            } else {
                $('#order_now').removeClass('disabled')
            }

            cartDetailTableBody.append(el)
        }

        let getProductVariantDetail = (productVariantId) => {
            var url = "{{ route('product_variant.detail', 'x') }}/".replace("x", productVariantId)
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        let checkQuantity = (productVariantId) => {
            var url = "{{ route('inventory.checkQuantity', 'x') }}/".replace("x", productVariantId)
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }
        
        var addToCartModal = document.getElementById('add_to_cart')
        addToCartModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            productVariantId = button.getAttribute('data-product-variant-id')

            getProductVariantDetail(productVariantId).then(json => {
                if(json.success) {
                    let stock = json.data.stock
                    if(stock < 1) {
                        $('#add_to_cart_button').prop('disabled', true)
                    }
                    $('#product_variant_name').text(`${json.data.product.product_name} (${json.data.product_variant_name})`)
                    $('#stock').text(stock)
                    $('#photo').attr('src', json.data.photo)
                    $('#qty').attr('max', stock)
                    $('#product_variant_id').val(json.data.id)
                    
                    checkQuantity(productVariantId).then(res => {
                        let inCartQty = res.data.quantity
                        if(inCartQty > 0) {
                            $('#qty').attr('max', stock - inCartQty)
                            $('#in-cart-qty').text(inCartQty)
                            inCartQtyLabel.show()
                        } else {
                            inCartQtyLabel.hide()
                        }
                    }).catch(error => error)
                    
                    productVariantDetail.slideDown()
                    $('#add_to_cart_button').show()
                } else {
                    notAvailable.slideDown()
                    $('#add_to_cart_button').hide()
                }
                
                spinner.slideUp()
            }).catch(error => error)
        })

        addToCartModal.addEventListener('hidden.bs.modal', function (event) {
            inCartQtyLabel.hide()
            productVariantDetail.hide()
            notAvailable.hide()
            spinner.show()
            $('#add_to_cart_button').hide()
            $('#add_to_cart_button').prop('disabled', false)
            $('#add_to_cart_label').text("Tambahkan")
            qtyInput.val(1)
        })

        $('#increase').on('click', function() {
            let qty = parseInt(qtyInput.val()) + 1
            let qtyMax = qtyInput.attr('max')

            qtyInput.val(qty > parseInt(qtyMax) ? parseInt(qtyMax) : qty)
        })

        $('#decrease').on('click', function() {
            let qty = qtyInput.val() - 1
            qtyInput.val(qty < 1 ? 1 : qty)
        })

        qtyInput.on('keyup keypress keydown', function() {
            if(parseInt($(this).val()) > parseInt($(this).attr('max'))) {
                $(this).val($(this).attr('max'))
            }

            if($(this).val() == "" || parseInt($(this).val()) < 1) {
                $('#add_to_cart_button').prop('disabled', true)
            } else {
                $('#add_to_cart_button').prop('disabled', false)
            }
        })

        qtyInput.on('keyup', function(event) {
            if(event.keyCode === 13) {
                $('#add_to_cart_button').click()
            }
        })

        let addToCart = (data) => {
            var url = "{{ route('cart.store') }}"

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

        $('#add_to_cart_button').on('click', function() {            
            $(this).attr('disabled', 'disabled')
            $('#add_to_cart_label').text("Loading...")

            let data = {
                product_variant_id: $('#product_variant_id').val(),
                quantity: $('#qty').val(),
            }

            if(data.quantity < 1) {
                $('.close').click()
                return;
            }

            addToCart(data).then(json => {
                toast(json.success, json.message)

                $('.close').click()
            })
        })

        let getCartDetail = () => {
            var url = "{{ route('cart.show') }}"
            return fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        var showCartModal = document.getElementById('show_cart_modal')
        showCartModal.addEventListener('show.bs.modal', function (event) {
            
            getCartDetail().then(json => {
                let cartDetail = json.data
                setCartDetailTable(cartDetail)
            })

            cartDetail.show()
        })

        showCartModal.addEventListener('hidden.bs.modal', function (event) {
            cartDetail.hide()
            $('#order_now').removeClass('disabled')
        })

        let changeQuantity = (cartDetail, quantity) => {
            var url = "{{ route('cart.changeQuantity', 'x') }}".replace('x', cartDetail)

            return fetch(url, {
                method: 'PATCH',
                body: JSON.stringify({
                    quantity: quantity
                }),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        let wto
        $(document).on('change keyup keypress keydown', '.change-quantity', function() {
            clearTimeout(wto)
            let cartDetail = $(this).data('cart-detail-id')
            let quantity = $(this).val()
            
            if(quantity < 1) {
                quantity = 1
            } else if(quantity > parseInt($(this).attr('max'))) {
                quantity = parseInt($(this).attr('max'))
            }

            $(this).val(quantity)
            
            wto = setTimeout(function () {
                changeQuantity(cartDetail, quantity).then(json => {
                    // console.log(json)
                    $('#order_now').removeClass('disabled')
                })
            }, 1000)
        })

        // $(document).on('', '.change-quantity', function() {
        //     if(parseInt($(this).val()) > parseInt($(this).attr('max'))) {
        //         $(this).val($(this).attr('max'))
        //     }

        //     if($(this).val() == "" || parseInt($(this).val()) < 1) {
        //         $('#order_now').addClass('disabled')
        //     } else {
        //         $('#order_now').removeClass('disabled')
        //     }
        // })

        let removeItemFromCart = (cartDetail) => {
            var url = "{{ route('cart.removeCartItem', 'x') }}".replace('x', cartDetail)

            return fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        $(document).on('click', '.removefromcart-button', function() {
            let cartDetail = $(this).data('cart-detail-id')

            removeItemFromCart(cartDetail).then(json => {
                toast(json.success, json.message)
                getCartDetail().then(json => {
                    let cartDetail = json.data
                    setCartDetailTable(cartDetail)
                })
            })
        })

        let removeAll = () => {
            var url = "{{ route('cart.removeAll', 'x') }}"

            return fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-Token': csrfToken
                },
            }).then(response => response.json())
        }

        $(document).on('click', '#remove_all_item', function() {
            removeAll().then(json => {
                toast(json.success, json.message)
                getCartDetail().then(json => {
                    let cartDetail = json.data
                    setCartDetailTable(cartDetail)
                })
            })
        })
        @endif
    })
</script>
@endsection