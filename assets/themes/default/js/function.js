$(document).ready(function(){
    $('[rel="tooltip"]').tooltip();

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
                console.log(json);
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
                console.log(json);
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
                console.log(json);
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
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                $(".search_result").empty();
                $("#search_brand_list").empty();
                $("#search_query").text(json['search_query']);
                if(json['brand'].length > 0){
                    var html = '';
                    $.each(json['brand'], function( index, brand ) {
                        html += '<a href="#" onclick="get_search(\''+brand['ID_art']+'\',\''+brand['brand']+'\',\''+brand['sku']+'\')" class="list-group-item">'+brand['brand']+'<br><small>'+brand['name']+'</small></a>';
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
            console.log(json);
            if(json['success']){
                $(".vin_request").trigger('reset');
                $(".alert-success").append(json['success']).fadeIn();
            } else {
                $(".alert-danger").append(json['error']).fadeIn();
            }
        }
    });
}

function add_cart(data, rowid, event){
    event.preventDefault();
    $.ajax({
        url: '/ajax/add_cart',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(json){
            if(json['success']){
                $(".product-count").html(json['product_count']).show();
                $(".cart-amunt").html(json['cart_amunt']);
                $("#"+rowid).fadeIn();
            }else{
                alert(json['error']);
            }
        }
    });
}

function get_search(ID_art, brand, sku){
    $.ajax({
        url: '/ajax/get_search',
        method: 'GET',
        data: {ID_art: ID_art, brand:brand, sku:sku},
        beforeSend: function(){
            $(".search_result").html('<img src="/assets/themes/default/img/loading.gif"/>');
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

