<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waybill_model extends Default_model{
    public $table = 'waybill';

    public function getInvoices(){
        // полусаем товары с инвойсов кторые на отправку
        return $this->db->query("SELECT 
        o.address, o.first_name, o.last_name, o.delivery_method_id, o.telephone,
        op.sku, op.brand, op.name, op.order_id, op.price,
        n.*, 
        dm.name as delivery_method,
        i.customer_id,
        i.comment,
        ip.* FROM ax_invoice_product ip
       
        INNER JOIN ax_invoice i ON i.id = ip.invoice_id
        INNER JOIN ax_order_product op ON op.id = ip.product_id
        INNER JOIN ax_order o ON o.id = op.order_id
        LEFT JOIN ax_delivery_method dm ON dm.id = o.delivery_method_id
        LEFT JOIN ax_np n ON n.order_id = o.id
        WHERE i.status_id = 2
        ")->result_array();
    }
}