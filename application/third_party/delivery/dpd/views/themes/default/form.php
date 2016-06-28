<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div>
        <div class="col-md-4">
            <div class="form-group">
                <label>City</label>
                <input class="additional" type="text" name="additional[dpd][city]" value="<?php echo @$_POST['additional']['dpd']['city'];?>" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Zip</label>
                <input class="additional" type="text" name="additional[dpd][zip]" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Data</label>
                <input class="additional" type="text" name="additional[dpd][date]" required>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?php echo $html;?>
<script>
    $(".additional").change(function(){
       if($('[name="additional[dpd][date]"]').val().length > 0 && $('[name="additional[dpd][zip]"]').val().length > 0 && $('[name="additional[dpd][city]"]').val().length > 0){
            total();
        }
    })
</script>
