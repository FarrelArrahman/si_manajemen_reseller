@extends('layouts.template')

@section('title')
{{ $productVariant->product->product_name }} ({{ $productVariant->product_variant_name }})
@endsection

@section('sub-title')

@endsection

@section('action-button')
@if(auth()->user()->isAdmin())
<a href="{{ route('product_variant.edit', ['product' => $productVariant->product->sku, 'productVariant' => str_replace('#', '', $productVariant->color)]) }}" class="btn btn-warning"><i class="fas fa-edit fa-sm me-2"></i> Ubah Varian Produk</a>
<a href="#" data-bs-toggle="modal" data-bs-target="#changeStockModal" class="btn btn-primary"><i class="fa fa-plus fa-sm me-2"></i> Tambah/Kurangi Stok</a>
@endif
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($productVariant->trashed())
            <div class="alert alert-danger">
                Varian ini telah dihapus sementara. Untuk memulihkan dan menampilkan varian ini, <a href="{{ route('product_variant.restore', ['product' => $productVariant->product->sku, 'productVariant' => $productVariant->color, 'ref' => 'show']) }}">klik di sini</a>.
            </div>
            @endif
            <div class="card">
                <div class="card-content">
                    <div class="card-header pb-3">
                        <h4 class="card-title">Detail Varian</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a class="image-popup" href="{{ Storage::url($productVariant->photo) }}">
                                        <img src="{{ Storage::url($productVariant->photo) }}" alt="" width="100%">
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="product-name-vertical">Nama Produk</label>
                                            <p class="fs-5 text-dark"><a href="{{ route('product.show', $productVariant->product->sku) }}">{{ $productVariant->product->product_name }}</a></p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="product-name-vertical">Varian Produk</label>
                                            <p class="fs-5 text-dark">{{ $productVariant->product_variant_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="color-vertical">Warna</label>
                                            <p class="fs-5 text-dark m-0">
                                                <div class="rounded-circle float-start me-1" style="border:1px solid black;background-color:{{ $productVariant->color }};width:20px;height:20px;">&nbsp;</div> 
                                                {{ $productVariant->color }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- @if(auth()->user()->isAdmin())
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="description-vertical">Harga Pokok</label>
                                            <p class="fs-5 text-dark">Rp. {{ number_format($productVariant->base_price, 0, '', '.') }}</p>
                                        </div>
                                    </div>
                                    @endif -->
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="description-vertical">Harga Umum</label>
                                            <p class="fs-5 text-dark">Rp. {{ number_format($productVariant->general_price, 0, '', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="description-vertical">Harga Reseller</label>
                                            <p class="fs-5 text-dark">Rp. {{ number_format($productVariant->reseller_price, 0, '', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="product-variant-vertical">Stok</label>
                                            <p class="fs-5 text-dark" id="productVariantStock">{{ $productVariant->stock }}</p>
                                        </div>
                                    </div>
                                    @if(auth()->user()->isAdmin())
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="status-vertical">Status Aktif</label>
                                            <p class="fs-5 text-dark">{!! $productVariant->statusBadge() !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="added-by-vertical">Ditambahkan oleh</label>
                                            <p class="fs-5 text-dark">{{ $productVariant->addedBy->name }} ({{ $productVariant->created_at->format('Y-m-d h:i') }})</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="updated-by-vertical">Terakhir diubah oleh</label>
                                            <p class="fs-5 text-dark">{{ $productVariant->lastEditedBy->name }} ({{ $productVariant->updated_at->format('Y-m-d h:i') }})</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Log -->
            @if(auth()->user()->isAdmin())
            <div class="card">
                <div class="card-content">
                    <div class="card-header pb-3">
                        <h4 class="card-title">Log Perubahan Stok</h4>
                    </div>
                    <div class="card-body">
                        <table class="table yajra-datatable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Qty Ubah</th>
                                    <th>Qty Awal</th>
                                    <th>Qty Akhir</th>
                                    <th>Catatan</th>
                                    <th>Penanggung Jawab</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
<!-- // Basic Vertical form layout section end -->

<!-- Start Modal -->
<!--primary theme Modal -->
<div class="modal fade text-left" id="changeStockModal" tabindex="-1" role="dialog"
    aria-labelledby="changeStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="changeStockModalLabel">Tambah atau Kurangi Stok</h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qty-before-vertical">Stok Awal</label>
                            <p class="fs-5 text-dark" id="qtyBefore">{{ $productVariant->stock }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qty-after-vertical">Stok Setelah Diubah</label>
                            <p class="fs-5 text-dark" id="qtyAfter">{{ $productVariant->stock }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="qtyChange">Ubah stok sebanyak</label>
                            <input type="number" class="form-control" name="stock" value="0" id="qtyChange">
                            <small class="fw-bold">Untuk mengurangi stok, berikan tanda minus ( - ) pada stok.</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" id="note" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" id="saveStock" disabled="disabled">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('js')
<script type="text/javascript">
    function changeStock(id, stock, note) {
        var url = "{{ route('product_variant.changeStock', ['product' => $productVariant->product->id, 'productVariant' => 'x']) }}/".replace("x", id)
        var data = {
            stock: stock,
            note: note
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
        ajax: "{{ route('product_variant_stock_log.index_dt', $productVariant->id) }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'qty_change', name: 'qty_change'},
            {data: 'qty_before', name: 'qty_before'},
            {data: 'qty_after', name: 'qty_after'},
            {data: 'note', name: 'note'},
            {data: 'handled_by', name: 'handled_by'},
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
    
    $('#qtyChange').on('change keyup keypress blur', function() {
        if($(this).val() == '-0') {
            $(this).val(0)
        }

        let qtyAfter = +$('#qtyBefore').text() + +$(this).val();
        $('#qtyAfter').text(qtyAfter)

        let disabled = false
        if(qtyAfter < 0 || $(this).val() == 0) disabled = true
        $('#saveStock').prop('disabled', disabled)
    })

    let loading = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...'

    $('#saveStock').on('click', function() {
        let id = '{{ $productVariant->id }}'
        console.log(id)
        let stock = $('#qtyChange').val()
        let note = $('#note').val()

        $(this).html(loading)
        $(this).prop('disabled', true)

        changeStock(id, stock, note)
            .then((json) => {
                toast(json.success, json.message)
                
                if(json.success) {
                    $('.close').click()
                    $('#qtyBefore').text(json.data.qty_after)
                    $('#productVariantStock').text(json.data.qty_after)
                }
                
                $(this).html("Simpan")
                $('#qtyChange').val(0)
                $('#note').val("")

                table.draw()
            })
            .catch(error => error)
    })
</script>
@endsection