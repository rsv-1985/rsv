<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Waybill extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/waybill');
        $this->load->model('waybill_model');
        $this->load->model('delivery_model');
        $this->load->model('payment_model');
        $this->load->model('orderstatus_model');
    }

    public function index(){
        $data = [];

        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/waybill/index');
        $config['total_rows'] = $this->waybill_model->count_all();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $data['waybills'] = $this->waybill_model->get_all($config['per_page'], $this->uri->segment(4),false,['id' => 'DESC']);

        $this->load->view('admin/header');
        $this->load->view('admin/waybill/waybill', $data);
        $this->load->view('admin/footer');
    }

    public function create(){
        $save_waybill['created_at'] = date('Y-m-d H:i:s');
        $save_waybill['updated_at'] = date('Y-m-d H:i:s');
        $waybill_id = $this->waybill_model->insert($save_waybill);
        $this->session->set_flashdata('success', 'Путевой лист №'.$waybill_id.' создан. Добавьте заказы для отгрузки.');
        redirect('/autoxadmin/waybill');
    }

    public function edit($id){
        $data['orders'] = false;
        $data['waybill'] = $this->waybill_model->get($id);
        $data['waybill_id'] = (int)$id;
        $data['delivery_methods'] = $this->delivery_model->delivery_get_all();
        $data['order_statuses'] = $this->orderstatus_model->status_get_all();
        $data['parcels'] = $this->waybill_model->get_parcels($data['waybill_id']);

        if($this->input->get()){
            $orders = $this->waybill_model->get_orders();
            if($orders){
                foreach ($orders as &$order){
                    $order['products'] = $this->waybill_model->get_products($order['id'],$this->input->get('status_id'));
                }
                $data['orders'] = $orders;
            }
        }

        if($this->input->post()){
            $this->load->library('sender');
            $contacts = $this->settings_model->get_by_key('contact_settings');

            $save['status_id'] = (int)$this->input->post('status_id');
            $save['updated_at'] = date("Y-m-d H:i:s");
            $this->waybill_model->insert($save,$id);

            //Прописываем ТТН
            foreach ($this->input->post('ttn') as $parcel_id => $value){
                if($value != ''){
                    $this->waybill_model->insert_parcel(['ttn' => $value],$parcel_id);
                    if($data['parcels'][$parcel_id]['email'] != '' && $save['status_id']){
                        $this->sender->email('Номер ТТН',$value, $data['parcels'][$parcel_id]['email'],explode(';',$contacts['email']));
                    }
                }

            }

            //Обновлесм статусы товаров
            if($this->input->post('order_product_status_id')){
                $this->waybill_model->set_status_order_product($id,$this->input->post('order_product_status_id'));
            }
            $this->session->set_flashdata('success', 'OK');
            redirect('/autoxadmin/waybill');
        }



        $this->load->view('admin/header');
        $this->load->view('admin/waybill/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id){
        $this->waybill_model->delete($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('/autoxadmin/waybill');
    }

    public function add_order(){
        $json = [];

        if($this->input->post()){
            $this->load->model('order_model');

            $order_id = (int)$this->input->post('order_id');
            $waybill_id = (int)$this->input->post('waybill_id');
            $status_id = (int)$this->input->post('status_id');
            $order_info = $this->order_model->get($order_id);
            if($order_info){
                $products = $this->waybill_model->get_products($order_info['id'],$status_id);
                if($products){
                    $parcel_data = [
                        'waybill_id' => $waybill_id,
                        'customer_id' => $order_info['customer_id'],
                        'first_name' => $order_info['first_name'],
                        'last_name' => $order_info['last_name'],
                        'patronymic' => $order_info['patronymic'],
                        'delivery_method_id' => $order_info['delivery_method_id'],
                        'delivery_method' => (string)$this->delivery_model->get($order_info['delivery_method_id'])['name'],
                        'payment_method_id' => $order_info['payment_method_id'],
                        'payment_method' => (string)$this->payment_model->get($order_info['payment_method_id'])['name'],
                        'telephone' => $order_info['telephone'],
                        'email' => $order_info['email'],
                        'address' => $order_info['address'],
                        'total' => $order_info['total'],
                        'paid' => $order_info['paid']
                    ];

                    //Проверяем нет ли получателя в путевом листе
                    $parcel_id = $this->waybill_model->get_parcel_by_customer($parcel_data);
                    $json['success'] = 'Товары добавлены в существующую поссылку №'.$parcel_id;
                    if(!$parcel_id){
                        $parcel_id = $this->waybill_model->insert_parcel($parcel_data);
                        $json['success'] = 'Поссылка №'.$parcel_id.' создана';
                    }

                    foreach ($products as $product){
                        $save[] = [
                            'waybill_parcel_id' => $parcel_id,
                            'order_product_id' => $product['id'],
                            'order_id' => $order_info['id']
                        ];
                    }

                    $this->waybill_model->insert_product_batch($save);


                }else{
                    $json['error'] = 'Нет товаров';
                }
            }else{
                $json['error'] = 'Заказ не найден';
            }
        }else{
            $json['error'] = 'Не правильный запрос';
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function delete_waybill_product(){
        $waybill_product_id = $this->input->post('id');
        $this->waybill_model->delete_waybill_product($waybill_product_id);
        $this->session->set_flashdata('success', 'Товар удален с посылки');
    }

    public function delete_waybill_parcel(){
        $waybill_parcel_id = $this->input->post('id');
        $this->waybill_model->delete_waybill_parcel($waybill_parcel_id);
        $this->session->set_flashdata('success', 'Посылка и товары в ней удалены');
    }
}