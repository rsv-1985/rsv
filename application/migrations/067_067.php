<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_067 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_product_price` ADD `price` DECIMAL(15,2) NOT NULL AFTER `updated_at`;");
    }
}