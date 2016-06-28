<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_paid extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order` ADD `paid` BOOLEAN NOT NULL AFTER `delivery_price`;");
    }

    public function down()
    {

    }
}