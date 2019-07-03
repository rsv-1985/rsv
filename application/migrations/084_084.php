<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_084 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_supplier` ADD `description2` TEXT NOT NULL AFTER `description`;");
    }

    public function down(){

    }
}