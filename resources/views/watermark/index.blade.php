@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/libs/font-awesome/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/fancybox/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/pages/watermark.css')}}">

@endsection

@section('scripts')
    <script src="{{asset('assets/libs/fancybox/jquery.fancybox.min.js')}}"></script>
    <script src="{{asset('assets/js/media.js')}}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12"> Ảnh đại diện</label>
            <div class="controls col-md-9 col-sm-10 col-xs-12 form-image">
                <a class="btn btn-primary" href="javascript:;" onclick="media_popup('add','single','image','Chọn làm ảnh đại diện');">Ảnh đại diện</a>
                <div class="clear"></div>
                <input id="image" type="hidden" name="image" value="">
                <img src="{{asset('img/default_image.png')}}" class="thumb-img">
                <span class="form-image__delete" id="image_delete"><i class="fa fa-trash"></i></span>
                    </div>
            <script>
                $(document).ready(function() {
                    $('body').on('click', '#image_delete', function(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        $(this).closest('.form-image').find('img').attr('src', 'http://sudo-chanhtuoi.devv/assets/img/default_image.png');
                        $('#image').val('');
                    })
                });
            </script>
        </div>
    </div>
    <div class="row watermark">
        <div class="col-md-4 col-sm-12">
            {{-- <img src="{{ asset('assets/img/anh-nuoc-hoa.jpg') }}" alt=""> --}}
            <img src="{{ $src_url }}" alt="">
        </div>
        <div class="col-md-4 col-sm-12">
            <img src="{{ asset('img/logo-chanhtuoi.png') }}" alt="">
        </div>
        <div class="col-md-4 col-sm-12">
            <img src="{{ asset('assets/img/result.jpg') }}" alt="">
            {{-- <p>Ảnh sau khi chèn</p> --}}
        </div>
    </div>
@endsection
