<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_order_model extends CI_model
{
   public $total_rows = 0;

   public function sale_order_get_all($limit, $start){
       //Получаем сумму расходов по заказам
       $sql = "SELECT SUM(op.delivery_price) as delivery_total FROM `ax_order_product` op 
       LEFT JOIN `ax_order` o ON o.id = op.order_id 
       WHERE 1";

       if ($this->input->get('status_id')) {
           $sql .= " AND o.status = '" . (int)$this->input->get('status_id') . "'";
       } else {
           $sql .= " AND o.status > '0'";
       }

       if ($this->input->get('date_start')) {
           $sql .= " AND DATE(o.created_at) >= " . $this->db->escape($this->input->get('date_start')) . "";
       }

       if ($this->input->get('date_end')) {
           $sql .= " AND DATE(o.created_at) <= " . $this->db->escape($this->input->get('date_end')) . "";
       }

       if ($this->input->get('filter_group')) {
           $group = $this->input->get('filter_group');
       } else {
           $group = 'day';
       }

       switch($group) {
           case 'day';
               $sql .= " GROUP BY YEAR(o.created_at), MONTH(o.created_at), DAY(o.created_at)";
               break;
           default:
           case 'week':
               $sql .= " GROUP BY YEAR(o.created_at), WEEK(o.created_at)";
               break;
           case 'month':
               $sql .= " GROUP BY YEAR(o.created_at), MONTH(o.created_at)";
               break;
           case 'year':
               $sql .= " GROUP BY YEAR(o.created_at)";
               break;
       }

       $sql .= " ORDER BY o.created_at DESC";
       $sql .= " LIMIT " . $start . "," . $limit;

       $query = $this->db->query($sql)->row_array();

       $delivery_total = $query['delivery_total'];


       $sql = "SELECT SQL_CALC_FOUND_ROWS 
MIN(o.created_at) AS date_start, 
MAX(o.created_at) AS date_end, 
COUNT(*) AS `orders`, 
SUM(o.total) AS `total`, 
SUM(o.commission) as `total_commission`,
$delivery_total AS `total_delivery`
FROM `ax_order` o";

       if ($this->input->get('status_id')) {
           $sql .= " WHERE o.status = '" . (int)$this->input->get('status_id') . "'";
       } else {
           $sql .= " WHERE o.status > '0'";
       }

       if ($this->input->get('date_start')) {
           $sql .= " AND DATE(o.created_at) >= " . $this->db->escape($this->input->get('date_start')) . "";
       }

       if ($this->input->get('date_end')) {
           $sql .= " AND DATE(o.created_at) <= " . $this->db->escape($this->input->get('date_end')) . "";
       }

       if ($this->input->get('filter_group')) {
           $group = $this->input->get('filter_group');
       } else {
           $group = 'day';
       }

       switch($group) {
           case 'day';
               $sql .= " GROUP BY YEAR(o.created_at), MONTH(o.created_at), DAY(o.created_at)";
               break;
           default:
           case 'week':
               $sql .= " GROUP BY YEAR(o.created_at), WEEK(o.created_at)";
               break;
           case 'month':
               $sql .= " GROUP BY YEAR(o.created_at), MONTH(o.created_at)";
               break;
           case 'year':
               $sql .= " GROUP BY YEAR(o.created_at)";
               break;
       }

       $sql .= " ORDER BY o.created_at DESC";
       $sql .= " LIMIT " . $start . "," . $limit;

       $query = $this->db->query($sql);
       $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;
       return $query->result_array();
   }
}