@extends('layouts.master')

@section('title')
Dashboard
@endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Dashboard</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('home') }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-primary card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="flaticon-users"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Penghuni</p>
                            <h4 class="card-title">{{ $getPenghuni->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-info card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="flaticon-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Jualan</p>
                            <h4 class="card-title">{{ $getJualan->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-success card-round">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="flaticon-analytics"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Keamanan</p>
                            <h4 class="card-title">{{ $getSecurity->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-secondary card-round">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="flaticon-interface-6"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Berita</p>
                            <h4 class="card-title">{{ $getBerita->count() }} </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($changePassword == TRUE)
<!-- Form -->
<div class="row">
    <div class="col-md-6 ml-auto mr-auto">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Harap Ganti Password Anda!!</div>
            </div>
            <form id="Validation" method="post" action="{{ route('gantiPassword') }}">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="password" class="col-lg-4 col-md-4 col-sm-4 mt-sm-2 text-right">Password Baru <span class="required-label">*</span></label>
                        <div class="col-lg-6 col-md-9 col-sm-8">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required="">
                            @if($errors->has('password'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('password')}}</span></code>
                            @endif
                        </div>
                    </div>
                    <div class="form-group form-show-validation row">
                        <label for="confirmPassword" class="col-lg-4 col-md-4 col-sm-4 mt-sm-2 text-right">Password Konfirmasi<span class="required-label">*</span></label>
                        <div class="col-lg-6 col-md-9 col-sm-8">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Enter Password" required="">
                            @if($errors->has('confirmPassword'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('confirmPassword')}}</span></code>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-action">
                    <div class="row">
                        <div class="col-md-12">
                            <input class="btn btn-success" type="submit" value="Submit">
                            <button class="btn btn-danger">Cancel</button>
                        </div>										
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@section('bottomscript')
<script>

$("#Validation").validate({
    validClass: "success",
    rules: {
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
</script>
@endsection