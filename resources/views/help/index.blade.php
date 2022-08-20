@extends('layouts.template')

@section('title')
Bantuan
@endsection

@section('sub-title')
Halaman ini berisi bantuan dan FAQ.
@endsection

@section('action-button')
@if(auth()->user()->isAdmin())
<a href="{{ route('configuration.index') }}" class="btn btn-warning"><i class="fas fa-edit fa-sm me-2"></i> Ubah Konten Halaman Bantuan</a>
@endif
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        {!! $configuration !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- // Basic Vertical form layout section end -->
@endsection

@section('js')
<script>
</script>
@endsection