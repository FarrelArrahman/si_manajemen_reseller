@extends('layouts.template')

@section('title')
Pelanggan
@endsection

@section('sub-title')
Daftar pelanggan.
@endsection

@section('action-button')
<a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
<i class="fa fa-plus me-2"></i> Tambah Pelanggan
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
                        <th width="30%">Nama</th>
                        <th width="30%">Umur</th>
                        <th width="30%">Tanggal Beli</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- Basic Tables end -->
<form id="delete-pelanggan" action="" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('js')
<script>
    function deletePelanggan(id) {
        var url = "{{ route('pelanggan.destroy', '') }}/" + id
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
        ajax: "{{ route('pelanggan.index_dt') }}",
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
            {data: 'nama', name: 'nama'},
            {data: 'umur', name: 'umur'},
            {data: 'tanggal_beli', name: 'tanggal_beli'},
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
            title: 'Hapus Pelanggan',
            text: "Apakah Anda ingin menghapus pelanggan ini?",
            footer: "<small class='text-center text-danger'>Pelanggan ini akan terhapus secara permanen.</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                deletePelanggan(id)
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