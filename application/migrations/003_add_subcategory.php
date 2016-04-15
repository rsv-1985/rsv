<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_subcategory extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_category` ADD `parent_id` INT NOT NULL AFTER `id`;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `ax_category` DROP `parent_id`;");
    }
}