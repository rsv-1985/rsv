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
                 <?php foreach ($breadcrumb as $b) { ?>

					<?  if($b == end($breadcrumb)) {
        ?>
   	  <li><?php echo $b['title']; ?></li>
   <?
  }
  else {
	  ?>
	  <li><a href="<?php echo $b['href']; ?>"><?php echo $b['title']; ?></a></li>

	   <?

  }
	?>
                <?php } ?>
            </ol>
			<div class="table-responsive">
            <table class="table table-bordered table-hover">
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
                    <tr style="cursor: pointer;" onclick="location.href='<?php echo current_url();?>/<?php echo $typ['slug'];?>'">
                        <td><a href="<?php echo current_url();?>/<?php echo $typ['slug'];?>"><?php echo $typ['Name'];?></a> </td>
                        <td><?php echo $typ['Engines'];?></td>
                        <td><?php echo $typ['CCM'];?></td>
                        <td><?php echo $typ['KwHp'];?></td>
                        <td><?php echo $typ['Fuel'];?></td>
                        <td><?php echo $typ['Drive'];?></td>
                        <td><?php echo $typ['Body'];?></td>
                    </tr>
                <?php } ?>
            </table>       </div>

        </div>
    </div>
</div>

