<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Key_settings extends CI_Migration
{

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_settings` CHANGE `key_settings` `key_settings` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
    }

    public function down()
    {
        return;
    }
}