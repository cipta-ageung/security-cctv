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
            <a href="#">Kontak</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                    <a href="{{ route('contact.create') }}" class="btn btn-primary btn-round ml-auto">
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
                                <th>Nama</th>
                                <th>Gambar</th>
                                <th>No Telp</th>
                                <th>Alamat</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getData as $data)
                            <tr>
                                <td>{!! $data->nama !!}</td>
                                <td align="center">
                                    @if($data->gambar)
                                    <img width="70%" src="{{ asset('kontak/images/').'/'.$data->gambar }}">
                                    @else
                                    <img width="70%" src="http://placehold.it/300x200">
                                    @endif
                                </td>
                                <td align="center">{!! $data->no_telp !!}</td>
                                <td>{{ $data->alamat }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td><div class="form-button-action">
                                        <a href="{{ route('contact.edit', ['id' => $data->id ]) }}" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Ubah">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <button class="btn btn-link btn-danger btn-lg" data-original-title="Hapus" onclick="deleteConfirmation({{ $data->id }})"><i class="la la-trash"></i></button>
                                        </form>
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
                url: "{{ url('contact/delete') }}/" +id,
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