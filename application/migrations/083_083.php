<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_083 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD `manager_notes` TEXT NOT NULL AFTER `deferment_payment`;");
    }

    public function down(){

    }
}