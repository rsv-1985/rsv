<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Productindex extends CI_Migration {

    public function up()
    {
        $this->db->query("UPDATE `ax_migrations` SET `version`= 27;");
        $this->db->query("ALTER TABLE `ax_product` ADD INDEX (`name`);");
        $this->db->query("ALTER TABLE `ax_product` ADD INDEX (`brand`);");
    }
    public function down()
    {
        return;
    }
}