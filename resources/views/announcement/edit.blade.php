@extends('layouts.template')

@section('title')
Ubah Pengumuman
@endsection

@section('sub-title')
Mengubah data pengumuman.
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('vendors/quill/quill.snow.css') }}">
@endsection

@section('content')
<!-- Basic Vertical form layout section start -->
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-24">
            <form class="form form-horizontal" method="POST" action="{{ route('announcement.update', $announcement->id) }}">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <!-- @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            @endif -->
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="title-vertical">Judul <span class="text-danger">*</span></label>
                                            <input type="text" id="title-vertical" class="form-control @error('title') is-invalid @enderror"
                                                name="title" value="{{ old('title') ?? $announcement->title }}">
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="content-vertical">Isi / Konten</label>
                                            <div id="editor">{!! old('content') ?? $announcement->content !!}</div>
                                            <input type="hidden" name="content" value="{{ old('content') ?? $announcement->content }}">
                                            @error('content')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="start-from-vertical">Berlaku dari tanggal <span class="text-danger">*</span></label>
                                            <input type="date" id="start-from-vertical" class="form-control @error('start_from') is-invalid @enderror"
                                                name="start_from" value="{{ $announcement->start_from->format('Y-m-d') ?? old('start_from') ?? today()->format('Y-m-d') }}">
                                            @error('start_from')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="valid-until-vertical">Sampai dengan tanggal <span class="text-danger">*</span></label>
                                            <input type="date" id="valid-until-vertical" class="form-control @error('valid_until') is-invalid @enderror"
                                                name="valid_until" value="{{ $announcement->valid_until->format('Y-m-d') ?? old('valid_until') ?? today()->addDays(1)->format('Y-m-d') }}">
                                            @error('valid_until')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class='form-check form-switch'>
                                            <div class="checkbox">
                                                <input {{ $announcement->is_private == 0 || old('is_private') == 'on' ? 'checked' : '' }} type="checkbox" id="checkbox3" class='form-check-input' name="is_private">
                                                <label for="checkbox3">Tampilkan pengumuman ini ke publik</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Kosongkan Isian</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- // Basic Vertical form layout section end -->
@endsection

@section('js')
<script src="{{ asset('vendors/quill/quill.min.js') }}"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    quill.on('text-change', function(delta, oldDelta, source) {
        document.querySelector("input[name='content']").value = quill.root.innerHTML;
    });
</script>
@endsection