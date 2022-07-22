@extends('layouts.template')

@section('title')
Dashboard
@endsection

@section('sub-title')
Selamat datang, <strong>{{ auth()->user()->name }}</strong>
@endsection

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Default Layout</h4>
        </div>
        <div class="card-body">
            <!-- Tes Notifikasi
            <div class="form-group mt-3">
                <label for="message">Judul</label>
                <input type="text" class="form-control" id="message">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" id="send-notification">Kirim</button>
            </div> -->
            <!-- <table class="table yajra-datatable">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Judul</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table> -->
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{ url('js/app.js') }}"></script>
<script>
    $(function() {
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
    })
</script>
@endsection