@extends('layouts.template')

@section('title')
Katalog
@endsection

@section('sub-title')
Katalog yang berisi daftar produk.
@endsection

@section('action-button')
@if(auth()->user()->isAdmin())
<a href="{{ route('product.create') }}" class="btn btn-primary">
<i class="fa fa-plus me-2"></i> Tambah Master Produk
</a>
@endif
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
                @if(auth()->user()->isAdmin())
                <div class="col-md-4">
                    <small>Tampilan Produk Pada Pencarian</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="product_status">
                            <option value="">Semua Produk</option>
                            <option value="1">Hanya Produk Yang Tampil</option>
                            <option value="0">Hanya Produk Yang Tidak Tampil</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Status Produk</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="show">
                            <option value="">Aktif</option>
                            <option value="0">Dihapus Sementara</option>
                            <option value="1">Semua Produk</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <small>Kategori</small>
                    <div class="input-group mb-3">
                        <select class="form-control filter select2" id="category_id">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                <div class="col-md-4">
                    <small>Kategori</small>
                    <div class="input-group mb-3">
                        <select class="form-control filter select2" id="category_id">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table yajra-datatable">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Foto Utama</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        @if(auth()->user()->isAdmin())
                        <th width="20%">Tampilkan produk?</th>
                        @endif
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
<script>
    function deleteProduct(id) {
        var url = "{{ route('product.destroy', '') }}/" + id
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function restoreProduct(id) {
        var url = "{{ route('product.restore', 0) }}/".replace("0", id)
        return fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function changeProductStatus(id, status) {
        var url = "{{ route('product.changeStatus', 0) }}/".replace("0", id)
        var data = {
            product_status: status
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
    
    // Jquery Datatable
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: "{{ route('product.index_dt') }}",
            data: function (d) {
                d.category_id = $('#category_id').val(),
                d.product_status = $('#product_status').val(),
                d.show = $('#show').val(),
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
            {data: 'default_photo', name: 'default_photo'},
            {data: 'product_name', name: 'product_name'},
            {data: 'category', name: 'category'},
            @if(auth()->user()->isAdmin())
            {
                data: 'switch_button', 
                name: 'switch_button',
                orderable: false, 
                searchable: false
            },
            @endif
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

    $('.yajra-datatable').on('click', '.delete-button', function() {
        let id = $(this).data('id')

        Swal.fire({
            title: 'Hapus Produk',
            text: "Apakah Anda ingin menghapus produk ini?",
            footer: "<small>Data yang telah dihapus bersifat sementara sehingga dipulihkan kembali.</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteProduct(id)
                    .then((json) => {
                        toast(json.success, json.message)
                        table.draw()
                    })
                    .catch(error => error)
            }
        })
    })

    $('.yajra-datatable').on('click', '.restore-button', function() {
        let id = $(this).data('id')

        restoreProduct(id)
            .then((json) => {
                toast(json.success, json.message)
                table.draw()
            })
            .catch(error => error)
    })

    $('.yajra-datatable').on('change', '.switch-button', function() {
        let id = $(this).data('id')
        let status = $(this).is(':checked')

        changeProductStatus(id, status)
            .then((json) => {
                // toast(json.success, json.message)
                if($('#product_status').val() != "") {
                    table.draw()
                }
            })
            .catch(error => error)
    })

    $('.filter').on('change', function() {
        table.draw()
    })
</script>
@endsection