<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_download_folder extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer_group` ADD `download_folder` VARCHAR(255) NOT NULL AFTER `name`;");
    }
}