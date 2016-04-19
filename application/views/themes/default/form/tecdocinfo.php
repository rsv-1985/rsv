<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $Article;?><a href="#" class="pull-right" onclick="$('#popover').fadeOut().empty();return false;">x</a> </div>
    <div class="panel-body">
        <img src="/image?img=<?php echo $Image;?>&width=200">
        <?php echo str_replace(';','<br>',$Info);?>
    </div>
</div>
