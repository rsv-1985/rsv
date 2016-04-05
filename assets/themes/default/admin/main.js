$(document).ready(function(){
    console.log('ready!');
    $(".confirm").click(function(){
        if (confirm("")) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    });
    CKEDITOR.replaceClass = 'textarea';
});
