@extends('layouts.master')

@section('title') Pembayaran Cicilan @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Pembayaran Cicilan</h4>
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
            <a href="#">Pembayaran Cicilan</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                    <a href="{{ route('reminder-installment.create') }}" class="btn btn-primary btn-round ml-auto">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Cluster</th>
                                <th>Penghuni</th>
                                <th>Nominal</th>
                                <th>Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getData as $data)
                            <tr>
                                <td>{!! $data->month !!}</td>
                                <td>{!! $data->penghuni->product->nama !!}</td>
                                <td>{!! $data->penghuni->nama_kk !!}</td>
                                <td>{{ number_format($data->nominal,0,0,'.') }}</td>
                                <td>{{ $data->user->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('bottomscript')

<script>
$(document).ready(function() {
    $('#basic-datatables').DataTable({
    });
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