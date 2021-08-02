jQuery(document).ready(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (typeof $.datetimepicker !== "undefined") {
        $.datetimepicker.setLocale('vi');
        $('.datepicker').datetimepicker({
            timepicker:false,
            format:'Y-m-d',
            scrollMonth : false,
            scrollInput : false,
        });
        $('.datetimepicker').datetimepicker({
            format:'Y-m-d H:i:s',
            defaultTime:'23:59:59',
            formatTime:'H:i:s',
            timepicker:false,
            scrollMonth : false,
            scrollInput : false,
        });
    }

    $('.close-alert').on('click',function(){
        $('.alert-success').fadeOut();
    });
    $('.close-error').on('click',function(){
        $('.alert-danger').fadeOut();
    });
    
    $('.select2').select2();

    //Các input có class tags dùng jquery.tagsinput.js
    $('.tags').tagsInput({
        //'autocomplete_url': url_to_autocomplete_api,
        //'autocomplete': { option: value, option: value},
        'height':'auto',
        'width':'100%',
        'interactive':true,
        'defaultText':'thêm',
        //'onAddTag':callback_function,
        //'onRemoveTag':callback_function,
        //'onChange' : callback_function,
        'delimiter': [','],   // Or a string with a single delimiter. Ex: ';'
        'removeWithBackspace' : true,
        'minChars' : 0,
        'maxChars' : 0, // if not provided there is no limit
        'placeholderColor' : '#666666'
    });

    //Lấy slug cho về link preview seo
    if($('.preview_snippet_link').length && $('#slug').length) {
        setInterval(function(){ 
            $slug = $('#slug').val();
            $('.preview_snippet_link span').html($slug);
        }, 2000);
    }
    //Đếm ký tự tiêu đề meta seo
    $(".in_title").on("keyup",function(){
        $(".preview_snippet_title").html($(this).val());
        $(".in_title_count").html($(this).val().length);
    });
    //Đếm ký tự mô tả meta seo
    $(".in_des").on("keyup",function(){
        $(".preview_snippet_des").html($(this).val());
        $(".in_des_count").html($(this).val().length);
    });

    //Click nút ghim
    $('.btn-pins').on('click',function () {
        var type = $(this).attr('data-type');
        var type_id = $(this).closest('.record-data').attr('data-id');
        var place = $(this).attr('data-place');
        var pin_group = $(this).closest('.pins-group');
        var value = pin_group.find('input').val();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {type: type,type_id: type_id,place: place,value: value},
            url: '/admin/ajax/pins',
            success: function (result) {
                pin_group.find('.loading-wrap').remove();
                bootbox.alert(result.message);
            },
            beforeSend: function () {
                pin_group.append('<div class="loading-wrap"><img src="/template-admin/images/loading.gif"></div>')
            }
        });
    });
    //js cho relate field
    //ajax search khi keyup
    $('.relate-search').on('keyup',function () {
        var relateSearch = $(this);
        var relateForm = relateSearch.closest('.form-relate');
        var relateTable = relateSearch.attr('data-table');
        var relateId = relateSearch.attr('data-id');
        var relateName = relateSearch.attr('data-name');
        var inputName = relateForm.attr('data-name');
        var keyword = relateSearch.val();
        var id_array_not_where = [];
        $(this).parent().find('.relate-result').find('.relate-item').children('input[type=hidden]').each(function() {
            value = $(this).val();
            id_array_not_where.push(value);
        });

        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {table: relateTable, id: relateId, name: relateName, key: keyword,id_array_not_where: id_array_not_where},
            url: '/admin/ajax/relate-suggest',
            success: function (result) {
                if(result.status == 1) {
                    var result_data = result.data;
                    var str = '';
                    for(var i = 0; i < result_data.length; i++) {
                        var obj = result_data[i];
                        str += '<li class="relate-suggest-item" data-id="'+obj.id+'">'+obj.name+'</li>';
                    }
                    relateForm.find('.relate-suggest').html(str);
                }else {
                    relateForm.find('.relate-suggest').html(result.message);
                }
            }
        });
    });
    //click chọn từ suggest
    $('.form-relate').on('click','.relate-suggest-item',function () {
        var relateForm = $(this).closest('.form-relate');
        var id = $(this).attr('data-id');
        var name = $(this).html();
        var inputName = relateForm.attr('data-name');
        var str = '';
        str += '<p class="relate-item">';
        str += '<input type="hidden" name="'+inputName+'[]" value="'+id+'">';
        str += name;
        str += '<a href="javascript:;" class="relate-item-remove"><i class="fa fa-times"></i></a>';
        str += '</p>';
        relateForm.find('.relate-result').append(str);
        $('.relate-suggest').html('');
    });
    //click nút remove item relate
    $('.form-relate').on('click','.relate-item-remove',function () {
        $(this).closest('.relate-item').remove();
    });

    $('body').on('click', '*[data-run_ecommerce]', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: {},
            url: '/admin/ajax/command_ecommerces',
            success: function (result) {
                bootbox.alert('Cập nhật thành công');
            }
        });
    });
});
function validForm(form,list){
    var elements = list.split(',');
    var valid = true;
    var check_focus = true;
    for(i in elements){
        if($('#'+elements[i]).hasClass('multi-check')) {//valid cho kiểu multicheck
            if($('input[name="'+elements[i]+'[]"]:checked').length == 0) {
                $('#'+elements[i]).css({border:'1px solid #ff0000'});
                valid = false;
            }else {
                $('#'+elements[i]).css({border:'1px solid #ccc'});
            }
        }
        else if($('#'+elements[i]).hasClass('slide-check')) {//valid cho kiểu slide
            if($('#'+elements[i]).find('.result_image_item').length == 0) {
                $('#'+elements[i]).css({border:'1px solid #ff0000'});
                valid = false;
            }else {
                $('#'+elements[i]).css({border:'1px solid #ccc'});
            }
        }
        else if($('#'+elements[i]).hasClass('relate-search')) {//valid cho kiểu relate
            if($('#'+elements[i]).closest('.form-relate').find('.relate-item').length == 0) {
                $('#'+elements[i]).css({border:'1px solid #ff0000'});
                valid = false;
            }else {
                $('#'+elements[i]).css({border:'1px solid #ccc'});
            }
        }
        else if($('#'+elements[i]).hasClass('select2')) {
            value = $('#'+elements[i]).val();
            if (value == '') {
                $('#'+elements[i]).parent().find('.select2-container--default').find('.select2-selection--single').css('border', '1px solid #ff0000');
                valid = false;
            } else if (value == null) {
                $('#'+elements[i]).parent().find('.select2-container--default').find('.select2-selection--single').css('border', '1px solid #ff0000');
                valid = false;
            } else {
                $('#'+elements[i]).parent().find('.select2-container--default').find('.select2-selection--single').css('border', '1px solid #ccc');
            }
        }
        else if($('#'+elements[i]).hasClass('suggest-input')) {
            value = $('#'+elements[i]).val();
            if (value == '') {
                $('#'+elements[i]).parent().find('.suggest-search').css('border', '1px solid #ff0000');
                valid = false;
            } else {
                $('#'+elements[i]).parent().find('.suggest-search').css('border', '1px solid #ccc');
            }
        }
        else if($('#'+elements[i]).val().trim() == '') {
            if (check_focus) {
                if($('#'+elements[i]).attr('type') == 'hidden') {//hidden cho input ảnh => border cho div controll
                    $('#'+elements[i]).parent().css({border:'1px solid #ff0000'}).focus();
                }else {
                    $('#'+elements[i]).css({border:'1px solid #ff0000'}).focus();
                    check_focus = false;
                }
            }else {
                if($('#'+elements[i]).attr('type') == 'hidden') {
                    $('#'+elements[i]).parent().css({border:'1px solid #ff0000'});
                }else {
                    $('#'+elements[i]).css({border:'1px solid #ff0000'});
                }
            }
            valid = false;
        }
        else {//nếu đã valid => trả lại trạng thái bình thường cho các element
            if($('#'+elements[i]).attr('type') == 'hidden') {
                $('#'+elements[i]).parent().css({border:'none'});
            }else {
                $('#'+elements[i]).css({border:'1px solid #ccc'});
            }
        }
    }

    if (valid) {
        document.form.submit();
    }else {
        alert('Những trường có dấu * là bắt buộc');
        return false;
    }
}
function media_remove_item(el){
    el.closest('.result_image_item').remove();
}

function check_edit(i) {
    var input = $(i).closest('tr').find('.well input');
    input.prop("checked", true);
    input.parent().removeClass('fa-square-o');
    input.parent().addClass('fa-check-square-o');
}
function check_one(i) {
    if ($(i).prop('checked')) {
        $(i).parent().removeClass('fa-square-o');
        $(i).parent().addClass('fa-check-square-o');
    } else {
        $(i).parent().addClass('fa-square-o');
        $(i).parent().removeClass('fa-check-square-o');
    }
}

function check_all() {
    if ($('#check_all').prop('checked')) {
        $('.well input.check').parent().removeClass('fa-square-o');
        $('.well input.check').parent().addClass('fa-check-square-o');
        $('.well input.check').prop("checked", true);
    } else {
        $('.well input.check').parent().addClass('fa-square-o');
        $('.well input.check').parent().removeClass('fa-check-square-o');
        $('.well input.check').prop("checked", false);
    }
}
function check_all_role(){
    if($('#check_all').prop('checked')){
        $('.well input.check').parent().removeClass('fa-square-o');
        $('.well input.check').parent().addClass('fa-check-square-o');
        $('.well input.check').prop( "checked", true );
    }else{
        $('.well input.check').parent().addClass('fa-square-o');
        $('.well input.check').parent().removeClass('fa-check-square-o');
        $('.well input.check').prop( "checked", false );
    }
}

function delete_one(id,destroy_uri) {
    if (confirm('Bạn muốn xóa vĩnh viễn bản ghi này ?')) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {id: id,_method: 'DELETE'},
            url: destroy_uri,
            success: function (result) {
                if(result.status == 1) {
                    $('#record-'+id).fadeOut('slow');
                }else {
                    bootbox.alert(result.message);
                }
            }
        });
    }
}
function delete_all(table) {
    if (confirm('Bạn muốn xóa vĩnh viễn các bản ghi đã chọn ?')) {
        var ids = [];
        $('tr.record-data').each(function () {
            if ($(this).find('.check').is(":checked")) {
                ids.push(parseInt($(this).attr('data-id')));
            }
        });
        if(ids.length) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {ids: ids,table: table},
                url: '/admin/ajax/delete-all',
                success: function (result) {
                    if(result.status == 1) {
                        $.each(ids, function( index, value ) {
                            $('#record-'+value).fadeOut('slow');
                        });
                    }
                    bootbox.alert(result.message);
                }
            });
        }else {
            bootbox.alert('Vui lòng chọn ít nhất một bản ghi để thực hiện thao tác này');
        }
    }
}
function trash_all(table) {
    if (confirm('Bạn muốn chuyển các bản ghi đã chọn vào thùng rác ?')) {
        var ids = [];
        $('tr.record-data').each(function () {
            if ($(this).find('.check').is(":checked")) {
                ids.push(parseInt($(this).attr('data-id')));
            }
        });
        if(ids.length) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {ids: ids,table: table},
                url: '/admin/ajax/trash-all',
                success: function (result) {
                    if(result.status == 1) {
                        $.each(ids, function( index, value ) {
                            $('#record-'+value).find('select[name=status]').val(3);
                            //Bỏ check input
                            var input = $('#record-'+value).find('.well input');
                            input.checked = false;
                            input.parent().addClass('fa-square-o');
                            input.parent().removeClass('fa-check-square-o');
                        });
                    }
                    bootbox.alert(result.message);
                }
            });
        }else {
            bootbox.alert('Vui lòng chọn ít nhất một bản ghi để thực hiện thao tác này');
        }
    }
}
function deactive_all(table) {
    if (confirm('Bạn muốn chuyển các bản ghi đã chọn về trạng thái không hoạt động ?')) {
        var ids = [];
        $('tr.record-data').each(function () {
            if ($(this).find('.check').is(":checked")) {
                ids.push(parseInt($(this).attr('data-id')));
            }
        });
        if(ids.length) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {ids: ids,table: table},
                url: '/admin/ajax/deactive-all',
                success: function (result) {
                    if(result.status == 1) {
                        $.each(ids, function( index, value ) {
                            $('#record-'+value).find('select[name=status]').val(2);
                            //Bỏ check input
                            var input = $('#record-'+value).find('.well input');
                            input.checked = false;
                            input.parent().addClass('fa-square-o');
                            input.parent().removeClass('fa-check-square-o');
                        });
                    }
                    bootbox.alert(result.message);
                }
            });
        }else {
            bootbox.alert('Vui lòng chọn ít nhất một bản ghi để thực hiện thao tác này');
        }
    }
}

function save_one(id,table) {
    var data = new Object();
    $('#record-'+id).find('.quick-edit').each(function () {
        var attr_name = $(this).attr('name');
        var attr_value = $(this).val();
        data[attr_name] = attr_value;
    });
    if(!$.isEmptyObject(data)) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {id: id,table: table, data: JSON.stringify(data)},
            url: '/admin/ajax/save-one',
            success: function (result) {
                if(result.status == 1) {
                    //Bỏ check input
                    var input = $('#record-'+id).find('.well input');
                    input.checked = false;
                    input.parent().addClass('fa-square-o');
                    input.parent().removeClass('fa-check-square-o');
                }
                bootbox.alert(result.message);
            }
        });
    }
}

function save_all(table) {
    var all_data = [];
    $('.well input.check').each(function () {
        if(this.checked) {
            //console.log($(this).closest('.record-data').attr('data-id'));
            var item_id = $(this).closest('.record-data').attr('data-id');
            var data = new Object();
            data['id'] = item_id;
            $('#record-'+item_id).find('.quick-edit').each(function () {
                var attr_name = $(this).attr('name');
                var attr_value = $(this).val();
                data[attr_name] = attr_value;
            });
            if(!$.isEmptyObject(data)) {
                all_data.push(data);
            }
        }
    });
    //console.log(all_data);
    if(all_data.length) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {table: table, data: JSON.stringify(all_data)},
            url: '/admin/ajax/save-all',
            success: function (result) {
                if(result.status == 1) {

                }
                bootbox.alert(result.message);
            }
        });
    }
}


function format_price(number, decimals, dec_point, thousands_sep) {
    var _decimals = 0;
    var _dec_point = ',';
    var _thousands_sep = '.';
    $.extend(_decimals, decimals);
    $.extend(_dec_point, dec_point);
    $.extend(_thousands_sep, thousands_sep);
    number = (number + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
            .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

function password_generator( len ) {
    var length = (len)?(len):(10);
    var string = "abcdefghijklmnopqrstuvwxyz"; //to upper 
    var numeric = '0123456789';
    var punctuation = '!@#$%^&*()';
    var password = "";
    var character = "";
    var crunch = true;
    while( password.length<length ) {
        entity1 = Math.ceil(string.length * Math.random()*Math.random());
        entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
        entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
        hold = string.charAt( entity1 );
        hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
        character += hold;
        character += numeric.charAt( entity2 );
        character += punctuation.charAt( entity3 );
        password = character;
    }
    password=password.split('').sort(function(){return 0.5-Math.random()}).join('');
    return password.substr(0,len);
}
function password_strength(password){ 
    //initial strength
    var strength = 0    
    if (password.length == 0) {
        return strength;
    }
 
    if (password.match(/[a-z]+/)) {
        strength += 1;
    }
    if (password.match(/[A-Z]+/)) {
        strength += 1;
    }
    if (password.match(/[0-9]+/)) {
        strength += 1;
    }
    if (password.match(/[!@#$%^&*()]+/)) {
        strength += 1;
    }
    if (password.length >= 6) {
        strength += 1;
    }
    return strength;
}

function addTinyMCE(selector_id) {
    id = selector_id.replace('#','');
    tinymce.execCommand('mceRemoveEditor', false, id);
    tinymce.init({
        path_absolute : "/",
        selector:selector_id,
        content_css: '/template-admin/tinymce/js/tinymce/sudo/css-content.css,https://pro.fontawesome.com/releases/v5.12.0/css/all.css',
        branding: false,
        hidden_input: false,
        relative_urls: false,
        convert_urls: false,
        force_p_newlines: true,
        height : 400,
        autosave_ask_before_unload:true,
        autosave_interval:'10s',
        autosave_restore_when_empty:true,
        entity_encoding : "raw",
        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 32px 36px 40px 46px 52px 60px",
        plugins: [
            "textcolor toc",
            "advlist autolink lists link image imagetools charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table autosave contextmenu paste wordcount noneditable"
        ],
        external_plugins : { 
            "fontawesome":"/template-admin/tinymce/src/plugins/fontawesome/plugin.min.js" 
        },
        noneditable_noneditable_class: 'fa',
        extended_valid_elements: 'span[*]',
        valid_elements : '*[*]',
        wordcount_countregex: /[\w\u2019\x27\-\u00C0-\u1FFF]+/g,
        language: "vi_VN",
        autosave_retention:"30m",
        autosave_prefix: "tinymce-autosave-{path}{query}-{id}-",
        wordcount_cleanregex: /[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g,
        toolbar: "insertfile undo redo table sudomedia charmap | styleselect | sizeselect | bold italic underline strikethrough | fontselect |  fontsizeselect | forecolor " +
            "backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent " +
            "indent | link unlink fullscreen restoredraft filemanager | toc | chanh_blockquote chanh_notification chanh_product_2 chanh_slide chanh_price_trade fontawesome",
        setup: function (editor) {
            editor.addButton('sudomedia', {
                text: 'Tải ảnh',
                icon: 'image',
                label:'Nhúng ảnh vào nội dung',
                onclick: function () {
                    selector_id = selector_id.replace('#','');
                    media_popup("add","tinymce",selector_id,"Chèn ảnh vào bài viết");
                    }
                }
            );
            editor.addButton('chanh_blockquote', {
                text: 'Trích dẫn',
                icon: false,
                stateSelector: '.chanh-blockquote',
                onclick: function() {
                    state = this.active();
                    if (state) {
                        row = tinymce.activeEditor.selection.getNode().closest('.chanh-blockquote');
                        content = $(row).find('.chanh-blockquote__text').html();
                    } else {
                        content = 'Đây là nội dung';
                    }
                    editor.windowManager.open({
                        title: 'Nội dung',
                        body: [
                            { 
                                type: 'textbox',
                                multiline: true,
                                minWidth: 500,
                                minHeight: 100,
                                name: 'content',
                                label: 'Nội dụng',
                                value: content,
                                placeholder: 'Chấp nhận html',
                            },
                            { 
                                type: 'container',
                                label: 'Ảnh Box',
                                html: '<img src="/template-admin/tinymce/js/tinymce/sudo/blockquote.png" style="width: 100%; height: 70px; object-fit: contain;">',
                                minHeight: 70,
                            },
                            { 
                                type: 'container',
                                label: 'Lưu ý',
                                html: 'Để xóa thẻ nếu để <b>Nội dung</b> là trống. <b>KHÔNG KHUYẾN KHÍCH</b> xóa tại <b>phần hiển thị</b>.'
                            },
                        ],
                        onsubmit: function(e) {
                            data = e.data;
                            html = `
                                <div class="chanh-blockquote">
                                    <div class="chanh-blockquote__text">${data.content}</div>
                                </div>
                                <p></p>
                            `;
                            row = tinymce.activeEditor.selection.getNode().closest('.chanh-blockquote');
                            if(row) { $(row).remove(); }
                            if (data.content != '') {
                                editor.insertContent(html);
                            }
                        }
                    })
                }
            });
            editor.addButton('chanh_notification', {
                text: 'Thông báo',
                icon: false,
                stateSelector: '.chanh-notification',
                onclick: function() {
                    state = this.active();
                    if (state) {
                        row = tinymce.activeEditor.selection.getNode().closest('.chanh-notification');
                        content = $(row).find('.chanh-notification__text').html();
                    } else {
                        content = 'Đây là nội dung thông báo';
                    }
                    editor.windowManager.open({
                        title: 'Nội dung',
                        body: [
                            {
                                type: 'textbox',
                                multiline: true,
                                minWidth: 500,
                                minHeight: 100,
                                name: 'content',
                                label: 'Nội dụng',
                                value: content
                            },
                            { 
                                type: 'container',
                                label: 'Ảnh Box',
                                html: '<img src="/template-admin/tinymce/js/tinymce/sudo/notification.png" style="width: 100%; height: 70px; object-fit: contain;">',
                                minHeight: 70,
                            },
                            { 
                                type: 'container',
                                label: 'Lưu ý',
                                html: 'Để xóa thẻ nếu để <b>Nội dung</b> là trống. <b>KHÔNG KHUYẾN KHÍCH</b> xóa tại <b>phần hiển thị</b>.'
                            },
                        ],
                        onsubmit: function(e) {
                            data = e.data;
                            html = `
                                <div class="chanh-notification">
                                    <div class="chanh-notification__text">${data.content}</div>
                                </div>
                                <p></p>
                            `;
                            row = tinymce.activeEditor.selection.getNode().closest('.chanh-notification');
                            if(row) { $(row).remove(); }
                            if (data.content != '') {
                                editor.insertContent(html);
                            }
                        }
                    })
                }
            });
            editor.addButton('chanh_product_2', {
                text: 'Sản phẩm',
                icon: false,
                stateSelector: '.post-products-2',
                onclick: function() {
                    state = this.active();
                    console.log(state);
                    if (state) {
                        row = tinymce.activeEditor.selection.getNode().closest('.post-products-2');
                        label = $(row).find('.post-products-2__label').html();
                        image = $(row).find('img').attr('src');
                        title = $(row).find('.post-products-2__info-title').html();
                        subtitle = $(row).find('.post-products-2__info-subtitle').html();
                        desc = $(row).find('.post-products-2__info-desc').html();
                        note = $(row).find('.post-products-2__info-note').find('.text').html();
                        text_1 = $(row).find('.post-products-2__info-link').find('a:nth(0)').find('.text').html();
                        text_2 = $(row).find('.post-products-2__info-link').find('a:nth(1)').find('.text').html();
                        text_3 = $(row).find('.post-products-2__info-link').find('a:nth(2)').find('.text').html();
                        link_1 = $(row).find('.post-products-2__info-link').find('a:nth(0)').attr('href');
                        link_2 = $(row).find('.post-products-2__info-link').find('a:nth(1)').attr('href');
                        link_3 = $(row).find('.post-products-2__info-link').find('a:nth(2)').attr('href');
                    } else {
                        label = '';
                        image = '';
                        title = '';
                        subtitle = '';
                        desc = '';
                        note = '';
                        text_1 = '';
                        text_2 = '';
                        text_3 = '';
                        link_1 = '';
                        link_2 = '';
                        link_3 = '';
                    }
                    editor.windowManager.open({
                        title: 'Nội dung',
                        autoScroll: true,
                        width: 700,
                        height: 500,
                        body: [
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'image',
                                label: 'Ảnh',
                                value: image,
                                placeholder: 'Link ảnh',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'label',
                                label: 'Text đề xuất',
                                value: label,
                                placeholder: 'Đề xuất',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'title',
                                label: 'Tiêu đề',
                                value: title,
                                placeholder: 'Tiêu đề',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'subtitle',
                                label: 'Tiêu đề con',
                                value: subtitle,
                                placeholder: 'Tiêu đề con',
                            },
                            { 
                                type: 'textbox',
                                multiline: true,
                                minWidth: 500,
                                minHeight: 50,
                                name: 'desc',
                                label: 'Mô tả',
                                value: desc,
                                placeholder: 'Mô tả',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'note',
                                label: 'Ghi chú',
                                value: note,
                                placeholder: 'Ghi chú',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'text_1',
                                label: 'Đường dẫn 1',
                                value: text_1,
                                placeholder: 'Text',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'link_1',
                                label: ' ',
                                value: link_1,
                                placeholder: 'Link',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'text_2',
                                label: 'Đường dẫn 2',
                                value: text_2,
                                placeholder: 'Text',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'link_2',
                                label: ' ',
                                value: link_2,
                                placeholder: 'Link',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'text_3',
                                label: 'Đường dẫn 3',
                                value: text_3,
                                placeholder: 'Text',
                            },
                            { 
                                type: 'textbox',
                                minWidth: 500,
                                name: 'link_3',
                                label: ' ',
                                value: link_3,
                                placeholder: 'Link',
                            },
                            { 
                                type: 'container',
                                label: 'Ảnh Box',
                                html: '<img src="/template-admin/tinymce/js/tinymce/sudo/post_product_2.png" style="width: 100%; height: 200px; object-fit: contain;">',
                                minHeight: 200,
                            },
                            { 
                                type: 'container',
                                label: 'Lưu ý',
                                html: 'Để xóa thẻ nếu để <b>Tiêu đề</b> là trống. <b>KHÔNG KHUYẾN KHÍCH</b> xóa tại <b>phần hiển thị</b>.'
                            },
                        ],
                        onsubmit: function(e) {
                            data = e.data;
                            console.log(data);
                            html = `
                                <div class="post-products-2">
                                    <div class="post-products-2__label">${data.label}</div>
                                    <div class="post-products-2__image">
                                        <div class="square">
                                            <img src="${data.image}" alt="">
                                        </div>
                                    </div>
                                    <div class="post-products-2__info">
                                        <div class="post-products-2__info-title">${data.title}</div>
                                        <div class="post-products-2__info-subtitle">${data.subtitle}</div>
                                        <div class="post-products-2__info-desc">${data.desc}</div>
                                        <div class="post-products-2__info-link">
                            `;
                            if (data.link_1 && data.text_1) {
                                html += `<a href="${data.link_1}" target="_blank"><i class="fa fa-external-link"></i><span class="text">${data.text_1}</span></a>`;
                            }
                            if (data.link_2 && data.text_2) {
                                html += `<a href="${data.link_2}" target="_blank"><i class="fa fa-external-link"></i><span class="text">${data.text_2}</span></a>`;
                            }
                            if (data.link_3 && data.text_3) {
                                html += `<a href="${data.link_3}" target="_blank"><i class="fa fa-external-link"></i><span class="text">${data.text_3}</span></a>`;
                            }
                            html += `
                                        </div>
                                        <div class="post-products-2__info-note">
                                            <i class="fa fa-info-circle"></i>
                                            <span class="text">${data.note}</span>
                                        </div>
                                    </div>
                                </div>
                                <p></p>
                            `;
                            row = tinymce.activeEditor.selection.getNode().closest('.post-products-2');
                            if(row) { $(row).remove(); }
                            if (data.title != '') {
                                editor.insertContent(html);
                            }
                        }
                    })
                }
            });
            editor.addButton('chanh_slide', {
                text: 'Slide sản phẩm',
                icon: false,
                stateSelector: '.chanh-slide',
                onclick: function() {
                    state = this.active();
                    title = '';
                    product_name = [];
                    product_image = [];
                    product_price = [];
                    product_price_old = [];
                    product_link = [];
                    if (state) {
                        row = tinymce.activeEditor.selection.getNode().closest('.chanh-slide');
                        title = $(row).find('.chanh-slide__title').text();
                        $.each($(row).find('.item'), function() {
                            product_name.push($(this).find('.item-info__title').text());
                            product_image.push($(this).find('img').attr('src'));
                            product_price.push($(this).find('.item-info__price--big').text());
                            product_price_old.push($(this).find('.item-info__price--small').text());
                            product_link.push($(this).find('a:gt(0)').attr('href'));
                        });
                    } else {
                        
                    }
                    // Trường hiển thị
                    $field_setup = [];
                    $field_setup.push({ 
                        type: 'container',
                        label: 'Ảnh Box',
                        html: '<img src="/template-admin/tinymce/js/tinymce/sudo/chanhtuoi_slide.PNG" style="width: 100%; height: 70px; object-fit: contain;">',
                        minHeight: 70,
                    });
                    $field_setup.push({ 
                        type: 'container',
                        label: 'Lưu ý',
                        html: 'Xóa thẻ nếu để <b>Tiêu đề</b> là trống. <b>KHÔNG KHUYẾN KHÍCH</b> xóa tại <b>phần hiển thị</b>.'
                    });
                    $field_setup.push({ 
                        type: 'textbox',
                        minWidth: 500,
                        name: 'title',
                        label: 'Tiêu đề',
                        value: (title) ? title : '',
                        placeholder: 'Tiêu đề (Xóa thẻ nếu như tiêu đề trống)',
                    });
                    for (i = 1; i < 11; i++) {
                        $field_setup.push({ 
                            type: 'textbox',
                            minWidth: 500,
                            name: 'product_name_'+i,
                            label: 'Sản phẩm '+i,
                            value: (product_name[i-1]) ? product_name[i-1] : '',
                            placeholder: 'Tên sản phẩm (Nếu trống sẽ tự động bỏ các giá trị của sp)',
                        });
                        $field_setup.push({ 
                            type: 'textbox',
                            minWidth: 500,
                            name: 'product_image_'+i,
                            label: ' ',
                            value: (product_image[i-1]) ? product_image[i-1] : '',
                            placeholder: 'Ảnh sản phẩm',
                        });
                        $field_setup.push({ 
                            type: 'textbox',
                            minWidth: 500,
                            name: 'product_price_'+i,
                            label: ' ',
                            value: (product_price[i-1]) ? product_price[i-1].replace(/\D/g,'') : '',
                            placeholder: 'Giá',
                        });
                        $field_setup.push({ 
                            type: 'textbox',
                            minWidth: 500,
                            name: 'product_price_old_'+i,
                            label: ' ',
                            value: (product_price_old[i-1]) ? product_price_old[i-1].replace(/\D/g,'') : '',
                            placeholder: 'Giá trước khuyến mãi',
                        });
                        $field_setup.push({ 
                            type: 'textbox',
                            minWidth: 500,
                            name: 'product_link_'+i,
                            label: ' ',
                            value: (product_link[i-1]) ? product_link[i-1] : '',
                            placeholder: 'Link sản phẩm',
                        });
                    }
                    editor.windowManager.open({
                        title: 'Nội dung',
                        autoScroll: true,
                        width: 700,
                        height: 500,
                        body: $field_setup,
                        onsubmit: function(e) {
                            data = e.data;
                            row = tinymce.activeEditor.selection.getNode().closest('.chanh-slide');
                            if (data.title == '') {
                                if(row) { $(row).remove(); }
                            } else {
                                item = ``;
                                for (i = 1; i < 11; i++) {
                                    eval("var product_name_"+i+'= '+'data.product_name_'+i);
                                    eval("var product_image_"+i+'= '+'data.product_image_'+i);
                                    eval("var product_price_"+i+'= '+'data.product_price_'+i);
                                    eval("var product_price_old_"+i+'= '+'data.product_price_old_'+i);
                                    eval("var product_link_"+i+'= '+'data.product_link_'+i);
                                    product_name = eval('product_name_'+i);
                                    product_image = eval('product_image_'+i);
                                    product_price = eval('product_price_'+i);
                                    product_price_old = eval('product_price_old_'+i);
                                    product_link = eval('product_link_'+i);
                                    // Giảm theo %
                                    price_discount = '';
                                    if (product_price_old != 0 && product_price_old != '') {
                                        price_discount = '-'+parseInt(((product_price_old-product_price)/product_price_old)*100)+'%';
                                        product_price_old = format_price(product_price_old);
                                    } else {
                                        product_price_old = '';
                                    }
                                    if (price_discount == '' || price_discount == '-0%') {
                                        price_discount = '';
                                    }
                                    if (product_name != '') {
                                        item += `
                                            <div class="item">
                                                <div class="item-image square">
                                                    <a href="${product_link}" target="_blank"><img src="${product_image}" alt=""></a>
                                                </div>
                                                <div class="item-info">
                                                    <div class="item-info__title"><a href="${product_link}" target="_blank">${product_name}</a></div>
                                                    <div class="item-info__price">
                                                        <div class="item-info__price--big">${format_price(product_price)}</div>
                                                        <div class="item-info__price--small">${product_price_old}</div>
                                                        <div class="item-info__price__discount">${price_discount}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    }
                                }
                                if (item != '') {
                                    html = `
                                        <div class="chanh-slide">
                                            <div class="chanh-slide__title">${data.title}</div>
                                            <div class="chanh-slide__list owl-carousel">
                                                ${item}
                                            </div>
                                        </div>
                                        <p></p>
                                    `;
                                    if(row) { $(row).remove(); }
                                    if (data.content != '') {
                                        editor.insertContent(html);
                                    }
                                } else {
                                    alert('Không xác định được nội dung slide!');
                                }
                            }
                        }
                    })
                }
            });
            editor.addButton('chanh_price_trade', {
                text: 'Ưu đãi tốt nhất',
                icon: false,
                stateSelector: '.chanh_price_trade',
                onclick: function() {
                    state = this.active();
                    note_box = '';
                    data_fields = [
                        { label: 'Lazada', name: 'lazada', name_value: '', price: '', link: '', disabled_name: true, },
                        { label: 'Shopee', name: 'shopee', name_value: '', price: '', link: '', disabled_name: true, },
                        { label: 'Tiki', name: 'tiki', name_value: '', price: '', link: '', disabled_name: true, },
                        { label: 'Sendo', name: 'sendo', name_value: '', price: '', link: '', disabled_name: true, },
                        { label: 'Sàn khác 1', name: 'san_khac_1', name_value: '', price: '', link: '', disabled_name: false, },
                        { label: 'Sàn khác 2', name: 'san_khac_2', name_value: '', price: '', link: '', disabled_name: false, }
                    ];
                    if (state) {
                        row = tinymce.activeEditor.selection.getNode().closest('.chanh_price_trade');
                        note_box = $(row).find('.review-top__note').find('.text-note').html();
                        $.each($(row).find('.item'), function() {
                            stt = $(this).data('stt');
                            data_fields[stt].name_value = $(this).find('a').data('name');
                            data_fields[stt].link = $(this).find('a').attr('href');
                            data_fields[stt].price = $(this).find('.price-detail').html().replace(/\D/g,'');
                        });
                    } else {}
        
                    $field_trade = [];
                    $field_trade.push({ 
                        type: 'container',
                        label: 'Ảnh hiển thị box:',
                        html: '<img src="https://cdn.chanhtuoi.com/uploads/2021/03/box-uu-dai.png" style="width: 100%; height: 150px; object-fit: contain;">'
                    });
                    $field_trade.push({ 
                        type: 'container',
                        label: 'Lưu ý:',
                        html: 'Để chỉnh sửa vui lòng chọn vùng nhập Ưu đãi tốt nhất và click vào nút <b>Ưu đãi tốt nhất</b> trên thanh công cụ.'
                    });
                    $field_trade.push({ 
                        type: 'container',
                        label: ' ',
                        html: 'Xóa thẻ nếu để <b>Giá</b> là trống. Xóa Box khi toàn bộ <b>Giá</b> để trống.'
                    });
                    $field_trade.push({ 
                        type: 'container',
                        label: ' ',
                        html: 'Tên 4 sàn mặc định sẽ điền: Lazada, Shopee, Tiki, Sendo; Tên các sàn khác sẽ <b>viết hoa</b> chữ cái đầu tiên và viết liền không dấu.'+
                        '<p>VD: Nguyễn Kim: Nguyenkim</p>'
                    });
        
                    $.each(data_fields, function(index, item) {
                        $field_trade.push({
                            type: 'textbox',
                            minWidth: 500,
                            name: 'trade_name_'+item.name,
                            label: item.label,
                            value: (index < 4) ? item.label : item.name_value,
                            placeholder: 'VD: '+item.label,
                            disabled: item.disabled_name,
                        });
                        $field_trade.push({
                            type: 'textbox',
                            minWidth: 500,
                            name: 'trade_price_'+item.name,
                            label: ' ',
                            value: item.price,
                            placeholder: 'Giá sản phẩm. VD: 100000',
                        });
                        $field_trade.push({
                            type: 'textbox',
                            minWidth: 500,
                            name: 'trade_link_'+item.name,
                            label: ' ',
                            value: item.link,
                            placeholder: 'Link sản phẩm',
                        });
                    });
                    $field_trade.push({
                        type: 'textbox',
                        minWidth: 500,
                        name: 'note_box',
                        label: 'Ghi chú',
                        value: note_box,
                    });
                    editor.windowManager.open({
                        title: 'Ưu đãi tốt nhất',
                        autoScroll: true,
                        width: 1000,
                        height: 500,
                        body: $field_trade,
                        onsubmit: function(e) {
                            data_value = e.data;
                            row = tinymce.activeEditor.selection.getNode().closest('.review-top');
                            item_html = ``;
                            $.each(data_fields, function(index, item) {
                                name_value = eval('data_value.trade_name_'+item.name);
                                price = eval('data_value.trade_price_'+item.name);
                                link = eval('data_value.trade_link_'+item.name);
                                if(price != '') {
                                    item_html +=`
                                        <div class="item" data-stt="${index}">
                                            <p class="price-detail">${formatPrice(price, 'Miễn phí', '')} VNĐ</p>
                                            <a href="${link}" data-name="${name_value}" rel="nofollow" target="_blank">Xem tại ${name_value}</a>
                                        </div>
                                    `;
                                }
                            });
                            if(item_html != '' ) {
                                html=`
                                    <div class="review-top chanh_price_trade">
                                        <div class="review-top__price">
                                            <div class="left">Ưu đãi tốt nhất</div>
                                            <div class="right">${item_html}</div>
                                        </div>
                                        <div class="review-top__note">
                                            <div class="icon-note"><i class="fa fa-info-circle" aria-hidden="true"></i></div>
                                            <div class="conent-text text-note">${data_value.note_box}</div>
                                        </div>
                                    </div>
                                    <p></p>
                                `;
                                if(row) { $(row).remove(); }
                                editor.insertContent(html);
                            } else {
                                if( confirm('Bạn có đồng ý xóa box này không?') ) {
                                    if(row) { $(row).remove(); }
                                }
                            }
                        }
                    });
                }
            });
        },
        file_picker_callback: function() {
            selector_id.replace('#','');
            media_popup("add","tinymce",selector_id,"Chèn ảnh vào bài viết");
        }
    });
}

function action_option_table(template,actionBtn) {
    $('body').on('click',actionBtn,function(e) {
        e.preventDefault();
        $(this).closest('.module_table_option').children('tbody').append(`
            <tr draggable="true">
                ${template}
                <td><button type="button" class="btn-delete delete_option"><i class="fa fa-trash-o"></i></button></td>
            </tr>
        `);
        $('.module_table_option tbody').sortable();
    });
    $('body').on('click','.delete_option',function(e) {
        e.preventDefault();
        $(this).parent('td').parent('tr').remove();
    });
}

// Chuyển chuỗi sang dạng slug
function convertToSlug(str) {
    //Đổi chữ hoa thành chữ thường
    slug = str.toLowerCase();
    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    //In slug ra textbox có id “slug”
    return slug
}

// Trả về true nếu rỗng
function check_empty(value) {
    if (value == null) { 
        return true;
    } else if (value == 'null') { 
        return true;
    } else if (value == undefined) { 
        return true;
    } else if (value == '') { 
        return true;
    } else {
        return false;
    }
}

// Định dạng giá
function format_price(number) {
    if (number == 0) {
        return '0đ';
    } else {
        number += '';
        x = number.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        number = x1 + x2 +"đ";
        return number;
    }
}

// Định dạng giá
function formatPrice(number, text_default = 'Miễn phí', unit = 'đ') {
    number = number.replace(/\D/g,'');
    if (number == '') {
        return '';
    } else if (number == 0 && text_default != '') {
        return text_default;
    } else {
        number += '';
        x = number.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        number = x1 + x2 + unit;
        return number;
    }
}