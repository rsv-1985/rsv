<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_db extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_history` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
        $this->db->query("ALTER TABLE `ax_page` ADD `show_for_user` TINYINT NOT NULL AFTER `show_footer`;");
    }

    public function down()
    {

    }
}