<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Managerment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{!! url('/template-admin/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/css/font-awesome.min.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/css/daterangepicker.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/vendors/select2/dist/css/select2.min.css') !!}" rel="stylesheet">
    <link href="{!! url('/js/fancybox/jquery.fancybox.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/vendors/dropzone/dist/min/dropzone.min.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/css/custom.min.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/css/style1.css') !!}" rel="stylesheet">
    <link href="{!! url('/template-admin/css/media.css') !!}" rel="stylesheet">
</head>
<body class="media-body" style="background: #fff; overflow-y: auto;">
<div class="row">
    <div class="col-md-12">
        <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="@if($view=='add'){{'active'}}@endif"><a href="#media-main-add" id="media-tab-add" role="tab" data-toggle="tab" aria-expanded="@if($view=='add'){{'true'}}@else{{'false'}}@endif">Thêm mới ảnh</a></li>
                <li role="presentation" class="@if($view=='list'){{'active'}}@endif"><a href="#media-main-list" role="tab" id="media-tab-list" data-toggle="tab" aria-expanded="@if($view=='list'){{'true'}}@else{{'false'}}@endif">Thư viện ảnh</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade @if($view=='add'){{'active in'}}@endif" id="media-main-add">
                    <div class="x_content">
                        <p>Chọn hoặc kéo file cần tải lên vào khung bên dưới</p>
                        <form action="{!! route('media.store') !!}" class="dropzone" id="sudoUploadForm">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="dz-custom dz-message">Chọn file ảnh để tải lên (tối đa 5)</div>
                        </form>
                        <br />
                        <br />
                        <br />
                        <br />
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade @if($view=='list'){{'active in'}}@endif" id="media-main-list">
                    <div id="media-list">
                        <div id="media-list-control">
                            <input type="text" id="media-search-name" placeholder="Tìm theo tên ...">
                            <div class="media-top-pagination clearfix">{{ $data->links() }}</div>
                        </div>
                        <div id="media-list-wrap">
                            @include('media.list')
                        </div>
                        <div class="media-bottom-pagination clearfix">{{ $data->links() }}</div>
                    </div>
                    <div id="media-info">
                        <div class="media-info-img"></div>
                        <form class="media-info-form" action="#" method="post">
                            <input type="hidden" id="media-setting-id" value="0">
                            <p class="media-setting-title">Cập nhật thông tin ảnh</p>
                            <label class="media-setting">
                                <span class="name">Tiêu đề</span>
                                <input class="field" type="text" id="media-setting-title" value="">
                            </label>
                            <label class="media-setting">
                                <span class="name">Mô tả</span>
                                <textarea class="field" id="media-setting-caption"></textarea>
                            </label>
                            <label class="media-setting">
                                <span class="name">&nbsp;</span>
                                <button id="media-setting-btn" class="btn btn-info btn-sm" style="margin: 10px 0px;">Cập nhật thông tin ảnh</button>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
            <div id="media-action">
                <button id="media-chose" disabled>{{$text}}</button>
            </div>
        </div>
    </div>
</div>

<script src="{!! url('/template-admin/js/jquery.min.js') !!}"></script>
<script src="{!! url('/template-admin/js/bootstrap.min.js') !!}"></script>
<script src="{!! url('/template-admin/js/fastclick.js') !!}"></script>
<script src="{!! url('/template-admin/js/nprogress.js') !!}"></script>
<script src="{!! url('/template-admin/js/moment.min.js') !!}"></script>
<script src="{!! url('/template-admin/vendors/dropzone/dist/min/dropzone.min.js') !!}"></script>
<script src="{!! url('/template-admin/js/jquery.sortable.min.js') !!}"></script>
<script src="{!! url('/template-admin/js/daterangepicker.js') !!}"></script>
<script src="{!! url('/template-admin/vendors/select2/dist/js/select2.full.min.js') !!}"></script>
<script src="{!! url('/template-admin/js/jquery.tagsinput.js') !!}"></script>
<script src="{!! url('/js/fancybox/jquery.fancybox.js') !!}"></script>

<script src="{!! url('/template-admin/js/custom.min.js') !!}"></script>
@yield('script')
<script src="{!! url('/template-admin/js/script.js') !!}"></script>
{{-- Các script chỉ xuất hiện ở duy nhất file này: --}}
<script>
    Dropzone.options.sudoUploadForm = {
        //http://www.dropzonejs.com/#configuration-options
        url:'/media/store',
        paramName: "files",
        maxFilesize: 2, // MB
        uploadMultiple: true,
        parallelUploads: 100, //số file xử lý song song trên 1 request
        maxFiles: 5, //Tối đa 5 file 1 lần
        acceptedFiles: '.jpeg,.jpg,.png,.gif,.svg,.webp',
        //autoProcessQueue: false,
        accept: function(file, done) {
            done();
        },
        error: function(file, response) {
            console.log('Up-Error: '+response);
        },
        processing: function (file) {
            var imgLoading = Dropzone.createElement('<img class="img-loading" style="position:absolute;top:-20px;left:calc(50% - 8px);z-index:10;" src="/template-admin/images/loading.gif" />');
            file.previewElement.append(imgLoading);
        },
        success: function(file,response) {
            $('.img-loading').remove();
            console.log('Up-Success:');
            console.log(response);
            if(response.success) {
                var selected_ids = '';//danh sách id đc chọn
                $.each(response.result, function( key, value ) {
                    selected_ids += value.id+',';
                });
                selected_ids = selected_ids.replace(/,+$/, '');//bỏ dấu , ở cuối
                window.location.href = '/media/library?view=list&type={{$type}}&element={{$element}}&text={{$text}}&selected_ids='+selected_ids;
            }
        }
    };
</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //Click chọn ảnh để chèn vào nội dung
    $('#media-list').on('click','.media-item',function () {
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.media-info-img').html('');
            $('.media-info-form').css('display','none');
            $('#media-chose').prop('disabled',true);
        }else{
            if('{{$type}}' == 'single') {
                $('.media-item').removeClass('active');
            }
            $(this).addClass('active');
            var id = $(this).attr('data-id');
            var user = $(this).attr('data-user');
            var name = $(this).attr('data-name');
            var title = $(this).attr('data-title');
            var caption = $(this).attr('data-caption');
            var src = $(this).find('img').attr('src');
            var time = $(this).attr('data-time');
            var html = '<img src="'+src+'"/>';
            html += '<p style="padding: 5px 0;font-size: 1.2em;"><b>Tên ảnh: '+name+'</b></p>';
            html += '<p>Đăng lúc: '+time+'</p>';
            $('.media-info-img').html(html);
            $('.media-info-form').find('#media-setting-id').val(id);
            $('.media-info-form').find('#media-setting-title').val(title);
            $('.media-info-form').find('#media-setting-caption').val(caption);
            $('.media-info-form').css('display','block');
            $('#media-chose').prop('disabled',false);
        }
    });
    //Tìm ảnh theo tên
    var searchImage = false;
    $('#media-search-name').on('keyup',function () {
        var keyword = $(this).val();
        if(searchImage == false){
            searchImage = true;
            $.ajax({
                url:'/media/search',
                dataType:'json',
                type:'post',
                data:{keyword:keyword},
                beforeSend:function(){
                    $('#media-list-control').append('<img id="loading-tiny" src="/template-admin/images/loading.gif" style="display: inline-block"/>');
                },
                success:function(result){
                    $('#loading-tiny').remove();
                    searchImage = false;
                    if(result.status == 1) {
                        $('#media-list-wrap').html(result.html);
                    }else {
                        $('#media-list-wrap').html('');
                    }
                }
            });
        }
    });

    //Có thay đổi thông tin tiêu đề, mô tả hay không
    var updateImage = false;
    $('#media-setting-title').change(function () {
        updateImage = true;
    });
    $('#media-setting-caption').change(function () {
        updateImage = true;
    });
    //Click nút cập nhật thông tin ảnh
    $('#media-setting-btn').on('click',function (e) {
        e.preventDefault();
        if(updateImage) {
            var id = $('#media-setting-id').val();
            var title = $('#media-setting-title').val();
            var caption = $('#media-setting-caption').val();
            $.ajax({
                url:'/media/update',
                dataType:'json',
                type:'post',
                data:{id:id,title:title,caption:caption},
                success:function(result){
                    var alert_type = 'warning';
                    if(result.status == 1) alert_type = 'success';
                    var alert_str = '<div class="alert alert-'+alert_type+' alert-dismissible show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result.message+'</div>';
                    $('.media-info-form').append(alert_str);
                    $('#media-item-'+id).attr('data-title', title);
                    $('#media-item-'+id).attr('data-caption', caption);
                }
            });
        }
        return false;
    });

    //Click nút chèn ảnh
    $('#media-chose').click(function () {
        var type = '{{$type}}';
        var element = '{{$element}}';
        switch (type) {
            case 'single':
                var id = $('#media-setting-id').val();
                var title = $('#media-setting-title').val();
                var caption = $('#media-setting-caption').val();
                var src = $('.media-item.active').find('img').attr('src');
                window.parent.$('#{{$element}}').parent().find('input[type=hidden]').val(src);
                window.parent.$('#{{$element}}').parent().find('img').prop('src',src);
                window.parent.$('.fancybox-close').click();
                break;
            case 'tinymce':
                $('.media-item.active').each(function( index ) {
                    var title = $(this).attr('data-title');
                    var caption = $(this).attr('data-caption');
                    var src = $(this).find('img').attr('src');
                    var str = '<figure class="sudo-media-item">';
                    str += '<img src="'+src+'" alt="'+title+'">';
                    if(caption != '') {
                        str += '<figcaption>'+caption+'</figcaption>';
                    }
                    str += '</figure>';
                    window.parent.tinyMCE.get('{{$element}}').execCommand("mceInsertContent",false,str);
                });
                window.parent.$('.fancybox-close').click();
                break;
            case 'slide':
                $('.media-item.active').each(function( index ) {
                    var title = $(this).attr('data-title');
                    var caption = $(this).attr('data-caption');
                    var src = $(this).find('img').attr('src');
                    var str = '';
                    str += '<div class="result_image_item"><input type="hidden" name="{{$element}}[]" value="'+src+'">'+
                        '<img src="'+src+'" alt="Không có ảnh">'+
                        '<a href="javascript:;" class="del_img" onclick="return media_remove_item(this);"><i class="fa fa-times"></i></a></div>';
                    window.parent.$('#{{$element}} .result_image').append(str);
                    window.parent.$('#{{$element}} .result_image').sortable();
                });
                window.parent.$('.fancybox-close').click();
                break;
        }
    });
</script>
@if($selected_ids != '0')
    @php
        $selected_array = explode(',',$selected_ids);
    @endphp
    <script>
        //nếu có selected thì click vào media-item để load thông tin vào form cập nhật thông tin
        $(document).ready(function() {
            var selected = $('#media-item-{{$selected_array[0]}}');
            selected.addClass('active');
            var id = selected.attr('data-id');
            var user = selected.attr('data-user');
            var name = selected.attr('data-name');
            var title = selected.attr('data-title');
            var caption = selected.attr('data-caption');
            var src = selected.find('img').attr('src');
            var time = selected.attr('data-time');
            var html = '<img src="'+src+'"/>';
            html += '<p style="padding: 5px 0;font-size: 1.2em;"><b>Tên ảnh: '+name+'</b></p>';
            html += '<p>Đăng lúc: '+time+'</p>';
            $('.media-info-img').html(html);
            $('.media-info-form').find('#media-setting-id').val(id);
            $('.media-info-form').find('#media-setting-title').val(title);
            $('.media-info-form').find('#media-setting-caption').val(caption);
            $('.media-info-form').css('display','block');
            $('#media-chose').prop('disabled',false);
        });
    </script>
@endif
</body>
</html>
