<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo $h1; ?></h1>
            <div class="catalog">
                <?php if ($manufacturers) { ?>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                        <a href="/catalog/<?php echo $manufacturer['slug']; ?>">
                            <img src="<?php echo $manufacturer['logo']; ?>" alt="<?php echo $manufacturer['name']; ?>">
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr />
            <?php echo $text;?>
        </div>
    </div>
</div>
