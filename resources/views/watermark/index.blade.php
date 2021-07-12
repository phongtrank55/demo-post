@extends('layouts.master')

@section('styles')
    <style type="text/css">
        img{
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .row{
            margin-top: 50px
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-12">
            {{-- <img src="{{ asset('assets/img/anh-nuoc-hoa.jpg') }}" alt=""> --}}
            <img src="{{ $src_url }}" alt="">
        </div>
        <div class="col-md-4 col-sm-12">
            <img src="{{ asset('logo-chanhtuoi.png') }}" alt="">
        </div>
        <div class="col-md-4 col-sm-12">
            <img src="{{ asset('assets/img/result.jpg') }}" alt="">
            {{-- <p>Ảnh sau khi chèn</p> --}}
        </div>
    </div>
@endsection
