@extends('layouts.master')

@section('title') Profil @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Profil</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('home') }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah Profil</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="product_id" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Cluster <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" name="product_id" placeholder="Nama Profil" value="{{ old('product_id',$getData->product->nama ) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group form-show-validation row">
                        <label for="name" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Profil" value="{{ old('name',$getData->name) }}" required>
                            <input type="hidden" class="form-control" name="id" value="{{ $getData->id }}" required>
                        </div>
                        @if($errors->has('name'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('name')}}</span></code>
                        @endif
                    </div>
                    <div class="form-group form-show-validation row">
                        <label for="email" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Email <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email Untuk Login Ke Aplikasi" value="{{ old('email',$getData->email) }}" required>
                        @if($errors->has('email'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('email')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="no_hp" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">No HP <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No HP Profil" value="{{ old('no_hp',$getData->no_hp) }}" required>
                        @if($errors->has('no_hp'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('no_hp')}}</span></code>
                        @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Foto</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <div class="input-file input-file-image">
                                @if(!$getData->avatar)
                                <img class="img-upload-preview" width="100" height="100" src="https://placehold.it/100x100" alt="preview">
                                @else
                                <img class="img-upload-preview" width="100" height="100" src="{{ asset('admin/images/').'/'.$getData->avatar }}" alt="preview">
                                @endif
                                <input type="file" class="form-control form-control-file" id="avatar" name="avatar" accept=".jpg,.png,.jpeg">
                                <label for="avatar" class=" label-input-file btn btn-icon btn-primary btn-round btn-lg"><i class="la la-file-image-o"></i> Upload an Image</label>
                            </div>
                            @if($errors->has('avatar'))
                                <code><span style="color:red; font-size:12px;">{{ $errors->first('avatar')}}</span></code>
                            @endif
                        </div>
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="password" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Password Baru</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                            @if($errors->has('password'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('password')}}</span></code>
                            @endif
                        </div>
                    </div>
                    <div class="form-group form-show-validation row">
                        <label for="confirmPassword" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Password Konfirmasi</label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter Password">
                            @if($errors->has('confirmPassword'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('confirmPassword')}}</span></code>
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
    $("#avatar").on("change", function(){
        $(this).parent('form').validate();
    })

    $("#Validation").validate({
        validClass: "success",
        rules: {
            name: {
                required: true, 
            },
            email: {
                required: true, 
            },
            no_hp: {
                required: true, 
            },
            confirmPassword: {
                equalTo: "#password"
            },
        },
        highlight: function(element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
    });
    
@if(Session::has('berhasil'))
$.notify({
    icon: 'flaticon-alarm-1',
    title: 'Berhasil',
    message: '{{ Session::get('berhasil') }}',
},{
    type: 'success',
    placement: {
        from: "bottom",
        align: "right"
    },
    time: 800,
});
@endif
</script>

@endsection