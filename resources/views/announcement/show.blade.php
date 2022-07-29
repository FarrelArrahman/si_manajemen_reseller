@extends('layouts.template')

@section('title')
{{ $announcement->title }}
@endsection

@section('sub-title')
Dibuat oleh <strong>{{ $announcement->createdBy->name }}</strong> pada <strong>{{ $announcement->created_at->isoFormat('dddd, DD MMMM Y hh:mm') . ' WITA' }}</strong>
@endsection

@section('action-button')
@if(auth()->user()->isAdmin())
<a href="{{ route('announcement.edit', $announcement->id) }}" class="btn btn-warning"><i class="fas fa-edit fa-sm me-2"></i> Ubah Pengumuman</a>
@endif
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            @if($announcement->content != "")
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        {!! $announcement->content !!}
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center" id="not_available">
                                <i class="text-danger">(kosong)</i>
                            </div>
                        </div>
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