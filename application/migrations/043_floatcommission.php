<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_floatcommission extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_payment_method` CHANGE `comission` `comission` FLOAT(10,2) NULL DEFAULT NULL;");
    }

    public function down()
    {

    }
}