<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_synlen extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_synonym` CHANGE `brand1` `brand1` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->db->query("ALTER TABLE `ax_synonym` CHANGE `brand2` `brand2` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
    }
}