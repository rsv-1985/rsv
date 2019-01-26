<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>


<div style="text-align: center">
    <h1><?php echo $text_no_close;?></h1>
    <img src="/image/load.gif">
</div>
<form method="POST" action="<?=$action?>" id="liqpay_checkout" accept-charset="utf-8">
    <input type="hidden" name="data"  value="<?=$data?>" />
    <input type="hidden" name="signature" value="<?=$signature?>" />
    <input type="hidden" name="language" value="<?php echo $language;?>">
    <div class="buttons">
        <div class="right" style="display:none;">
            <input type="submit" value="" id="button-confirm" class="btn btn-primary button" />
        </div>
    </div>
</form>
<script>
    document.getElementById("liqpay_checkout").submit();
</script>
