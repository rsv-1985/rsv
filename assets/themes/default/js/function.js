$(document).ready(function(){
    $("#button_search,#search_brand").click(function(e){
       if($("#search_input").val().length){
           $("body").css('opacity','0.5').append('<img id="loading" src="/assets/themes/default/img/loading.gif"/>');
       }
    });
    $('[rel="tooltip"]').tooltip();

    $("a[href='#top']").click(function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(".container").offset().top
        }, 1000);
    });

    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 200) {
            $("a[href='#top']").fadeIn();
            $('.search').addClass('search_fixed');
        } else {
            $("a[href='#top']").fadeOut();
            $('.search').removeClass('search_fixed');
        }
    });

    $(".vin_request").submit(function(event){
        send_request(event);
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

function catalog_search(ID_art, sku, brand, event){
    event.preventDefault();
    $("#search_modal").modal();
    get_search(ID_art, brand, sku);
}

function tecdoc_info(sku, brand, blockInfo){
    $("#popover").empty();
    $.ajax({
        url: '/ajax/get_tecdoc_info',
        method: 'POST',
        data: {sku:sku, brand:brand},
        success: function(json){
            if(blockInfo){
                $("."+blockInfo).html(json['html']);
            }else{
                $("#popover").html(json['html']).fadeIn('slow');
            }

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

function getSearchBrand(search){
    if(search.length >=3){
        $.ajax({
            url:'/ajax/get_brands',
            data:{search:search},
            method:'post',
            dataType: "json",
            success: function(json){
                if(json['brands']){
                    console.log(json['brands']);
                    var html = '';
                    $(json['brands']).each(function(index, brand){
                        html += '<li onclick="location.href=\'/search?search='+brand['sku']+'&ID_art='+brand['ID_art']+'&brand='+brand['brand']+'\'"><img src="'+brand['image']+'" width="50"/> '+brand['brand']+' <small>'+brand['name']+'</small></li>';
                    })
                    $("#search_brand").html(html).show();
                }
            }
        });
    }
}
