<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_free_delivery extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_delivery_method` ADD `free_cost` FLOAT(10,2) NOT NULL AFTER `payment_methods`;");
    }

    public function down()
    {

    }
}