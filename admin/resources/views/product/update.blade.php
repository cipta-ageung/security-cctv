@extends('layouts.master')

@section('title') Product @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Product</h4>
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
            <a href="{{ route('product.index') }}">Product</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Ubah Product</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ubah Product</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('product.update') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="nama" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Product" value="{{ $find->nama }}" required>
                        </div>
                        @if($errors->has('nama'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nama')}}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Lokasi <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea id="lokasi" name="lokasi" class="form-control" required="">{{ $find->lokasi }}</textarea>
                        </div>
                        @if($errors->has('lokasi'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('lokasi')}}</span></code>
                        @endif
                    </div>
                    
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Brosur</label>
                        <div class="col-lg-3 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                <img class="img-upload-preview" width="100%" src="{{ asset('produk/images/').'/'.$find->brosur_1 }}" alt="preview">
                                <input type="file" class="form-control form-control-file" id="brosur_1" name="brosur_1" accept=".jpg,.png,.jpeg">
                                <label for="brosur_1" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('brosur_1'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('brosur_1')}}</span></code>
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                @if($find->brosur_2)
                                <img class="img-upload-preview" width="100%" src="{{ asset('produk/images/').'/'.$find->brosur_2 }}" alt="preview">
                                @else
                                <img class="img-upload-preview" width="200" height="300" src="https://placehold.it/200x300"" alt="preview">
                                @endif
                                <input type="file" class="form-control form-control-file" id="brosur_2" name="brosur_2" accept=".jpg,.png,.jpeg" >
                                <label for="brosur_2" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('brosur_2'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('brosur_2')}}</span></code>
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                @if($find->brosur_3)
                                <img class="img-upload-preview" width="100%" src="{{ asset('produk/images/').'/'.$find->brosur_3 }}" alt="preview">
                                @else
                                <img class="img-upload-preview" width="200" height="300" src="https://placehold.it/200x300"" alt="preview">
                                @endif
                                <input type="file" class="form-control form-control-file" id="brosur_3" name="brosur_3" accept=".jpg,.png,.jpeg" >
                                <label for="brosur_3" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('brosur_3'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('brosur_3')}}</span></code>
                            @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Master Plan</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                <img class="img-upload-preview" width="100%" src="{{ asset('produk/images/').'/'.$find->master_plan }}" alt="preview">
                                <input type="file" class="form-control form-control-file" id="master_plan" name="master_plan" accept=".jpg,.png,.jpeg">
                                <label for="master_plan" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('master_plan'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('master_plan')}}</span></code>
                            @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Video <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea id="video" name="video" class="form-control" required="">{{ $find->video }}</textarea>
                        </div>
                        @if($errors->has('video'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('video')}}</span></code>
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

<script>
    $("#brosur_1").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#brosur_2").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#brosur_3").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            nama: {
                required: true, 
            },
            lokasi: {
                required: true, 
            },
            lokasi: {
                required: true, 
            },
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