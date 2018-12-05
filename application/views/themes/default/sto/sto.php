<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<link rel="stylesheet" href="<?php echo theme_url();?>css/jquery-ui.min.css">
<script src="<?php echo theme_url();?>js/jquery-ui.min.js"></script>
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
                        <div class="panel-heading"><?php echo lang('text_section_auto');?></div>
                        <div class="panel-body">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <select name="service_id" class="form-control" required>
                                            <option value="">Услуга</option>
                                            <?php foreach ($services as $service){?>
                                                <option value="<?php echo $service['id'];?>" <?php echo set_select('service',$service['id']);?>><?php echo $service['name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input required placeholder="<?php echo lang('text_manufacturer');?>" type="text" name="manufacturer" value="<?php echo set_value('manufacturer');?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input required placeholder="<?php echo lang('text_model');?>"  type="text" name="model" value="<?php echo set_value('model');?>" class="form-control">
                                    </div>
                                    <div class="form-group pull-right">
                                       <input type="text" value="<?php echo set_value('vin');?>" name="vin" class="form-control" placeholder="<?php echo lang('text_vin');?>">
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo lang('text_section_date_time');?></div>
                        <div class="panel-body" style="min-height: 301px;">
                            <div class="form-group">
                                <label>Дата</label>
                                <div id="datepicker"></div>
                            </div>

                            <div class="form-group">
                                <label>Время</label>
                                <input required onkeyup="change_time($(this).val())" type="time" name="time" class="form-control" value="<?php echo set_value('time');?>">
                            </div>
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
                                        dateFormat: "yy.mm.dd",
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
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">Контактные данные</div>
                        <div class="panel-body" style="min-height: 301px;">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="ФИО">
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" placeholder="Телефон*" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="carnumber" class="form-control" placeholder="Номер автомобиля">
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="E-email">
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
    function change_time(time){
        event.preventDefault();
        $("#time").html(time);
    }
</script>