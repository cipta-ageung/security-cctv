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
            <a href="#">Product</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                    @if($getData->count() < 2)
                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-round ml-auto">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Brosur</th>
                                <th>Lokasi</th>
                                <th>Master Plan</th>
                                <th>Video</th>
                                <th>Fasilitas</th>
                                <th>Tipe Rumah</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getData as $data)
                            <tr>
                                <td>{!! $data->nama !!}</td>
                                <td align="center">
                                    @if($data->brosur_1)
                                    <img width="80%" src="{{ asset('produk/images/').'/'.$data->brosur_1 }}">
                                    @endif
                                    @if($data->brosur_2)
                                    <img width="80%" src="{{ asset('produk/images/').'/'.$data->brosur_2 }}">
                                    @endif
                                    @if($data->brosur_3)
                                    <img width="80%" src="{{ asset('produk/images/').'/'.$data->brosur_3 }}">
                                    @endif
                                </td>
                                <td align="center">{!! $data->lokasi !!}</td>
                                <td align="center">
                                    @if($data->master_plan)
                                    <img width="100%" src="{{ asset('produk/images/').'/'.$data->master_plan }}">
                                    @endif
                                </td>
                                <td>{{ $data->video }}</td>
                                <td><a href="{{ route('facilities.index', ['id' => $data->id ]) }}" class="btn btn-link btn-primary"><span class="btn-label"><i class="la la-picture-o"></i></span>Fasilitas</a></td>
                                <td><a href="{{ route('houseType.index', ['id' => $data->id ]) }}" class="btn btn-link btn-primary"><span class="btn-label"><i class="la la-picture-o"></i></span>Tipe Rumah</a></td>
                                <td>{{ $data->user->name }}</td>
                                <td><div class="form-button-action">
                                        <a href="{{ route('product.edit', ['id' => $data->id ]) }}" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Ubah">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </div>
                                </td>
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