<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_image_product extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_product` ADD `image` VARCHAR(255) NOT NULL AFTER `name`;");
        $this->db->query("ALTER TABLE `ax_importtmp` ADD `image` VARCHAR(255) NOT NULL AFTER `category_id`;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `ax_product` DROP `image`;");
        $this->db->query("ALTER TABLE `ax_importtmp` DROP `image`;");
    }
}