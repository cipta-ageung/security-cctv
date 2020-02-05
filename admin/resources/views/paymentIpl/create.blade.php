@extends('layouts.master')

@section('title') Pembayaran Iuran @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Pembayaran Iuran</h4>
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
            <a href="{{ route('reminder-ipl.index') }}">Pembayaran Iuran</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tambah Pembayaran Iuran</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah Pembayaran Iuran</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('reminder-ipl.store') }}">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right" for="bulan">Bulan <span class="required-label">*</span></label>
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="input-group">
                                <input type="text" class="form-control" id="datepicker_start" name="bulan" value="{{ old('bulan') }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('bulan'))
                        <code class="col-lg-3 col-md-3 col-sm-2 mt-sm-2 text-right"><span style="color:red; font-size:12px;">{{ $errors->first('bulan') }}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="nominal" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nominal <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="nominal" name="nominal" placeholder="Nominal" value="{{ old('nominal') }}" required>
                        </div>
                        @if($errors->has('nominal'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nominal')}}</span></code>
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
    $('#datepicker_start').datetimepicker({
        format: 'YYYY/MM',
        minDate:  moment(),
    });
    
    $("#Validation").validate({
        validClass: "success",
        rules: {
            bulan: {
                required: true, 
            },
            iuran: {
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