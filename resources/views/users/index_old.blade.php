@extends('layouts.template')

@section('title')
Master Produk
@endsection

@section('sub-title')
Daftar master produk yang tersedia pada sistem.
@endsection

@section('action-button')
<form id="create-article">
<div class="modal fade" id="create_article" tabindex="-1" aria-labelledby="createArticleTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="createArticleTitle">Tambah Master Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="article_name">Nama Produk</label>
                            <input type="text" id="article_name" class="form-control"
                                name="article_name" value="">
                            <span class="text-danger error-text article_name_error"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="unit_id">Satuan / Unit</label>
                            <select id="unit_id" style="width: 100%" name="unit_id" class="form-control @error('unit') is-invalid @enderror select2 w-100">
                                @foreach($units as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text unit_error"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="default_photo">Foto Utama Produk</label>
                            <br>
                            <input type="file" class="form-control mt-2" name="default_photo" id="default_photo">
                            <span class="text-danger error-text default_photo_error"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3" name="description"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class='form-check form-switch'>
                            <div class="checkbox">
                                <input type="checkbox" id="create_article_status" class='form-check-input' name="article_status">
                                <label for="checkbox3">Tampilkan produk?</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
</form>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_article">
<i class="fa fa-plus me-2"></i> Tambah Master Produk
</button>

<!-- Edit Article Modal -->
<form id="edit-article">
<div class="modal fade" id="edit_article" tabindex="-1" aria-labelledby="editArticleTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title white" id="editArticleTitle">Ubah Master Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_article_name">Nama Produk</label>
                                <input type="text" id="edit_article_name" class="form-control"
                                    name="article_name" value="">
                                <span class="text-danger error-text article_name_error"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_unit_id">Satuan / Unit</label>
                                <select id="edit_unit_id" style="width: 100%" name="unit_id" class="form-control @error('unit') is-invalid @enderror select2 w-100">
                                    @foreach($units as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text unit_error"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_default_photo">Foto Utama Produk</label>
                                <br>
                                <a id="default_photo_url" href=""><img id="default_photo_image" src="" alt="" width="192"></a>
                                <input type="file" class="form-control mt-2" name="default_photo" id="edit_default_photo">
                                <small>Kosongkan isian ini jika tidak ingin mengubah foto utama.</small>
                                <span class="text-danger error-text default_photo_error"></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_description">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description" rows="3" name="description"></textarea>
                                <span class="text-danger error-text description_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary cancel-edit"
                        data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
        </div>
    </div>
</div>
</form>
<!-- End Edit Article Modal -->
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
                <div class="col-md-6">
                    <small>Tampilan Produk Pada Pencarian</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter" id="article_status">
                            <option value="">Semua Produk</option>
                            <option value="1">Hanya Produk Yang Tampil</option>
                            <option value="0">Hanya Produk Yang Tidak Tampil</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <small>Status Produk</small>
                    <div class="input-group mb-3">
                        <select class="form-select filter" id="show">
                            <option value="">Aktif</option>
                            <option value="0">Dihapus Sementara</option>
                            <option value="1">Semua Produk</option>
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
                        <th width="30%">Nama Produk</th>
                        <th>Dibuat pada</th>
                        <th>Terakhir diperbarui oleh</th>
                        <th width="20%">Tampilkan produk?</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- Basic Tables end -->
<form id="delete-article" action="" method="POST">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('js')
<script>
    let articleId = 0
    var formData = null

    async function getArticle(id) {
        var url = "{{ route('article.show', '') }}/" + id
        fetch(url)
            .then(response => response.json())
            .then(json => {
                articleId = json.data.id
                $('#default_photo_url').attr('href', json.data.default_photo_url ?? '')
                $('#default_photo_image').attr('src', json.data.default_photo_url ?? '')
                $('#edit_article_name').val(json.data.article_name)
                $('#edit_unit_id').val(json.data.unit_id).change()
                $('#edit_article_status').attr('checked', json.data.article_status == '1' ? 'checked' : '').change()
                $('#edit_description').text(json.data.description)
                $('#edit_article').modal('show')
            })
    }

    function createArticle(formData) {
        clearError()
        let url = "{{ route('article.store') }}"
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            body: formData
        }).then(response => response.json())
    }

    function updateArticle(id, formData) {
        clearError()
        let url = "{{ route('article.update', '') }}/" + id

        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            body: formData
        }).then(response => response.json())
    }

    function deleteArticle(id) {
        var url = "{{ route('article.destroy', '') }}/" + id
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function restoreArticle(id) {
        var url = "{{ route('article.restore', 0) }}/".replace("0", id)
        return fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json; charset=UTF-8',
                'X-CSRF-Token': csrfToken
            },
        }).then(response => response.json())
    }

    function changeArticleStatus(id, status) {
        var url = "{{ route('article.changeStatus', 0) }}/".replace("0", id)
        var data = {
            article_status: status
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

    function clearError() {
        $(document).find('span.error-text').text('')
    }
    
    // Jquery Datatable
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay: 1000,
        ajax: {
            url: "{{ route('article.index_dt') }}",
            data: function (d) {
                d.article_status = $('#article_status').val(),
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
            {data: 'article_name', name: 'article_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'last_edited_by', name: 'last_edited_by'},
            {
                data: 'switch_button', 
                name: 'switch_button',
                orderable: false, 
                searchable: false
            },
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

    $('.yajra-datatable').on('click', '.edit-button', function() {
        let id = $(this).data('id')
        getArticle(id)
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
                deleteArticle(id)
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

        restoreArticle(id)
            .then((json) => {
                toast(json.success, json.message)
                table.draw()
            })
            .catch(error => error)
    })

    $('.yajra-datatable').on('change', '.switch-button', function() {
        let id = $(this).data('id')
        let status = $(this).is(':checked')

        changeArticleStatus(id, status)
            .then((json) => {
                // toast(json.success, json.message)
                if($('#article_status').val() != "") {
                    table.draw()
                }
            })
            .catch(error => error)
    })

    $('.filter').on('change', function() {
        table.draw()
    })

    $('#create-article').on('submit', function(e) {
        e.preventDefault()
        createArticle(new FormData(this))
            .then((json) => {
            if(json.success) {
                $('#create-article')[0].reset()
                $('#create_article').modal('hide')
                table.draw()
                toast(json.success, json.message)
            } else {
                $.each(json.data, function(prefix, value) {
                    $('span.' + prefix + '_error').text(value[0])
                })
            }
        })
        .catch(error => error)
    })

    $('#edit-article').on('submit', function(e) {
        e.preventDefault()
        formData = new FormData(this)
        formData.append('_method', 'PUT')
        updateArticle(articleId, formData)
            .then((json) => {
            if(json.success) {
                $('#edit-article')[0].reset()
                $('#edit_article').modal('hide')
                table.draw()
                toast(json.success, json.message)
            } else {
                $.each(json.data, function(prefix, value) {
                    $('span.' + prefix + '_error').text(value[0])
                })
            }
        })
        .catch(error => error)
    })
</script>
@endsection