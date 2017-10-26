<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_cart_comment extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_cart` ADD `comment` TEXT NOT NULL AFTER `cart_data`;");
    }

    public function down()
    {

    }
}