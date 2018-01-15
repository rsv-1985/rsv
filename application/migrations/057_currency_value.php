<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_currency_value extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_currency` CHANGE `value` `value` FLOAT(10,2) NOT NULL;");
    }
}


