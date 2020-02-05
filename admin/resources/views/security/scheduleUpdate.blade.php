@extends('layouts.master')

@section('title') Ubah Jadwal Security @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Ubah Jadwal Security</h4>
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
            <a href="{{ route('security.index') }}">Security</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="{{ route('securitySchedule.index', ['id' => $find->security_id ]) }}">Jadwal Security</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="">Ubah Jadwal Security</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Ubah Jadwal Security  || {{ $occupant->nama_kk }} - {{ $getProduct->nama }}</div>
            </div>
            <form id="Validation" method="POST" action="{{ route('securitySchedule.update') }}">
            @csrf
                <div class="card-body">
                    <div class="form-group form-show-validation row">
                        <label class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right" for="date_start">Tanggal <span class="required-label">*</span></label>
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" value="{{ $find->id }}">
                                <input type="hidden" name="security_id" value="{{ $find->security_id }}">
                                <input type="hidden" name="security_name" value="{{ $occupant->nama_kk }}">
                                <input type="text" class="form-control" id="datepicker_start" name="date_start" value="{{ old('date_start', $find->date) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-o"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('date_start'))
                        <code class="col-lg-3 col-md-3 col-sm-2 mt-sm-2 text-right"><span style="color:red; font-size:12px;">{{ $errors->first('date_start') }}</span></code>
                        @endif
                    </div>
                    
                    <div class="form-group form-show-validation row">
                        <label for="name" class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right">Shift <span class="required-label">*</span></label>
                        <div class="col-lg-4 col-md-9 col-sm-8">
                            <select class="form-control" id="shift" name="shift" required>
                                <option value="">-- Pilih --</option>
                                <option value="Pagi" {{ old('shift', $find->shift) == 'Pagi' ? 'selected=""' : '' }}>Pagi (00:00 - 20:00)</option>
                                <option value="Malam" {{ old('shift', $find->shift) == 'Malam' ? 'selected=""' : '' }}>Malam (20:00 - 08:00)</option>
                            </select>
                            <input type="hidden" class="form-control" id="id" name="id" value="{{ $find->id }}" required>
                        @if($errors->has('shift'))
                            <code class="col-lg-3 col-md-3 col-sm-4 mt-sm-2 text-right"><span style="color:red; font-size:12px;">{{ $errors->first('shift')}}</span></code>
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
    $('#shift').select2({
        theme: "bootstrap"
    });

    $('#datepicker_start').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate:  moment(),
    });

    $("#Validation").validate({
        validClass: "success",
        rules: {
            shift: {
                required: true, 
            },
            datepicker_start: {
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

    @if(Session::has('gagal'))
    $.notify({
        icon: 'flaticon-alarm-1',
        title: 'Gagal',
        message: '{{ Session::get('gagal') }}',
    },{
        type: 'danger',
        placement: {
            from: "top",
            align: "right"
        },
        time: 1000,
    });
    @endif
</script>

@endsection