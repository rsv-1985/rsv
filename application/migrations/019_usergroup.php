
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_usergroup extends CI_Migration {

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_user_group` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(32) NOT NULL , `access` LONGTEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM;");
    }
    public function down()
    {
        return;
    }
}