
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_api extends CI_Migration {

    public function up()
    {
        $this->db->query("CREATE TABLE `ax_keys` (
       `id` INT(11) NOT NULL AUTO_INCREMENT,
       `user_id` INT(11) NOT NULL,
       `key` VARCHAR(40) NOT NULL,
       `level` INT(2) NOT NULL,
       `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
       `is_private_key` TINYINT(1)  NOT NULL DEFAULT '0',
       `ip_addresses` TEXT NULL DEFAULT NULL,
       `date_created` INT(11) NOT NULL,
       PRIMARY KEY (`id`)
   ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
        $this->db->query("ALTER TABLE `ax_product_price` ADD INDEX(`status`);");
    }
    public function down()
    {
        return;
    }
}