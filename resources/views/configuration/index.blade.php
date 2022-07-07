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
            Tes Notifikasi
            <div class="form-group mt-3">
                <label for="message">Judul</label>
                <input type="text" class="form-control" id="message">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" id="send-notification">Kirim</button>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{ url('js/app.js') }}"></script>
<script>
    $(function() {
        
    })
</script>
@endsection