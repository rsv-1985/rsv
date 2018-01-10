<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_created extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order` CHANGE `created_at` `created_at` TIMESTAMP NOT NULL;");
    }


    public function down()
    {

    }
}