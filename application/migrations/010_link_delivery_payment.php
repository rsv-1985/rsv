<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_link_delivery_payment extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_delivery_method` ADD `payment_methods` LONGTEXT NOT NULL AFTER `sort`;");
        $this->db->query("ALTER TABLE `ax_payment_method` ADD `delivery_methods` LONGTEXT NOT NULL AFTER `fix_cost`;");
    }

    public function down()
    {

    }
}