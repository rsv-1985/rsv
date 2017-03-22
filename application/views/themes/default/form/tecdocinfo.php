<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="div-info">
    <div class="col-md-4">
        <?php if($tecdoc_info){?>
            <img onerror="imgError(this, 200);" src="/image?img=<?php echo $tecdoc_info->Image;?>&width=200">
        <?php }else{ ?>
            <img onerror="imgError(this, 200);" src="/image?width=200">
        <?php } ?>
    </div>
    <div class="col-md-8">
        <a href="#" class="pull-right" onclick="$('.div-info').remove();return false;">x</a>
        <?php if($tecdoc_info){?>
            <?php echo str_replace(';','<br>',$tecdoc_info->Info);?>
        <?php }?>

    </div>
</div>
