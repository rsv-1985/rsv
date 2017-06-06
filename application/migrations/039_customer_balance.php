<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_customer_balance extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD `balance` FLOAT(10,2) NOT NULL AFTER `password`;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `ax_customer` DROP `balance`;");
    }
}