@extends('layouts.master')

@section('title') Tambah Penghuni @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Penghuni</h4>
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
            <a href="{{ route('occupant.index') }}">Penghuni</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tambah Penghuni</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah Penghuni</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('occupant.update') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="product_id" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Cluster<span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach($getProduct as $data)
                                <option value="{{ $data->id }}" {{ $find->product_id == $data->id ? 'selected=""' : '' }}>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        @if($errors->has('product_id'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="no_rumah" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">No Rumah <span class="required-label">*</span></label>
                        <div class="col-lg-2 col-md-3 col-sm-8">
                            <input type="text" class="form-control" id="no_rumah" name="no_rumah" placeholder="Nomor Rumah" value="{{ $find->no_rumah }}" required>
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                        @if($errors->has('no_rumah'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('no_rumah')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="nama_kk" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="nama_kk" name="nama_kk" placeholder="Nama Penghuni" value="{{ $find->nama_kk }}" required>
                        @if($errors->has('nama_kk'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nama_kk')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="no_hp" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">No HP <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="0878xx246xx4" value="{{ $find->no_hp }}" required>
                        @if($errors->has('no_hp'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('no_hp')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Foto</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                @if($find->avatar)
                                <img class="img-upload-preview" width="100" height="100" src="{{ asset('penghuni/images').'/'.$find->avatar }}" alt="preview">
                                @else
                                <img class="img-upload-preview" width="100" height="100" src="https://placehold.it/100x100" alt="preview">
                                @endif
                                <input type="file" class="form-control form-control-file" id="avatar" name="avatar" accept=".jpg,.png,.jpeg">
                                <label for="avatar" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            @if($errors->has('avatar'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('avatar')}}</span></code>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="email" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Email <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@email.com" value="{{ $find->email }}" required>
                        @if($errors->has('email'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('email')}}</span></code>
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
    $('#product_id').select2({
        theme: "bootstrap"
    });

    /* validate */
    $("#avatar").on("change", function(){
        $(this).parent('form').validate();
    })

    // validation when select change
    $("#product_id").change(function(){
        $(this).valid();
    })
    
    $("#Validation").validate({
        validClass: "success",
        rules: {
            product_id: {
                required: true, 
            },
            nama_kk: {
                required: true, 
            },
            no_hp: {
                required: true, 
            },
            email: {
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