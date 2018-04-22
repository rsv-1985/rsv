<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_email96 extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` CHANGE `email` `email` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
    }
}