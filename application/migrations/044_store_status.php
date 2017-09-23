<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_store_status extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD `store_status` TINYINT NOT NULL AFTER `status`;");
    }

    public function down()
    {

    }
}