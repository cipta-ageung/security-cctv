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
            <a href="#">CCTV</a>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">List Data</h4>
                    <a href="{{ route('cctv.create') }}" class="btn btn-primary btn-round ml-auto">
                        <i class="la la-plus"></i>
                        Tambah Data
                    </a>
                </div>
            </div>
            <div class="card-body">
                @foreach($getData as $data => $product)
                <div class="row">
                    @foreach($product as $list)
                    <div class="col-md-4">
                        <div class="card card-post card-round">
                            <iframe id="ytplayer" type="text/html" height="200" width="300"  src="{{ $list->link_stream }}" frameborder="0"></iframe>
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="social-media">
                                        <span>{{ $list->nama_cctv }}</span>
                                        <a href="{{ route('cctv.edit', ['id' => $list->id ]) }}" data-toggle="tooltip" title="" class="btn btn-link btn-primary" data-original-title="Ubah"><i class="la la-edit"></i></a>
                                        <button class="btn btn-link btn-danger btn-lg" data-original-title="Hapus" onclick="deleteConfirmation({{ $list->id }})"><i class="la la-trash"></i></button>
                                    </div>
                                </div>
                                <!-- <a href="#" class="btn btn-primary btn-rounded btn-sm">Read More</a> -->
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
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
                url: "{{ url('cctv/delete') }}/" +id,
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

var player;
  function onYouTubePlayerAPIReady() {
    player = new YT.Player('ytplayer', {
      height: '200',
      videoId: '2wUT34CNl58'
    });
  }
</script>

@endsection