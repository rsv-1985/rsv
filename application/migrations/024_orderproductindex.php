
<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Orderproductindex extends CI_Migration {

    public function up()
    {
        $this->db->query("ALTER TABLE `ax_order_product` ADD INDEX(`order_id`);");
        $this->db->query("ALTER TABLE `ax_order_product` ADD INDEX(`product_id`);");
        $this->db->query("ALTER TABLE `ax_order_product` ADD INDEX(`supplier_id`);");
        $this->db->query("ALTER TABLE `ax_order_product` ADD INDEX(`sku`);");
        $this->db->query("ALTER TABLE `ax_order_product` ADD INDEX(`status_id`);");
    }
    public function down()
    {
        return;
    }
}