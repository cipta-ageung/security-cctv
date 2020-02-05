@extends('layouts.master')

@section('title') News @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Berita Terbaru</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('home') }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('news.index') }}">Berita Terbaru</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Ubah Berita</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ubah Berita</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('news.update') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="judul" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Judul <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Berita" value="{{ $find->judul }}" required>
                        </div>
                        @if($errors->has('judul'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('judul')}}</span></code>
                        @endif
                    </div>
                    
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Upload Gambar</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                @if($find->gambar)
                                <img class="img-upload-preview" width="250" height="250" src="{{ asset('berita/images/'.$find->gambar) }}" alt="preview">
                                @else
                                <img class="img-upload-preview" width="250" height="250" src="http://placehold.it/250x250" alt="preview">
                                @endif
                                <input type="file" class="form-control form-control-file" id="gambar" name="gambar" accept=".jpg,.png,.jpeg">
                                <label for="gambar" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload a Image</label>
                            </div>
                            @if($errors->has('gambar'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('gambar')}}</span></code>
                            @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Kategori <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="select2-input">
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Berita" {{ $find->kategori === "Berita" ? 'selected' : '' }}>Berita</option>
                                    <option value="Promo" {{ $find->kategori === "Promo" ? 'selected' : '' }}>Promo</option>
                                    <option value="Kegiatan" {{ $find->kategori === "Kegiatan" ? 'selected' : '' }}>Kegiatan</option>
                                </select>
                                @if($errors->has('kategori'))
                                    <code><span style="color:red; font-size:12px;">{{ $errors->first('kategori')}}</span></code>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Isi Berita <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea id="isi_berita" name="isi_berita" class="form-control" required="">{{ $find->isi_berita }}</textarea>
                        </div>
                        @if($errors->has('isi_berita'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('isi_berita')}}</span></code>
                        @endif
                    </div>
                </div>
                <div class="card-action">
                    <div class="row">
                        <div class="col-md-12">
                            <input class="btn btn-success" type="submit" value="Ubah">
                        </div>										
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('bottomscript')
<script src="{{ asset('js/plugin/summernote/summernote-bs4.min.js') }}"></script>

<script>
    $('#isi_berita').summernote({
        placeholder: 'Isi Konten',
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New'],
        tabsize: 2,
        height: 300,
        required: true
    });

    $('#kategori').select2({
        theme: "bootstrap"
    });

    $("#gambar").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            isi_berita: {
                required: true,
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
    });
</script>

@endsection