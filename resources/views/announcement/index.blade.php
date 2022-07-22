@extends('layouts.template')

@section('title')
Pengumuman
@endsection

@section('sub-title')
Daftar pengumuman yang tersedia pada sistem.
@endsection

@section('action-button')
<a href="{{ route('announcement.create') }}" class="btn btn-primary">
<i class="fa fa-plus me-2"></i> Tambah Pengumuman
</a>
@endsection

@section('content')
<!-- Basic Tables start -->
<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table yajra-datatable">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Judul</th>
                            <th>Berlaku dari</th>
                            <th>Sampai dengan</th>
                            <th>Dibuat oleh</th>
                            <th>Status</th>
                            <th>Tampilkan?</th>
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
<form id="delete-announcement" action="" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('js')
<script>
    function deleteAnnouncement(id) {
        var url = "{{ route('announcement.destroy', '') }}/" + id
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function changeAnnouncementStatus(id, status) {
        var url = "{{ route('announcement.changeStatus', 0) }}/".replace("0", id)
        var data = {
            is_private: status
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
        ajax: "{{ route('announcement.index_dt') }}",
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
            {data: 'title', name: 'title'},
            {data: 'start_from', name: 'start_from'},
            {data: 'valid_until', name: 'valid_until'},
            {data: 'created_by', name: 'created_by'},
            {data: 'is_private', name: 'is_private'},
            {data: 'switch_button', name: 'switch_button'},
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
            title: 'Hapus Pengumuman',
            text: "Apakah Anda ingin menghapus pengumuman ini?",
            footer: "<small class='text-center text-danger'>Pengumuman akan dihapus secara permanen.</small>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteAnnouncement(id)
                    .then((json) => {
                        toast(json.success, json.message)
                        table.draw()
                    })
                    .catch(error => error)
            }
        })
    })

    $('.yajra-datatable').on('change', '.switch-button', function() {
        let id = $(this).data('id')
        let status = $(this).is(':checked')

        changeAnnouncementStatus(id, status)
            .then((json) => {
                // toast(json.success, json.message)
                table.draw()
            })
            .catch(error => error)
    })

    $('.filter').on('change', function() {
        table.draw()
    })
</script>
@endsection