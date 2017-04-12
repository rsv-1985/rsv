<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Patronymic extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_customer` ADD `patronymic` VARCHAR(255) NOT NULL AFTER `second_name`;");
        $this->db->query("ALTER TABLE `ax_order` ADD `patronymic` VARCHAR(255) NOT NULL AFTER `last_name`;");
    }
    public function down()
    {
        return;
    }
}