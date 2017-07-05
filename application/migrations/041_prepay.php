<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_prepay extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order` ADD `prepayment` FLOAT(10,2) NOT NULL AFTER `paid`;");
    }

    public function down()
    {

    }
}