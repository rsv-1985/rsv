<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Orderaddress extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order` ADD `address` TEXT NOT NULL AFTER `payment_method_id`;");
    }
    public function down()
    {
        return;
    }
}