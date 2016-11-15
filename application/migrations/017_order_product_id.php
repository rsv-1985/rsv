
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_product_id extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` ADD `product_id` INT NOT NULL AFTER `order_id`;");
    }
    public function down()
    {
        return;
    }
}