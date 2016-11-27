
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_usergroup2 extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_user` ADD `group_id` INT NOT NULL AFTER `password`;");
    }
    public function down()
    {
        return;
    }
}