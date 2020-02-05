@extends('layouts.master')

@section('title') CCTV @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">CCTV</h4>
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
            <a href="{{ route('cctv.index') }}">CCTV</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Tambah CCTV</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tambah CCTV</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('cctv.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label for="product_id" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Cluster<span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">-- Pilih --</option>
                                @foreach($getProduct as $data)
                                <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($errors->has('product_id'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="nama_cctv" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Nama <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <input type="text" class="form-control" id="nama_cctv" name="nama_cctv" placeholder="Nama CCTV" value="{{ old('nama_cctv') }}" required>
                        </div>
                        @if($errors->has('nama_cctv'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('nama_cctv')}}</span></code>
                        @endif
                    </div>

                    <div class="form-group form-show-validation row">
                        <label for="birth" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">CCTV RTSP <span class="required-label">*</span></label>
                        <div class="col-lg-8 col-md-9 col-sm-8">
                           <textarea id="link_stream" name="link_stream" class="form-control" placeholder="Link Streaming RTSP" required="">{{ old('link_stream') }}</textarea>
                        </div>
                        @if($errors->has('link_stream'))
                            <code><span style="color:red; font-size:12px;">{{ $errors->first('link_stream')}}</span></code>
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
    $('#product_id').select2({
        theme: "bootstrap"
    });

    /* validate */

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
            nama_cctv: {
                required: true, 
            },
            link_stream: {
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