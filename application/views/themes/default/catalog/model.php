<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1;?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row-fluid">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumb as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['title']; ?></a></li>
                <?php } ?>
            </ol>
            <table class="table table-responsive">
                <tr>
                    <th><?php echo lang('text_model');?></th>
                    <th><?php echo lang('text_model_date');?></th>
                    <th><?php echo lang('text_model_date_end');?></th>
                </tr>
                <?php foreach ($models_type as $models_type) { ?>
                    <tr>
                        <td>
                            <a href="<?php echo current_url(); ?>/<?php echo $models_type['slug']; ?>"><?php echo $models_type['name']; ?></a>
                        </td>
                        <td><?php echo $models_type['date_start']; ?></td>
                        <td><?php echo $models_type['date_end']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hr/>
            <?php echo $text; ?>
        </div>
    </div>
</div>
