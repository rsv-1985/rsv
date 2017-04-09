<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Searchfix extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_search_history` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;");
    }
    public function down()
    {
        return;
    }
}