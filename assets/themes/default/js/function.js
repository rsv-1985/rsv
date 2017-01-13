$(document).ready(function(){
    $('body').fadeIn('slow');
    $('[rel="tooltip"]').tooltip();

    $("a[href='#top']").click(function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(".container").offset().top
        }, 1000);
    });

    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 800) {
            $("a[href='#top']").fadeIn();
        } else {
            $("a[href='#top']").fadeOut();
        }
    });

    $(".vin_request").submit(function(event){
        send_request(event);
    });

    $('.filters').click(function(){
        var countChecked = 0;
        $(".filters-item").hide();
        $(".filters").each(function () {
            if($(this).prop('checked')){
                countChecked++;
                $("."+$(this).val()).show();
            }
        });
        if(countChecked == 0){
            $(".filters-item").show();
        }
    });
    

    $("#newsletter").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#call_back_form").submit(function (e) {
       e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#login_form").submit(function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $(".search_form").submit(function(event){
        event.preventDefault();
        $("#popover").empty();
        window.history.replaceState(null, '', '/search?sku='+$("#search_input").val());
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['search_new_window']){
                    location.reload();
                    return false;
                }
                $(".search_result").empty();
                $("#search_brand_list").empty();
                $("#search_query").html(json['search_query']);
                if(json['brand'].length > 0){
                    var html = '';
                    $.each(json['brand'], function( index, brand ) {
                        html += '<a href="#" onclick="get_search(\''+brand['ID_art']+'\',\''+brand['brand']+'\',\''+brand['sku']+'\'); return false" class="list-group-item">'+brand['brand']+'<br><small>'+brand['name']+'</small></a>';
                    });
                    $("#search_brand_list").html(html);
                }else{
                    get_search(false, false, json['search_query']);
                }
                $("#search_modal").modal();
            }
        });
    });
});
function send_request(event){
    event.preventDefault();
    $.ajax({
        url: $('.vin_request').attr('action'),
        method: 'POST',
        data: $('.vin_request').serialize(),
        dataType: 'json',
        success: function(json){
            if(json['success']){
                $(".vin_request").trigger('reset');
                $(".alert-success").append(json['success']).fadeIn();
            } else {
                $(".alert-danger").append(json['error']).fadeIn();
            }
        }
    });
}

function add_cart(data, event){
    event.preventDefault();
    $.ajax({
        url: '/cart/add_cart',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(json){

            if(json['success']){
                $(".product-count").html(json['product_count']).show();
                $(".cart-amunt").html(json['cart_amunt']);
                $("."+json['cartId']).show().css({fontSize:'1px'}).animate({
                    fontSize: '12px',
                }, 400 );
                $("#"+json['cartId']).show().css({fontSize:'1px'}).animate({
                    fontSize: '12px',
                }, 400 );
            }else{
                alert(json['error']);
            }
        }
    });
}

function get_search(ID_art, brand, sku){
    window.history.replaceState(null, '', '/search?sku='+sku+'&ID_art='+ID_art+'&brand='+brand);
    $.ajax({
        url: '/ajax/get_search',
        method: 'GET',
        data: {ID_art: ID_art, brand:brand, sku:sku},

        beforeSend: function(){
            $(".search_result").html('<img style="width: 50px;" src="/assets/themes/default/img/loading.gif"/>');
        },
        success: function(html){
            $(".search_result").html(html);
        }
    });
}

function catalog_search(ID_art, sku, brand, event){
    event.preventDefault();
    $("#search_modal").modal();
    get_search(ID_art, brand, sku);
}

function tecdoc_info(sku, brand){
    $("#popover").empty();
    $.ajax({
        url: '/ajax/get_tecdoc_info',
        method: 'POST',
        data: {sku:sku, brand:brand},
        success: function(json){
            $("#popover").html(json['html']).fadeIn('slow');
        }
    });
}

function imgError(image, width, height) {
    image.onerror = "";
    if(width && height){
        $(image).css('width', width);
        $(image).css('height', height);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&width="+width+"&height="+height;
    }else if(width){
        $(image).css('width', width);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&width="+width;
    }else if(height){
        $(image).css('height', height);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&height="+height;
    }else{
        image.src = "/image?img=/assets/themes/default/img/no_image.png";
    }

    return true;
}

function remove_garage(key,event) {
    event.preventDefault();
    $.ajax({
       url:'/ajax/remove_garage',
        method: 'post',
        data: {key:key},
        success: function(){
            $('.'+key).remove();
        }
    });
}
