$(document).ready(function(){
    $("[name='customer_id']").autocomplete({
        source: '/autoxadmin/customer/search'
    });
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

function addInvoiceByOrder(order_id) {
    $.ajax({
        url: '/autoxadmin/invoice/add',
        data: {type:'order',data:order_id},
        method: 'post',
        success: function (response) {
            alert(response);
            location.reload();
        }
    })
}

function addInvoiceByItem(product_id) {
    $.ajax({
        url: '/autoxadmin/invoice/add',
        data: {type:'item',data:product_id},
        method: 'post',
        success: function (response) {
            alert(response);
            location.reload();
        }
    })
}
