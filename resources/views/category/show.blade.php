@extends('layouts.template')

@section('title')
{{ $product->product_name }}
@endsection

@section('sub-title')

@endsection

@section('action-button')
<a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning"><i class="fas fa-edit fa-sm me-2"></i> Ubah Master Produk</a>
<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#primary"><i class="fas fa-plus fa-sm me-2"></i> Tambah Varian Baru</a>
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($product->trashed())
            <div class="alert alert-danger">
                Produk ini telah dihapus sementara. Untuk memulihkan dan menampilkan produk ini, <a href="{{ route('product.restore', ['product' => $product->id, 'ref' => 'show']) }}">klik di sini</a>.
            </div>
            @endif
            <div class="card">
                <div class="card-content">
                    <div class="card-header pb-3">
                        <ul class="nav nav-pills" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link show active" id="information-tab" data-bs-toggle="tab" href="#information" role="tab"
                                    aria-controls="information" aria-selected="true">Informasi</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="variants-tab" data-bs-toggle="tab" href="#variants" role="tab"
                                    aria-controls="variants" aria-selected="false">Varian</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <a href="{{ Storage::url($product->default_photo) }}">
                                                <img src="{{ Storage::url($product->default_photo) }}" alt="" width="100%">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product-name-vertical">Nama Produk</label>
                                                    <p class="fs-5 text-dark">{{ $product->product_name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description-vertical">Deskripsi</label>
                                                    <p class="fs-5 text-dark">{{ $product->description ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="status-vertical">Status Aktif</label>
                                                    <p class="fs-5 text-dark">{!! $product->statusBadge() !!}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="product-variant-vertical">Varian Warna</label>
                                                    <p class="fs-5 text-dark">{{ $product->productVariants->count() }}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="added-by-vertical">Ditambahkan oleh</label>
                                                    <p class="fs-5 text-dark">{{ $product->addedBy->name }} ({{ $product->created_at->format('Y-m-d h:i') }})</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="updated-by-vertical">Terakhir diubah oleh</label>
                                                    <p class="fs-5 text-dark">{{ $product->lastEditedBy->name }} ({{ $product->updated_at->format('Y-m-d h:i') }})</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table" id="table">
                                            <thead>
                                                <tr>
                                                    <th width="70%">Varian Warna</th>
                                                    <th>Biaya Pendaftaran (Rp.)</th>
                                                    <th>Jumlah Tim / Peserta</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic Vertical form layout section end -->

<!-- Start Modal -->
<!--primary theme Modal -->
<div class="modal fade text-left" id="primary" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Primary Modal
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                Tart lemon drops macaroon oat cake chocolate toffee chocolate
                bar icing. Pudding jelly beans
                carrot cake pastry gummies cheesecake lollipop. I love cookie
                lollipop cake I love sweet
                gummi
                bears cupcake dessert.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                    data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-primary ml-1"
                    data-bs-dismiss="modal">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Accept</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('js')
<script>
</script>
@endsection