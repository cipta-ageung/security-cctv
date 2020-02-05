@extends('layouts.master')

@section('title') Tentang Aplikasi @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Tentang Aplikasi</h4>
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
            <a href="{{ route('about.index') }}">Tentang Aplikasi</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tambah Tentang Aplikasi</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah Tentang Aplikasi</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('about.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="nama" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ old('nama') }}" required>
                        </div>
                        @if($errors->has('nama'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nama')}}</span></code>
                        @endif
                    </div>
                    
                    <!-- <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Upload Gambar <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                <img class="img-upload-preview" width="200" height="100" src="http://placehold.it/250x100" alt="preview">
                                <input type="file" class="form-control form-control-file" id="gambar" name="gambar" accept=".jpg,.png,.jpeg" >
                                <label for="gambar" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload a Image</label>
                            </div>
                            @if($errors->has('gambar'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('gambar')}}</span></code>
                            @endif
                        </div>
                    </div> -->

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Deskripsi <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea name="deskripsi" class="form-control" required="">{{ old('deskripsi') }}</textarea>
                        </div>
                        @if($errors->has('deskripsi'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('deskripsi')}}</span></code>
                        @endif
                    </div>
                </div>
                <div class="card-action">
                    <div class="row">
                        <div class="col-md-12">
                            <input class="btn btn-success" type="submit" value="Simpan">
                        </div>										
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('bottomscript')

<script>
    $("#gambar").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            deskripsi: {
                required: true,
            },
            nama: {
                required: true,
            },
            // gambar: {
            //     required: true,
            // }
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