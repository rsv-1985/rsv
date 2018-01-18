<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_banner_description extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_banner` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
    }
}