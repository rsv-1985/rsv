<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends Default_model{
    public $table = 'invoice';

    public $total_rows = 0;

    public $statuses = [0 => 'Новый', 1 => 'Проведен', 2 => 'В путевой лист'];



    public function invoice_get_all($limit,$offset){
        $this->db->select('SQL_CALC_FOUND_ROWS i.*, CONCAT_WS(" ", c.phone, c.first_name, c.second_name) as customer_name, c.balance FROM ax_invoice i',false);
        $this->db->join('ax_customer c','c.id = i.customer_id', 'left', false);

        if($this->input->get('customer_name')){
            $this->db->group_start();
            $phone = format_phone($this->input->get('customer_name'));
            if($phone){
                $this->db->or_like('c.phone', $phone, 'both');
            }

            $this->db->or_like('c.first_name', $this->input->get('customer_name',true), 'both');
            $this->db->or_like('c.second_name',  $this->input->get('customer_name',true), 'both');
            $this->db->or_like('c.patronymic',  $this->input->get('customer_name',true), 'both');
            $this->db->or_like('c.email',  $this->input->get('customer_name',true), 'both');
            $this->db->group_end();
        }

        if($this->input->get('customer_id')){
            $this->db->where('i.customer_id', (int)$this->input->get('customer_id'), false);
        }

        if(isset($_GET['status_id']) && $_GET['status_id'] != ''){
            $this->db->where('i.status_id', (int)$this->input->get('status_id'), false);
        }

        if($this->input->get('date_from')){
            $this->db->where('DATE(i.created_at) >=', (string)$this->input->get('date_from', true));
        }

        if($this->input->get('date_to')){
            $this->db->where('DATE(i.created_at) <=', (string)$this->input->get('date_to', true));
        }

        $this->db->limit($limit,$offset);
        $this->db->order_by('id','DESC');
        $query = $this->db->get();

        $this->total_rows = $this->db->query('SELECT FOUND_ROWS() AS `Count`')->row()->Count;

        if($query->num_rows() > 0){
            return $query->result_array();
        }

        return false;
    }



    public function updateProductQty($product_id, $quantity){
        $this->db->where('product_id', (int)$product_id);
        $this->db->set('quantity',(int) $quantity);
        $this->db->update('invoice_product');
    }

    public function deleteProduct($product_id){
        $this->db->where('product_id', (int)$product_id);
        $this->db->delete('invoice_product');
    }

    public function getProducts($invoice_id){
        $this->db->from('invoice_product ip');
        $this->db->select('op.*, ip.quantity as iqty, ip.product_id as order_product_id');
        $this->db->join('order_product op', 'ip.product_id=op.id');
        $this->db->where('ip.invoice_id', (int)$invoice_id);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result_array();
        }
        return false;
    }

    public function getTotal($invoice_id){
        $result = $this->db->query("SELECT SUM(ip.quantity*op.price)+i.delivery_price as total FROM ax_invoice_product ip 
        LEFT JOIN ax_invoice i ON i.id = ip.invoice_id
        LEFT JOIN ax_order_product op ON ip.product_id=op.id WHERE ip.invoice_id = '".(int)$invoice_id."'")->row_array();

        if($result){
            return $result['total'];
        }
        return false;
    }

    private function _addInvoice($products){
        //Получаем статус отмененного заказа
        $return_status = (array)$this->orderstatus_model->get_return();

        $messages = '';
        //Разюиваем товары по пользователям
        $customer_items = [];
        foreach ($products as $item){
            if(!in_array($item['status_id'], $return_status)){
                $customer_items[$item['customer_id']][] = [
                    'id' => (int)$item['id'],
                    'quantity' => (int)$item['quantity']
                ];
            }
        }
        //Проверяем есть ли открытый инвойс под клиента, если нет создаем
        $save_items = [];
        if(count($customer_items)){
            foreach ($customer_items as $customer_id => $items){

                $this->db->where('customer_id', (int)$customer_id);
                $this->db->where('status_id', 0);
                $invoice = $this->db->get('invoice')->row_array();
                if($invoice){
                    $invoice_id = $invoice['id'];
                    $messages .= 'Обновлен инвойс '.$invoice_id.chr(10);
                }else{
                    $save['customer_id'] = (int)$customer_id;
                    $save['created_at'] = date('Y-m-d H:i:s');
                    $save['status_id'] = 0;
                    $invoice_id = $this->insert($save);
                    $messages .= 'Создан инвойс '.$invoice_id.chr(10);
                }

                foreach ($items as $item){
                    $save_items[] = [
                        'invoice_id' => (int)$invoice_id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity']
                    ];
                }
            }

            if($save_items){
                $this->db->insert_batch('invoice_product', $save_items);
            }
        }else{
            $messages = 'Нет товаров для добавления. Все товары добавлены или статуст товара отменен.';
        }


        return $messages;
    }

    public function addByOrder($order_id){
        $messages = '';
        //Получаем все товары с заказа которых нет в расходных
        $sql = "SELECT op.id, op.quantity, o.customer_id, op.status_id FROM ax_order_product op 
        INNER JOIN ax_order o ON op.order_id = o.id
        LEFT JOIN ax_invoice_product ip ON op.id = ip.product_id 
        WHERE op.order_id = '".(int)$order_id."' AND ip.invoice_id IS NULL ";
        $results = $this->db->query($sql)->result_array();
        if($results){
           $messages = $this->_addInvoice($results);
        }else{
            $messages = 'Нет товаров для рассходной';
        }

        return $messages;
    }

    public function addByItem($product_id){
        $messages = '';
        //Получаем все товары с заказа которых нет в расходных

        $sql = "SELECT op.id, op.quantity, o.customer_id, op.status_id FROM ax_order_product op 
        INNER JOIN ax_order o ON op.order_id = o.id
        LEFT JOIN ax_invoice_product ip ON op.id = ip.product_id 
        WHERE op.id = '".(int)$product_id."' AND ip.invoice_id IS NULL ";
        $results = $this->db->query($sql)->result_array();

        if($results){
            $messages = $this->_addInvoice($results);
        }else{
            $messages = 'Данный товар уже добавлен';
        }

        return $messages;
    }

    public function addByFilter(){
        $this->db->select('op.id, op.quantity, o.customer_id, op.status_id', false);
        $this->db->from('order_product op');
        $this->db->join('order o','op.order_id=o.id', 'left');
        $this->db->join('customer c','o.customer_id=c.id', 'left');
        $this->db->join('invoice_product ip','op.id=ip.product_id', 'left');
        if($this->input->get()){
            if($this->input->get('customer_name')){
                $this->db->group_start();
                $phone = format_phone($this->input->get('customer_name'));
                if($phone){
                    $this->db->or_like('o.phone', $phone);
                }

                $this->db->or_like('o.first_name', $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.last_name',  $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.patronymic',  $this->input->get('customer_name',true), 'both');
                $this->db->or_like('o.email',  $this->input->get('customer_name',true), 'both');
                $this->db->group_end();
            }

            if($this->input->get('customer_id')){
                $this->db->where('o.customer_id', (int)$this->input->get('customer_id'));
            }

            if($this->input->get('order_id')){
                $this->db->where('o.order_id', (int)$this->input->get('order_id'));
            }
            if($this->input->get('name')){
                $this->db->like('op.name', $this->input->get('name', true));
            }
            if($this->input->get('sku')){
                $this->db->where('op.sku', $this->product_model->clear_sku($this->input->get('sku', true)));
            }
            if($this->input->get('brand')){
                $this->db->where('op.brand', $this->input->get('brand', true));
            }
            if($this->input->get('quantity')){
                $this->db->where('op.quantity', (int)$this->input->get('quantity'));
            }
            if($this->input->get('supplier_id')){
                $this->db->where_in('op.supplier_id', (array)$this->input->get('supplier_id'));
            }
            if($this->input->get('status_id')){
                $this->db->where('o.status', (int)$this->input->get('status_id'));
            }
            if($this->input->get('product_status_id')){
                $this->db->where('op.status_id', (int)$this->input->get('product_status_id'));
            }
        }

        $this->db->where('ip.invoice_id IS NULL', NULL, FALSE);

        $results = $this->db->get()->result_array();

        if($results){
            $messages = $this->_addInvoice($results);
        }else{
            $messages = 'Нет товаров для добавления';
        }

        return $messages;
    }

    public function delete($invoice_id){
        //Удаляем товары
        $this->db->where('invoice_id', (int)$invoice_id);
        $this->db->delete('invoice_product');

        $this->db->where('id', (int)$invoice_id);
        $this->db->delete('invoice');
    }

    public function cancel($invoice_id){
        $this->db->where('id', (int)$invoice_id);
        $this->db->update('invoice', ['status_id' => 0]);
    }
}

