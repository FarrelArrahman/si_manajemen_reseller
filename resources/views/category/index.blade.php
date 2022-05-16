@extends('layouts.template')

@section('title')
Kategori
@endsection

@section('sub-title')
Daftar kategori produk.
@endsection

@section('action-button')
<a href="{{ route('category.create') }}" class="btn btn-primary">
<i class="fa fa-plus me-2"></i> Tambah Kategori
</a>
@endsection

@section('content')
<!-- Basic Tables start -->
<section class="section">
    <div class="card">
        <div class="card-body">
            <table class="table yajra-datatable">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="30%">Kategori</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- Basic Tables end -->
<form id="delete-category" action="" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('js')
<script>
    function deleteCategory(id) {
        var url = "{{ route('category.destroy', '') }}/" + id
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
        ajax: "{{ route('category.index_dt') }}",
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
            {data: 'category_name', name: 'category_name'},
            {data: 'description', name: 'description'},
        ]
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
            title: 'Hapus Kategori',
            text: "Apakah Anda ingin menghapus kategori ini?",
            footer: "<small class='text-center text-danger'>Produk dengan kategori ini akan otomatis diubah menjadi kategori default (Tanpa Kategori).</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteCategory(id)
                    .then((json) => {
                        toast(json.success, json.message)
                        table.draw()
                    })
                    .catch(error => error)
            }
        })
    })

    $('.filter').on('change', function() {
        table.draw()
    })
</script>
@endsection