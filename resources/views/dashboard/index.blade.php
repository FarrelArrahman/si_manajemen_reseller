@extends('layouts.template')

@section('title')
Dashboard
@endsection

@section('sub-title')
Selamat datang, <strong>{{ auth()->user()->name }}</strong>
@endsection

@section('content')
<section class="section">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fa fa-info-circle me-1"></i> Untuk dapat mengakses seluruh menu dan melakukan pemesanan, harap lengkapi data reseller terlebih dahulu, <a href="#" class="alert-link">klik di sini.</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Default Layout</h4>
        </div>
        <div class="card-body">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, commodi? Ullam quaerat
            similique iusto
            temporibus, vero aliquam praesentium, odit deserunt eaque nihil saepe hic deleniti? Placeat
            delectus
            quibusdam ratione ullam!
        </div>
    </div>
</section>
@endsection