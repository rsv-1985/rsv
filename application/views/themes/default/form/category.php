<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if($this->category){?>
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo lang('text_category');?></div>
        <div class="panel-body">
            <div class="dropdown clearfix">
                <?php echo build_tree($this->category,0);?>
            </div>
        </div>
    </div>
<?php } ?>
