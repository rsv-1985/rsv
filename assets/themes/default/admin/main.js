$(document).ready(function(){
    $(".confirm").click(function(){
        if (confirm($(this).attr('data-confirm'))) {
            return true;
        } else {
            return false;
        }
    });
    CKEDITOR.replaceClass = 'textarea';
    CKEDITOR.config.allowedContent = true;
});
