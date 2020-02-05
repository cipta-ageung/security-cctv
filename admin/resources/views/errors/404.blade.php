@extends('layouts.master')

@section('title') Fasilitas @endsection

@section('content')
<div class="wrapper not-found">
    <h1 class="animated fadeIn">404</h1>
    <div class="desc animated fadeIn"><span>OOPS!</span><br/>Halaman atau data yang anda cari tidak ada</div>
    <a href="{{ URL::previous() }}" class="btn btn-primary btn-back-home mt-4 animated fadeInUp">
        <span class="btn-label mr-2">
            <i class="flaticon-home"></i>
        </span>
        Kembali
    </a>
</div>

@endsection