@extends('layouts.master')

@section('title') Forum Jualan @endsection

@section('content')
<div class="page-header">
    <h4 class="page-title">Forum Jualan</h4>
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
            <a href="#">Forum Jualan</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover" >
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Nama</th>
                                <th>Gambar</th>
                                <th>Harga</th>
                                <th>No Telp</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getJualan as $data)
                            <tr>
                                <td>{{ $data->judul }}</td>
                                <td>{{ $data->nama }}</td>
                                <td align="center">
                                    @if($data->gambar_1)
                                    <img width="70%" src="{{ asset('jualan/images/').'/'.$data->gambar_1 }}">
                                    @else
                                    <img width="70%" src="https://placehold.it/300x200">
                                    @endif
                                </td>
                                <td>{{ $data->harga }}</td>
                                <td align="center">{!! $data->no_hp !!}</td>
                                <td>{{ $data->penjual->nama_kk }}</td>
                                <td><button class="btn btn-link btn-danger" data-original-title="Hapus" onclick="deleteConfirmation({{ $data->id }})"><i class="la la-trash"></i></button>
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
                url: "{{url('/jualan')}}/" + id,
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
</script>

@endsection