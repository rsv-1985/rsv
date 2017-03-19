<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<style>
    #sto-order{
        border: 1px solid #f6f6f6;
        width: 100%;
        height: 47px;
        padding: 10px;
    }
    .label.active {
        background: #31708f;
        color: #ffffff;
        border: 1px solid #31708f;
    }
    .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {
        border: 1px solid #31708f;
        background: #31708f;
        font-weight: normal;
        color: #ffffff;
    }
    .panel-body {
        padding: 20px !important;
        padding-top: 15px !important;
        border: 1px solid whitesmoke;
    }
    .ui-widget.ui-widget-content {
        border: none;
        width: 100%;
    }
    .ui-widget-header {
        border: 0px;
        background: none;
        color: #333333;
        font-weight: bold;
    }
</style>
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1;?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <?php echo form_open();?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $description;?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Услуги и данные о автомобиле</div>
                        <div class="panel-body">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <select name="service" class="form-control" required onchange="$('#manufacturer').removeAttr('disabled')">
                                            <option value="">Услуга</option>
                                            <?php foreach ($services as $service){?>
                                                <option value="<?php echo $service;?>" <?php echo set_select('service',$service);?>><?php echo $service;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="manufacturer" value="">
                                        <select id="manufacturer" class="form-control" disabled required onchange="get_model($(this).val())">
                                            <option value="">Выберите производителя</option>
                                            <?php foreach ($manufacturers as $manufacturer){?>
                                                <option value="<?php echo $manufacturer['ID_mfa'];?>"><?php echo $manufacturer['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="model" value="">
                                        <select id="model" class="form-control" disabled required onchange="get_typ($(this).val())">
                                            <option>Модель</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="typ" value="">
                                        <select id="typ" class="form-control" disabled required onchange="change_typ()">
                                            <option>Модификация</option>
                                        </select>
                                    </div>
                                    <div class="form-group pull-right">
                                       <input type="text" name="vin" class="form-control" placeholder="VIN автомобиля">
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Выберите дату</div>
                        <div class="panel-body" style="min-height: 301px;">
                            <div id="datepicker"></div>
                            <input type="hidden" name="date" value="" required>
                            <script>
                                ( function( factory ) {
                                    if ( typeof define === "function" && define.amd ) {

                                        // AMD. Register as an anonymous module.
                                        define( [ "../widgets/datepicker" ], factory );
                                    } else {

                                        // Browser globals
                                        factory( jQuery.datepicker );
                                    }
                                }( function( datepicker ) {

                                    datepicker.regional.ru = {
                                        closeText: "Закрыть",
                                        prevText: "&#x3C;Пред",
                                        nextText: "След&#x3E;",
                                        currentText: "Сегодня",
                                        monthNames: [ "Январь","Февраль","Март","Апрель","Май","Июнь",
                                            "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь" ],
                                        monthNamesShort: [ "Янв","Фев","Мар","Апр","Май","Июн",
                                            "Июл","Авг","Сен","Окт","Ноя","Дек" ],
                                        dayNames: [ "воскресенье","понедельник","вторник","среда","четверг","пятница","суббота" ],
                                        dayNamesShort: [ "вск","пнд","втр","срд","чтв","птн","сбт" ],
                                        dayNamesMin: [ "Вс","Пн","Вт","Ср","Чт","Пт","Сб" ],
                                        weekHeader: "Нед",
                                        dateFormat: "dd.mm.yy",
                                        firstDay: 1,
                                        isRTL: false,
                                        showMonthAfterYear: false,
                                        yearSuffix: "" };
                                    datepicker.setDefaults( datepicker.regional.ru );

                                    return datepicker.regional.ru;

                                } ) );
                                $( function() {
                                    $( "#datepicker" ).datepicker({
                                        firstDay: 1,
                                        onSelect: function( dateText,inst){
                                            $("[name='date']").val(dateText);
                                            $("#date").html(dateText);
                                        }
                                    });
                                } );
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Выберите время</div>
                        <div class="panel-body" style="min-height: 301px;">
                            <input type="hidden" name="time" value="" required>
                            <div class="form-group">
                                <label>Утро</label><br>
                                <?php foreach ($time_morning as $time_morning){?>
                                    <a href="#" onclick="change_time('<?php echo trim($time_morning);?>','<?php echo md5($time_morning);?>',event)">
                                        <span id="<?php echo md5($time_morning);?>" class="label label-default"><?php echo $time_morning;?></span>
                                    </a>
                                <?php } ?>
                                <hr>
                            </div>
                            <div class="form-group">
                                <label>День</label><br>
                                <?php foreach ($time_afternoon as $time_afternoon){?>
                                    <a href="#" onclick="change_time('<?php echo trim($time_afternoon);?>','<?php echo md5($time_afternoon);?>',event)">
                                        <span id="<?php echo md5($time_afternoon);?>" class="label label-default"><?php echo $time_afternoon;?></span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Контактные данные</div>
                        <div class="panel-body" style="min-height: 301px;">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="ФИО*" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" placeholder="Телефон*" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="carnumber" class="form-control" placeholder="Номер автомобиля*" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="E-email" required>
                            </div>
                            <div class="form-group">
                                <textarea name="comment" class="form-control" placeholder="Комментарий"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-md-10">
                <div id="sto-order">
                    <b>Ваш выбор:</b> <span id="date"></span> <b>на</b> <span id="time"></span>
                </div>
            </div>
            <div class="col-md-2">
                <input id="cmsautox" type="hidden" name="cmsautox">
                <button type="submit" onclick="$('#cmsautox').val('true');">Записаться</button>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    function change_time(time,itemId, event){
        event.preventDefault();
        $("[name='time']").val(time);
        $(".label").removeClass('active');
        $("#"+itemId).addClass('active');
        $("#time").html(time);
    }
    function get_model(ID_mfa){
        $("[name='manufacturer']").val($("#manufacturer :selected").text());
        $.ajax({
           url: '/ajax/get_model',
            data: {ID_mfa:ID_mfa},
            method: 'post',
            success: function (html) {
                $("#model").html(html).removeAttr('disabled')
            }
        });
    }

    function get_typ(ID_mod){
        $("[name='model']").val($("#model :selected").text());
        $.ajax({
            url: '/ajax/get_typ',
            data: {ID_mod:ID_mod},
            method: 'post',
            success: function (html) {
                $("#typ").html(html).removeAttr('disabled')
            }
        });
    }

    function change_typ(){
        $("[name='typ']").val($("#typ :selected").text());
    }
</script>