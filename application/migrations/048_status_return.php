<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_status_return extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_status` ADD `is_return` TINYINT NOT NULL AFTER `is_complete`;");
    }

    public function down()
    {

    }
}