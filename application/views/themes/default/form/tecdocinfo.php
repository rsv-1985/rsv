<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $sku.' '.$brand;?><a href="#" class="pull-right" onclick="$('#popover').empty();return false;">x</a> </div>
    <div class="panel-body">
        <?php if($tecdoc_info){?>
            <img onerror="imgError(this, 200);" src="/image?img=<?php echo $tecdoc_info->Image;?>&width=200">
            <?php echo str_replace(';','<br>',$tecdoc_info->Info);?>
        <?php }else{ ?>
            <img onerror="imgError(this, 200);" src="/image?width=200">
        <?php } ?>

    </div>
</div>
