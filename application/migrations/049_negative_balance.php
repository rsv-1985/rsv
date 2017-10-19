<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_negative_balance extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD `negative_balance` TINYINT NOT NULL AFTER `store_status`;");
    }

    public function down()
    {

    }
}