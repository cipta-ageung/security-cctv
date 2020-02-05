@extends('layouts.master')

@section('title') Penghuni @endsection

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
            <a href="#">Penghuni</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                    <a href="{{ route('occupant.create') }}" class="btn btn-primary btn-round ml-auto">
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
                                <th>Kluster - No</th>
                                <th>Nama</th>
                                <th>No Telp</th>
                                <th>Foto</th>
                                <th>Email</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getData as $data)
                            <tr>
                                <td>{{ $data->product->nama }} - {{ $data->no_rumah }}</td>
                                <td>{{ $data->nama_kk }}</td>
                                <td>{{ $data->no_hp }}</td>
                                <td align="center">
                                    @if($data->avatar)
                                    <img width="50%" src="{{ asset('penghuni/images/').'/'.$data->avatar }}">
                                    @endif
                                </td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->name }}</td>
                                <td><div class="form-button-action">
                                    <a href="{{ route('occupant.edit', ['id' => $data->id ]) }}" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Ubah">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <button class="btn btn-link btn-danger btn-lg" data-original-title="Hapus" onclick="resetConfirmation({{ $data->id }})"><i class="la la-refresh"></i></button>
                                    <button class="btn btn-link btn-danger btn-lg" data-original-title="Hapus" onclick="deleteConfirmation({{ $data->id }})"><i class="la la-trash"></i></button>
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

function deleteConfirmation(id) {
    swal({
        title: "Hapus?",
        text: "Yakin hapus data ini?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Tidak, batal!",
        reverseButtons: !0
    }).then(function (e) {

        if (e.value === true) {

            $.ajax({
                type: 'POST',
                url: "{{ url('occupant/delete') }}/" +id,
                data: { _token: '{!! csrf_token() !!}', },
                dataType: 'JSON',
                success: function (results) {
                    if (results.success === "Berhasil") {
                        swal("Done!", results.message, "success");
                        location.reload();
                    } else {
                        swal("Error!", results.message, "error");
                        location.reload();
                    }
                }
            });

        } else {
            e.dismiss;
        }

    }, function (dismiss) {
        return false;
    })
}

function resetConfirmation(id) {
    swal({
        title: "Reset Password?",
        text: "Yakin reset password penghuni?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "Ya, reset!",
        cancelButtonText: "Tidak, batal!",
        reverseButtons: !0
    }).then(function (e) {

        if (e.value === true) {

            $.ajax({
                type: 'POST',
                url: "{{ url('occupant/reset') }}/" +id,
                data: { _token: '{!! csrf_token() !!}', },
                dataType: 'JSON',
                success: function (results) {
                    if (results.success === "Berhasil") {
                        swal("Done!", results.message, "success");
                        location.reload();
                    } else {
                        swal("Error!", results.message, "error");
                        location.reload();
                    }
                }
            });

        } else {
            e.dismiss;
        }

    }, function (dismiss) {
        return false;
    })
}

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