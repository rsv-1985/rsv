<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_078 extends CI_Migration
{

    public function up()
    {
        $this->db->query("CREATE TABLE `cmsautox`.`ax_product_images` ( `id` INT NOT NULL AUTO_INCREMENT , `product_id` INT NOT NULL , `image` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`), INDEX (`product_id`)) ENGINE = MyISAM CHARSET=utf8 COLLATE utf8_general_ci;");
    }

    public function down(){

    }
}