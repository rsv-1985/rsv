<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_077 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` CHANGE `negative_balance` `negative_balance` DECIMAL(10,2) NOT NULL;");
        $this->db->query("UPDATE `ax_customer` SET `negative_balance` = 0");
        $this->db->query("ALTER TABLE `ax_customer` ADD `deferment_payment` INT NOT NULL AFTER `additional_information`;");
    }

    public function down(){

    }
}