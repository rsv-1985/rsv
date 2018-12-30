<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_076 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_invoice` ADD `comment` TEXT NOT NULL AFTER `delivery_price`;");
        $this->db->query("ALTER TABLE `ax_invoice` ADD `ttn` VARCHAR(32) NOT NULL AFTER `comment`;");
    }

    public function down(){

    }
}