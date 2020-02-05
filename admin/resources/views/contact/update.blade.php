@extends('layouts.master')

@section('title') Kontak @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Kontak</h4>
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
            <a href="{{ route('contact.index') }}">Kontak</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Ubah Kontak</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ubah Kontak</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('contact.update') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="product_id" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Cluster<span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach($getProduct as $data)
                                <option value="{{ $data->id }}" {{ $data->id == $find->product_id ? 'selected=""' : '' }}>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($errors->has('product_id'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
                        @endif
                    </div>
                    <div class="form-group form-show-validation row">
                        <label for="nama" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kontak" value="{{ $find->nama }}" required>
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                        </div>
                        @if($errors->has('nama'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nama')}}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Alamat <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea id="alamat" name="alamat" class="form-control" placeholder="Alamat" required="">{{ $find->alamat }}</textarea>
                        </div>
                        @if($errors->has('alamat'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('alamat')}}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="no_telp" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">No Telp <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="No Telp" value="{{ $find->no_telp }}" required>
                        </div>
                        @if($errors->has('no_telp'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('no_telp')}}</span></code>
                        @endif
                    </div>
                    
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Gambar</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                <img class="img-upload-preview" width="300" height="200" src="{{ asset('kontak/images').'/'.$find->gambar }}" alt="preview">
                                <input type="file" class="form-control form-control-file" id="gambar" name="gambar" accept=".jpg,.png,.jpeg">
                                <label for="gambar" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('gambar'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('gambar')}}</span></code>
                            @endif
                        </div>
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
    $("#gambar").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            nama: {
                required: true, 
            },
            alamat: {
                required: true, 
            },
            no_telp: {
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