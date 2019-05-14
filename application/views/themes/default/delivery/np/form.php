<div class="row">
    <div class="panel panel-default">
        <div class="col-md-12">
            <div class="form-group">
                <label><?php echo lang('text_np_address');?></label>
                <input required name="np_address" value="<?php echo @$customer->np_address;?>" type="text" onkeyup="getCity($(this).val())" class="form-control" >
                <div class="city-res"></div>
            </div>
            <div class="form-group" id="form-group-RecipientAddressName" <?php if(@$customer->RecipientAddressName2){?>style="display: none;"<?php } ?>>
                <label><?php echo lang('text_np_RecipientAddressName');?></label>
                <select disabled name="RecipientAddressName" class="form-control">
                    <?php if(isset($customer->RecipientAddressName)){?>
                        <option value="<?php echo $customer->RecipientAddressName;?>" selected><?php echo $customer->RecipientAddressName;?></option>
                    <?php } ?>
                </select>
            </div>
            <div id="WarehouseDoors" <?php if(!@$customer->RecipientAddressName2){?>style="display: none;"<?php } ?>>
                <div class="form-group">
                    <div class="form-group">
                        <label><?php echo lang('text_np_RecipientAddressName2');?></label>
                        <input value="<?php echo @$customer->RecipientAddressName2;?>" class="form-control" type="text" name="RecipientAddressName2">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_np_RecipientHouse');?></label>
                        <input value="<?php echo @$customer->RecipientHouse;?>" class="form-control" type="text" name="RecipientHouse">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_np_RecipientFlat');?></label>
                        <input value="<?php echo @$customer->RecipientFlat;?>" class="form-control" type="text" name="RecipientFlat">
                    </div>
                </div>
            </div>
            <div class="checkbox">
                <label><input <?php if(@$customer->RecipientAddressName2){?>checked<?php } ?> type="checkbox" onchange="getField(this)"><?php echo lang('text_np_WarehouseDoors');?></label>
            </div>
            <input id="DeliveryCity" type="hidden" name="DeliveryCity" value="<?php echo @$customer->DeliveryCity;?>">
            <input type="hidden" name="RecipientCityName" value="<?php echo @$customer->RecipientCityName;?>" class="form-control">
            <input type="hidden" name="RecipientArea" value="<?php echo @$customer->RecipientArea;?>" class="form-control">
            <input type="hidden" name="RecipientAreaRegions" value="<?php echo @$customer->RecipientAreaRegions;?>" class="form-control">

        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>

    $(document).ready(function(){
        //$("#form-group-address").hide();

        if($("[name='DeliveryCity']").val()){
            var Ref = $("[name='DeliveryCity']").val();
            var customer_warehouse = '<?php echo @$customer->RecipientAddressName;?>';
            getWarehouses(Ref,customer_warehouse);
        }

        $("[name='np_address']").change(function(){
            if($(this).prop('checked')){
                $("[name='np_city'],[name='np_store']").attr('disabled',true);
            }else{
                $("[name='np_city'],[name='np_store']").removeAttr('disabled');
            }
        })
    });

    function getField(obj) {
        if($(obj).prop('checked')){
            $("[name='RecipientAddressName']").prop('selectedIndex',0);
            $("#form-group-RecipientAddressName").hide();
            $("#WarehouseDoors").show();
        }else{
            $("#WarehouseDoors input").each(function(){
                $(this).val('');
            });
            $("#WarehouseDoors").hide();
            $("#form-group-RecipientAddressName").show();
        }
    }


    function getWarehouses(Ref,customer_warehouse) {
        $.ajax({
            url: '/delivery/np/getWarehouses',
            data: {Ref:Ref},
            success: function (json) {
                if(json){
                    var html = '<option>---</option>';
                    $(json).each(function(index, item){
                        if(item['Number'] == customer_warehouse){
                            html += '<option selected value="'+item['Number']+'">'+item['Number']+'-'+item['Description']+'</option>';
                        }else{
                            html += '<option value="'+item['Number']+'">'+item['Number']+'-'+item['Description']+'</option>';
                        }
                       $("[name='RecipientAddressName']").html(html).removeAttr('disabled');
                    });
                }
            }
        })
    }

    var timeout = null;
    function getCity(term){
        clearTimeout(timeout);
        timeout = setTimeout(function(){
            if(term.length > 2){
                $.ajax({
                    url: '/delivery/np/searchSettlements',
                    data: {city:term},
                    success: function (json) {
                        if(json){
                            var html = '';
                            $(json).each(function(index,item){
                                item['DeliveryCity'] = item['DeliveryCity'].replace("'","\\'");
                                item['MainDescription'] = item['MainDescription'].replace("'","\\'");
                                item['Area'] = item['Area'].replace("'","\\'");
                                item['Region'] = item['Region'].replace("'","\\'");

                                console.log(item);
                                html += '<a onclick="set(\''+item['DeliveryCity']+'\',\''+item['MainDescription']+'\',\''+item['Area']+'\',\''+item['Region']+'\');$(\'.city-res\').empty();return false;">'+item['Present']+'</a><br>';
                            });
                        }
                        $(".city-res").html(html);
                    }
                })
            }
        }, 1000);
    }

    function set(DeliveryCity,RecipientCityName,RecipientArea,RecipientAreaRegions){
        $("[name='np_address'").val(RecipientCityName + ' ' + RecipientArea + ' ' + RecipientAreaRegions);
        $("[name='DeliveryCity']").val(DeliveryCity);
        getWarehouses(DeliveryCity);
        $("[name='RecipientCityName']").val(RecipientCityName);
        $("[name='RecipientArea']").val(RecipientArea);
        $("[name='RecipientAreaRegions']").val(RecipientAreaRegions);
    }


</script>

