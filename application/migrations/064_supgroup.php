<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_supgroup extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_pricing` ADD `customer_group_id` INT NOT NULL AFTER `brand`;");
    }
}