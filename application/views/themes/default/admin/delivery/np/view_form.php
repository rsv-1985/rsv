<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php echo form_open('/delivery/np/create_en',['method' => 'post', 'id' => 'np-form']);?>
<input type="hidden" name="order_id" value="<?php echo $order_info['id'];?>">
<input type="hidden" name="np_id" value="<?php echo $form_data['id'];?>">
<input type="hidden" name="NewAddress" value="1">
<input type="hidden" name="PaymentMethod" value="Cash">
<input type="hidden" name="CargoType" value="Cargo">
<input type="hidden" name="RecipientType" value="PrivatePerson">
<input type="hidden" name="RecipientName" value="<?php echo $order_info['last_name'].' '.$order_info['first_name'].' '.$order_info['patronymic'];?>">
<input type="hidden" name="RecipientsPhone" value="<?php echo $order_info['telephone'];?>">
<input type="hidden" name="VolumeGeneral" value="">
<input type="hidden" name="AdditionalInformation" value="<?php echo $order_info['id'];?>">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Данные для отправки</div>
            <div class="panel-body">
                <div class="">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Поиск населенного пункта</label>
                            <input type="text" onkeyup="getCity($(this).val())" class="form-control">
                            <div class="city-res"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Город</label>
                            <input type="text" name="RecipientCityName"
                                   value="<?php echo @$form_data['RecipientCityName']; ?>" readonly class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Область</label>
                            <input type="text" name="RecipientArea" value="<?php echo @$form_data['RecipientArea']; ?>"
                                   readonly class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Регион</label>
                            <input type="text" name="RecipientAreaRegions"
                                   value="<?php echo @$form_data['RecipientAreaRegions']; ?>" readonly class="form-control">
                        </div>
                        <div class="form-group" id="RecipientAddressName"
                             <?php if (@$form_data['RecipientAddressName2']){ ?>style="display: none"<?php } ?>>
                            <label>Склад</label>
                            <select readonly name="RecipientAddressName" class="form-control">
                                <?php if (isset($form_data['RecipientAddressName'])) { ?>
                                    <option value="<?php echo $form_data['RecipientAddressName']; ?>"
                                            selected><?php echo $form_data['RecipientAddressName']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="WarehouseDoors"
                             <?php if (!$form_data['RecipientAddressName2']){ ?>style="display: none"<?php } ?>>

                            <div class="form-group">
                                <label>Адрес</label>
                                <input value="<?php echo $form_data['RecipientAddressName2']; ?>" class="form-control"
                                       type="text" name="RecipientAddressName2">
                            </div>
                            <div class="form-group">
                                <label>Номер дома</label>
                                <input value="<?php echo $form_data['RecipientHouse']; ?>" class="form-control" type="text"
                                       name="RecipientHouse">
                            </div>
                            <div class="form-group">
                                <label>Этаж</label>
                                <input value="<?php echo $form_data['RecipientFlat']; ?>" class="form-control" type="text"
                                       name="RecipientFlat">
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Тип доставки</label>
                            <select name="ServiceType" class="form-control">
                                <option value="WarehouseWarehouse"
                                        <?php if (!$form_data['RecipientAddressName2']){ ?>selected<?php } ?>>Склад
                                </option>
                                <option value="WarehouseDoors"
                                        <?php if ($form_data['RecipientAddressName2']){ ?>selected<?php } ?>>Адресная
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Плательщик за доставку</label>
                            <select name="PayerType" class="form-control">
                                <option value="Recipient" selected>Получатель</option>
                                <option value="Sender">Отправитель</option>
                            </select>
                        </div>




                        <div class="form-group">
                            <label>Вес</label>
                            <input required type="text" name="Weight" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Количество мест</label>
                            <input type="number" name="SeatsAmount" value="1" class="form-control">
                        </div>



                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Форма оплаты</label>
                            <select name="nalojka" class="form-control">
                                <option value="1">Наложенный платеж</option>
                                <option value="0">Предоплата</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Сумма наложки</label>
                            <input type="text" name="RedeliveryString" class="form-control" value="<?php echo $order_info['total'] - $order_info['prepayment']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Объявленная стоимость</label>
                            <input type="text" name="Cost" value="<?php echo $order_info['total']; ?>"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
                            <input type="text" name="Description" value="Запчасти" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Дата отправки</label>
                            <input type="date" name="DateTime" value="<?php echo date('Y-m-d');?>"  min="<?php echo date('Y-m-d');?>" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-info pull-right">Создать ЭН</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close();?>



<script>
    function getWarehouses(Ref) {
        $.ajax({
            url: '/delivery/np/getWarehouses',
            data: {Ref: Ref},
            success: function (json) {
                if (json) {
                    var html = '<option>---</option>';
                    $(json).each(function (index, item) {
                        html += '<option value="' + item['Number'] + '">' + item['Number'] + '-' + item['Description'] + '</option>';
                        $("[name='RecipientAddressName']").html(html).removeAttr('readonly');
                    });
                }
            }
        })
    }

    var timeout = null;

    function getCity(term) {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            if (term.length > 2) {
                $.ajax({
                    url: '/delivery/np/searchSettlements',
                    data: {city: term},
                    success: function (json) {
                        if (json) {
                            var html = '';
                            $(json).each(function (index, item) {
                                item['DeliveryCity'] = item['DeliveryCity'].replace("'","\\'");
                                item['MainDescription'] = item['MainDescription'].replace("'","\\'");
                                item['Area'] = item['Area'].replace("'","\\'");
                                item['Region'] = item['Region'].replace("'","\\'");
                                html += '<a href="#" onclick="set(\'' + item['DeliveryCity'] + '\',\'' + item['MainDescription'] + '\',\'' + item['Area'] + '\',\'' + item['Region'] + '\');$(\'.city-res\').empty();return false;">' + item['Present'] + '</a><br>';
                            });
                        }
                        $(".city-res").html(html);
                    }
                })
            }
        }, 500);
    }

    function set(DeliveryCity, RecipientCityName, RecipientArea, RecipientAreaRegions) {
        getWarehouses(DeliveryCity);
        $("[name='RecipientCityName']").val(RecipientCityName);
        $("[name='RecipientArea']").val(RecipientArea);
        $("[name='RecipientAreaRegions']").val(RecipientAreaRegions);
    }

    $(document).ready(function () {
        $("[name='ServiceType'").change(function () {
            if ($(this).val() == 'WarehouseDoors') {
                $("#RecipientAddressName").hide();
                $("#WarehouseDoors").show();
            } else {
                $("#RecipientAddressName").show();
                $("#WarehouseDoors").hide();
            }
        });

        $("[name='nalojka'").change(function(){
           if($(this).val() == '1'){
               $("[name='RedeliveryString']").removeAttr('disabled');
           }else{
               $("[name='RedeliveryString']").attr('disabled','disabled');

           }
        });

        $("#np-form").submit(function(e){
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                method: 'post',
                success: function(response){
                    if(response){
                        alert(response);
                    }else{
                        location.reload()
                    }
                }
            })
        })
    });
</script>
