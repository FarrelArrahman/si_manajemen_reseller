@extends('layouts.template')

@section('title')
Data {{ $role }}
@endsection

@section('sub-title')
Daftar {{ $role }} yang terdaftar pada sistem.
@endsection

@section('action-button')
@if($role == "reseller")
<a href="{{ route('reseller.index') }}" class="btn btn-warning">
<i class="fa fa-check-circle me-2"></i> Request Verifikasi Reseller @if($pending_reseller_count > 0)<span class="badge bg-danger">{{ $pending_reseller_count }}</span>@endif
</a>
@endif
<a href="{{ route('user.create', $role) }}" class="btn btn-primary">
<i class="fa fa-plus me-2"></i> Tambah {{ $role }}
</a>
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
                    <small>Status User</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter select2" id="status">
                            <option value="">(Semua Status)</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table yajra-datatable">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th width="20%">Aktifkan User?</th>
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
    function deactivateUser(id) {
        var url = "{{ route('user.destroy', ['role' => $role, 'user' => 0]) }}/".replace("0", id)
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function activateUser(id) {
        var url = "{{ route('user.restore', ['role' => $role, 'user' => 0]) }}/".replace("0", id)
        return fetch(url, {
            method: 'GET',
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
            url: "{{ route('user.index_dt', $role) }}",
            data: function (d) {
                d.status = $('#status').val(),
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
            {data: 'status', name: 'status'},
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

    $('.yajra-datatable').on('change', '.switch-button', function() {
        let id = $(this).data('id')
        let status = $(this).is(':checked')
        
        if( ! status) {
            deactivateUser(id)
        } else {
            activateUser(id)
        }

        table.draw()
    })

    $('.filter').on('change', function() {
        table.draw()
    })
</script>
@endsection