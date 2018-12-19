<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends Admin_controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_model');
        $this->load->model('order_product_model');
        $this->load->model('orderstatus_model');
    }

    public function change_status(){
        if($this->input->get()){
            $status_id  = (int)$this->input->post('status_id');
            $product_status_id = (int)$this->input->post('product_status_id');

            $this->db->select('id');
            $this->db->set('status_id',(int)$this->input->post('status_id'));
            if($this->input->get('customer_id')){
                $this->db->where('customer_id', (int)$this->input->get('customer_id'));
            }

            if(isset($_GET['status_id']) && in_array($_GET['status_id'],['0','2'])){
                $this->db->where('status_id', (int)$this->input->get('status_id'));
            }else{
                $this->db->where('status_id !=', (int)1);
            }

            if($this->input->get('date_from')){
                $this->db->where('created_at >=', (string)$this->input->get('date_from', true), false);
            }

            if($this->input->get('date_to')){
                $this->db->where('created_at <=', (string)$this->input->get('date_to', true), false);
            }

            $invoices = $this->db->get('ax_invoice')->result_array();

            if($invoices){
                //Если статус проведет, делаем проводку
                if($status_id == 1){
                    foreach ($invoices as $invoice){
                        $this->_provodka($invoice['id']);
                        $this->db->where('id',$invoice['id']);
                        $this->db->set('status_id',1);
                        $this->db->update('invoice');
                    }
                }else{
                    foreach ($invoices as $invoice){
                        $this->db->where('id',$invoice['id']);
                        $this->db->set('status_id',$status_id);
                        $this->db->update('invoice');
                    }
                }
            }
        }
    }

    public function index(){
        $data['invoices'] = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/invoice/index');
        $config['per_page'] = 50;

        $invoices= $this->invoice_model->invoice_get_all($config['per_page'], $this->uri->segment(4));
        if($invoices){
            foreach ($invoices as $invoice){
                $data['invoices'][format_date($invoice['created_at'])][] = $invoice;
            }
        }


        $config['total_rows'] = $this->invoice_model->total_rows;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $data['statuses'] = $this->invoice_model->statuses;
        $data['order_statuses'] = $this->orderstatus_model->status_get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/invoice/index', $data);
        $this->load->view('admin/footer');
    }

    public function delete_product($product_id){
        $this->invoice_model->deleteProduct($product_id);
        $this->session->set_flashdata('success', lang('text_success'));
    }

    private function _provodka($id){
        $products = $this->invoice_model->getProducts($id);

        if($products){

            foreach ($products as $product){
                //Обновляем статус тоара
                $this->db->where('id',$product['order_product_id']);
                $this->db->set('status_id',(int)$this->input->post('product_status_id', true));
                $this->db->update('order_product');


                //Разбиваем товары в заказе если не соответствует количество
                //Если количество не соответствует в заказе
                if($product['quantity'] != $product['iqty']){

                    $new_quantity = $product['quantity'] - $product['iqty'];
                    //Обновляем количество в заказе
                    $this->db->where('id',$product['order_product_id']);
                    $this->db->set('quantity',(int)$product['iqty']);
                    $this->db->set('delivery_price',$product['delivery_price'] / $product['quantity'] * (int)$product['iqty']);
                    $this->db->update('order_product');

                    //Добавляем товар
                    $product = [
                        'order_id' => $product['order_id'],
                        'product_id' => $product['product_id'],
                        'slug' => $product['slug'],
                        'quantity' => $new_quantity,
                        'delivery_price' => $product['delivery_price'] / $product['quantity'] * $new_quantity,
                        'price' => $product['price'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'brand' => $product['brand'],
                        'supplier_id' => $product['supplier_id'],
                        'status_id' => $product['status_id'],
                        'term' => (int)$product['term'],
                        'excerpt' => (string)$product['excerpt']
                    ];

                    $this->order_product_model->insert($product);
                }
            }

            //Списываем сумму инвойса с баланса клиента
            $invoice_total = $this->invoice_model->getTotal($id);
            $invoice_info = $this->invoice_model->get($id);

            $this->customerbalance_model->add_transaction(
                $invoice_info['customer_id'],
                $invoice_total,
                lang('text_invoice').' '.$id,
                2,
                date('Y-m-d H:i:s'),
                $this->session->userdata('user_id'),
                $id
            );
        }
    }

    private function _save_data($id = false){

        $save['status_id'] = (int)$this->input->post('status_id');
        $save['delivery_price'] = (float)$this->input->post('delivery_price');
        $id = $this->invoice_model->insert($save, $id);

        //Обновляем количество в товарах
        if($this->input->post('products')){
            foreach ($this->input->post('products') as $product_id => $product){
                $this->invoice_model->updateProductQty($product_id,$product['qty']);
            }
        }

        //Если статус инвойса проведен
        if($save['status_id'] == 1 && $id){
            $this->_provodka($id);
        }
    }

    public function edit($id){
        $data['invoice_info'] = $this->invoice_model->get($id);
        if (!$data['invoice_info']) {
            show_404();
        }

        if($this->input->post()){
            $this->_save_data($id);
            $this->session->set_flashdata('success', lang('text_success'));
            redirect('autoxadmin/invoice/edit/' . $id);
        }

        $data['customer_info'] = $this->customer_model->get($data['invoice_info']['customer_id']);
        $data['statuses'] = $this->invoice_model->statuses;
        $data['product_statuses'] = $this->orderstatus_model->status_get_all();
        $data['products'] = $this->invoice_model->getProducts($id);

        $data['total'] = $this->invoice_model->getTotal($id);

        $this->load->view('admin/header');
        $this->load->view('admin/invoice/edit', $data);
        $this->load->view('admin/footer');
    }

    public function add(){
        $type = $this->input->post('type');
        $data = $this->input->post('data');
        $messages = '';
        switch ($type){
            case 'order':
                $messages = $this->invoice_model->addByOrder($data);
                break;
            case 'item':
                $messages = $this->invoice_model->addByItem($data);
                break;
            case 'filter':
                $messages = $this->invoice_model->addByFilter($data);
                break;
        }

        exit($messages);
    }

    public function view($id){
        $data['invoice_info'] = $this->invoice_model->get($id);
        $data['invoice_products'] = $this->invoice_model->getProducts($id);
        $data['total'] = $this->invoice_model->getTotal($id);
        $data['customer_info'] = $this->customer_model->get($data['invoice_info']['customer_id']);

        $this->load->view('customer/invoice_view', $data);
    }

    public function delete($id){
        //Отменяем транзакцию
        $balance_info = $this->customerbalance_model->getByInvoice($id);
        if($balance_info){
            $this->customerbalance_model->delete($balance_info['id']);
            $this->session->set_flashdata('success', 'Транзакция отменена. Баланс пересчитан');
        }

        $this->invoice_model->delete($id);

        redirect('/autoxadmin/invoice');
    }

    public function cancel($id){
        //Отменяем транзакцию
        $balance_info = $this->customerbalance_model->getByInvoice($id);
        if($balance_info){
            $this->customerbalance_model->delete($balance_info['id']);
            $this->session->set_flashdata('success', 'Транзакция отменена. Баланс пересчитан');
        }

        $this->invoice_model->cancel($id);

        redirect('/autoxadmin/invoice/edit/'.$id);
    }
}
