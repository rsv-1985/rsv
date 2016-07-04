<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .listepr {
        float:left;
        display: inline-block;
    }

    .lignepr {
        background-color: #fff;
        color: #424143;
        height: 50px;
    }

    .lignepr:hover {
        cursor: pointer;
    }


    #dpdrelais_logo img {
        height: 45px;
        float: left;
        width: 45px;
    }

    .dpdrelais_info {
        float: left;
        margin: 4px 10px;
        text-transform: uppercase;
    }
    .dpdrelais_popup {
        margin: 16px 10px;
        float: right;
        text-align: right;
    }
    div.dpdrelais_popup a {
        color: #424143;
        text-decoration: none;
    }
    div.dpdrelais_popup a:hover{
        color: #dc0032;
        text-decoration: underline;
    }
    .dpdrelais_distance {
        margin: 10px 10px;
        float: right;
        text-align: right;

    }
    .dpdrelais_radio {
        margin: 12px 20px;
        float: right;
        text-align: right;

    }
    #dpdfrance_pickup {
        font-weight: normal;
        font-size: 11px;
    }

    div.lignepr {
        border-color: #424143;
        border-style: solid;
        border-width: 0px 0px 1px 0px;
        margin: 5px 0px;
        height: 65px;
    }
    div.lignepr:nth-child(9)
    {
        border:none;
    }
    .item-dpd{
        display: inline-block;
        /* max-width: 100%; */
        margin-bottom: 5px;
        font-weight: normal;
        width: 100%;
        /* height: 100px; */
    }
</style>
<div class="row">
    <div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Address</label>
                <input class="additional" type="text" name="additional[dpd][address]" value="<?php echo @$_POST['additional']['dpd']['address'];?>" placeholder="27 Rue du colonel pierre avia" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Zip</label>
                <input class="additional" type="text" name="additional[dpd][zip]" value="<?php echo @$_POST['additional']['dpd']['zip'];?>" placeholder="75015" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>City</label>
                <input class="additional" type="text" name="additional[dpd][city]" value="<?php echo @$_POST['additional']['dpd']['city'];?>" placeholder="Paris" required>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="dpd">
    <?php if($response){ echo $response;}else{?>
        Уажите Ваш адрес для посиска ближайшего отделения
    <?php } ?>
</div>

<script>
    $(".additional").change(function(){
       if($('[name="additional[dpd][address]"]').val().length > 0 && $('[name="additional[dpd][zip]"]').val().length > 0 && $('[name="additional[dpd][city]"]').val().length > 0){
            total();
        }
    })
</script>
