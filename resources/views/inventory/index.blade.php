@extends('layouts.template')

@section('title')
Inventori
@endsection

@section('sub-title')
Daftar inventori produk yang tersedia.
@endsection

@section('css')
<!-- jQuery UI CSS -->
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
<!-- Price Range Style CSS -->
<link rel="stylesheet" href="{{ asset('css/price_range_style.css') }}">
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
                    <small>Kategori</small>
                    <div class="input-group mb-3">
                        <select class="form-control filter" id="category_id">
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
                    <input type="number" min=0 max="95000" oninput="validity.valid||(value='0');" id="min_price" class="price-range-field" />
                    <input type="number" min=0 max="100000" oninput="validity.valid||(value='100000');" id="max_price" class="price-range-field" />
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
@endsection

@section('js')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
<script src="{{ asset('js/price_range_script.js') }}"></script>

<script>
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

    $(document).ready(function() {
        $('#category_id').on('change', function() {
            getProducts($(this).val())
        })
    })
    
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
</script>
@endsection