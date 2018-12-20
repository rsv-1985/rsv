<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if($statuses && $status_totals){?>
    <?php foreach ($statuses as $status){?>
        <?php if($status_totals[$status['id']]['total'] > 0){?>
            <span style="color: <?php echo $status['color'];?>"><b><?php echo $status['name'];?></b>:</span> <?php echo $status_totals[$status['id']]['qty'];?>шт. (<?php echo $status_totals[$status['id']]['total'];?>) /
        <?php } ?>
    <?php } ?>
<?php } ?>
