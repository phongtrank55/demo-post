/**
 *
 * @param view tab hiểu thi mặc định: add | list
 * @param type kiểu chèn vào trường image hay editor: single | tinymce
 * @param element id thẻ html chèn giá trị
 * @param text Nội dụng text của button chèn ảnh
 */
function media_popup(view,type,element,text) {
    text = text || 'Chèn ảnh vào';
    element = element || 'image';
    type = type || 'single';
    view = view || 'add';
    var link = '/media/library?view='+view+'&type='+type+'&element='+element+'&text='+text;
    $.fancybox({
        helpers:{
            overlay : {
                css : {
                    "background" : "rgba(0, 0, 0, .9)",
                }
            },
        },
        type   : "iframe",
        href   : link,
        autoSize	: false,
        width  : '80%',
        height : '80%'

    });
}
