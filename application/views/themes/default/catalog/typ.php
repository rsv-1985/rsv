<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row">
    <div class="container">
        <div class="col-md-12">
        
            <h1><?php echo $h1;?></h1>
            
            <ol class="breadcrumb">
              <?php foreach($breadcrumb as $breadcrumb){?>
                <li><a href="<?php echo $breadcrumb['href'];?>"><?php echo $breadcrumb['title'];?></a></li>
              <?php } ?>
            </ol>
            <table class="table">
                <?php foreach($typs as $typ){?>
                    <tr>
                        <td><a href="<?php echo current_url();?>/<?php echo $typ['slug'];?>"><?php echo $typ['Name'];?></a> </td>
                        <td><?php echo $typ['Engines'];?></td>
                        <td><?php echo $typ['CCM'];?></td>
                        <td><?php echo $typ['KwHp'];?></td>
                        <td><?php echo $typ['Fuel'];?></td>
                        <td><?php echo $typ['Drive'];?></td>
                        <td><?php echo $typ['Doors'];?></td>
                        <td><?php echo $typ['Trans'];?></td>
                        <td><?php echo $typ['Body'];?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
