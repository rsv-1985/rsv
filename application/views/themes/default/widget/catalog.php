<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="catalog">
        <?php if ($manufacturers) { ?>
            <?php foreach ($manufacturers as $manufacturer) { ?>
                <a href="/catalog/<?php echo $manufacturer['slug']; ?><?php if($this->input->get('id_tree')){?>?id_tree=<?php echo $this->input->get('id_tree');?><?php } ?> ">
                    <img onerror="imgError(this);"
                         src="<?php echo $manufacturer['logo']; ?>"
                         alt="<?php echo $manufacturer['name']; ?>"
                         title="<?php echo $manufacturer['name']; ?>">
                </a>
            <?php } ?>
        <?php } ?>
</div>