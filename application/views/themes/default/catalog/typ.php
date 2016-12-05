<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
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
<div class="row-fluid">
    <div class="container">
        <div class="col-md-12">
            <ol class="breadcrumb">
              <?php foreach($breadcrumb as $breadcrumb){?>
                <li><a href="<?php echo $breadcrumb['href'];?>"><?php echo $breadcrumb['title'];?></a></li>
              <?php } ?>
            </ol>
            <table class="table table-responsive">
                <tr>
                    <th><?php echo lang('text_column_engine');?></th>
                    <th><?php echo lang('text_column_engine_code');?></th>
                    <th><?php echo lang('text_column_ccm');?></th>
                    <th><?php echo lang('text_column_KwHp');?></th>
                    <th><?php echo lang('text_column_Fuel');?></th>
                    <th><?php echo lang('text_column_Drive');?></th>
                    <th><?php echo lang('text_column_Body');?></th>
                </tr>
                <?php foreach($typs as $typ){?>
                    <tr>
                        <td><a href="<?php echo current_url();?>/<?php echo $typ['slug'];?>"><?php echo $typ['Name'];?></a> </td>
                        <td><?php echo $typ['Engines'];?></td>
                        <td><?php echo $typ['CCM'];?></td>
                        <td><?php echo $typ['KwHp'];?></td>
                        <td><?php echo $typ['Fuel'];?></td>
                        <td><?php echo $typ['Drive'];?></td>
                        <td><?php echo $typ['Body'];?></td>
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

