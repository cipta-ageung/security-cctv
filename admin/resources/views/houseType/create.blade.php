@extends('layouts.master')

@section('title') Tipe Rumah @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Tipe Rumah</h4>
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
            <a href="{{ route('product.index') }}">Produk</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('facilities.index', ['id' => $find->id]) }}">Tipe Rumah</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tambah Tipe Rumah</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah Tipe Rumah</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('houseType.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="name" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Tipe Rumah" value="{{ old('name') }}" required>
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                        </div>
                        @if($errors->has('name'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('name')}}</span></code>
                        @endif
                    </div>
                    
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Gambar</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                <img class="img-upload-preview" width="300" height="200" src="https://placehold.it/300x200" alt="preview">
                                <input type="file" class="form-control form-control-file" id="image" name="image" accept=".jpg,.png,.jpeg" required>
                                <label for="image" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('image'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('image')}}</span></code>
                            @endif
                        </div>
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
    $("#image").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            nama: {
                required: true, 
            },
            image: {
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