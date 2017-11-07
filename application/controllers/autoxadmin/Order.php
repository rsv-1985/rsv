<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/order');
        $this->load->model('order_model');
        $this->load->model('order_product_model');
        $this->load->model('orderstatus_model');
        $this->load->model('payment_model');
        $this->load->model('delivery_model');
        $this->load->model('supplier_model');
        $this->load->model('order_history_model');
        $this->load->model('settings_model');
        $this->load->model('message_template_model');
        $this->load->model('product_model');
        $this->load->library('sender');
    }

    public function products()
    {
        $data = [];
        $this->load->library('pagination');

        if ($this->input->post()) {
            $this->form_validation->set_rules('status_id', 'Статус', 'required|integer');
            $this->form_validation->set_rules('id', 'id', 'required|trim');

            if ($this->form_validation->run() !== false) {
                $save = [];
                $save['status_id'] = (int)$this->input->post('status_id');

                $this->order_product_model->insert($save, (int)$this->input->post('id'));
                exit(lang('text_success'));

            } else {
                exit(validation_errors());
            }
        }

        $config['base_url'] = base_url('autoxadmin/order/products');
        $config['per_page'] = 20;
        $data['products'] = $this->order_model->order_get_all_products($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->order_model->total_rows;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['suppliers'] = $this->supplier_model->supplier_get_all();
        $data['status_totals'] = $this->order_model->get_status_totals($data['status']);

        $this->load->view('admin/header');
        $this->load->view('admin/order/products', $data);
        $this->load->view('admin/footer');
    }

    public function index()
    {
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/order/index');
        $config['per_page'] = 30;
        $orders = $this->order_model->order_get_all($config['per_page'], $this->uri->segment(4));
        $config['total_rows'] = $this->order_model->total_rows;
        $config['reuse_query_string'] = true;

        $this->pagination->initialize($config);

        if($orders){
            foreach ($orders as &$order){
                $order['products_status'] = $this->order_model->get_products_status($order['id']);
            }
        }

        $data['orders'] = $orders;
        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['status_totals'] = $this->order_model->get_status_totals($data['status']);
        $data['payment'] = $this->payment_model->payment_get_all();
        $data['delivery'] = $this->delivery_model->delivery_get_all();

        $this->load->view('admin/header');
        $this->load->view('admin/order/order', $data);
        $this->load->view('admin/footer');
    }

    public function create()
    {

    }

    public function edit($id)
    {

        $data = [];
        $data['order'] = $this->order_model->get($id);
        if (!$data['order']) {
            show_404();
        }
        $data['customer_info'] = $this->customer_model->get($data['order']['customer_id']);

        $settings_fraud = $this->settings_model->get_by_key('scamdb');
        $data['scamdb_info'] = false;
        if (@$settings_fraud['access_token']) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://scamdb.info/ru/v1/fraud/find?search=' . $data['order']['telephone'] . '&access-token=' . $settings_fraud['access_token']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl, CURLOPT_TIMEOUT, 2);
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result);

            if (is_array($result)) {
                $data['scamdb_info'] = '<a href="http://scamdb.info/ru/fraud/' . @$result[0]->id . '" target="_blank">Обнаружен в базе scamdb.info</a>';
            }
        }
        $data['status'] = $this->orderstatus_model->status_get_all();
        $data['payment'] = $this->payment_model->payment_get_all();
        $data['delivery'] = $this->delivery_model->delivery_get_all();
        $data['supplier'] = $this->supplier_model->supplier_get_all();
        $data['history'] = $this->order_history_model->history_get($id);
        $data['products'] = $this->order_product_model->get_all(false, false, ['order_id' => (int)$data['order']['id']]);

        if ($this->input->post()) {
            $this->form_validation->set_rules('delivery_method', lang('text_delivery_method'), 'required|integer');
            $this->form_validation->set_rules('payment_method', lang('text_payment_method'), 'required|integer');
            $this->form_validation->set_rules('first_name', lang('text_first_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('last_name', lang('text_last_name'), 'required|max_length[250]');
            $this->form_validation->set_rules('patronymic', lang('text_patronymic'), 'max_length[255]');
            $this->form_validation->set_rules('telephone', lang('text_telephone'), 'required|max_length[32]');
            $this->form_validation->set_rules('email', 'email', 'valid_email');
            $this->form_validation->set_rules('comment', lang('text_comment'), 'max_length[3000]');
            $this->form_validation->set_rules('address', lang('text_address'), 'max_length[3000]');
            if ($this->form_validation->run() !== false) {

                $delivery_price = (float)$this->input->post('delivery_price');
                $commissionpay = 0;
                $total = 0;
                $return_order_status_id = $this->orderstatus_model->get_return()['id'];

                if($this->input->post('set_products_status')){
                    $order_status_id = (int)$this->input->post('status', true);
                }else{
                    $order_status_id = '---';
                }
                if ($this->input->post('products')) {
                    foreach ($this->input->post('products') as $product) {
                        if((bool)$this->input->post('set_products_status')){
                            $product['status_id'] = (int)$this->input->post('status');
                        }
                        if($return_order_status_id != $product['status_id'] && $return_order_status_id != $order_status_id){
                            $total += $product['quantity'] * $product['price'];
                        }
                    }
                }

                $payment_id = (int)$this->input->post('payment_method', true);
                if ($payment_id) {
                    $paymentInfo = $this->payment_model->get($payment_id);
                    if ($paymentInfo['fix_cost'] > 0 || $paymentInfo['comission'] > 0) {
                        if ($paymentInfo['comission'] > 0) {
                            $commissionpay = $paymentInfo['comission'] * ($total + $delivery_price) / 100;
                        }
                        if ($paymentInfo['fix_cost'] > 0) {
                            $commissionpay = $commissionpay + $paymentInfo['fix_cost'];
                        }
                    }
                }


                $total = $total + $delivery_price + $commissionpay;

                $save = [];
                $save['customer_id'] = $this->input->post('customer_id', true);
                $save['first_name'] = $this->input->post('first_name', true);
                $save['last_name'] = $this->input->post('last_name', true);
                $save['patronymic'] = $this->input->post('patronymic', true);
                $save['email'] = $this->input->post('email', true);
                $save['telephone'] = $this->input->post('telephone', true);
                $save['delivery_method_id'] = (int)$this->input->post('delivery_method');
                $save['payment_method_id'] = (int)$this->input->post('payment_method');
                $save['address'] = $this->input->post('address', true);
                $save['total'] = (float)$total;
                $save['created_at'] = date('Y-m-d H:i:s');
                $save['updated_at'] = date('Y-m-d H:i:s');
                $save['status'] = (int)$this->input->post('status', true);
                $save['commission'] = (float)$commissionpay;
                $save['delivery_price'] = (float)$delivery_price;
                $save['paid'] = (bool)$this->input->post('paid', true);
                $save['prepayment'] = (float)$this->input->post('prepayment');
                $order_id = $this->order_model->insert($save, $id);

                //Если была предоплата
                if($save['prepayment'] && $save['customer_id'] != 0 && $data['order']['prepayment'] != $save['prepayment']){
                    $description = 'Предоплата по заказу №'.$order_id;
                    $this->customerbalance_model->add_transaction($save['customer_id'],$save['prepayment'], $description, 1, '', $this->session->userdata('user_id'));
                }

                //Возвращием или списываем деньги с баланса клиента если это
                if($save['customer_id'] != 0 && $data['order']['paid']){
                    $value = $data['order']['total'] - $save['total'];
                    if($value > 0){
                        //Возвращаем деньги
                        $description = 'Сумма заказ №'.$order_id.' изменилась с '.$data['order']['total'].' на '.$save['total'];
                        $this->customerbalance_model->add_transaction($save['customer_id'],$value, $description, 1, '', $this->session->userdata('user_id'));
                    }else if($value < 0){
                        $description = 'Сумма заказ №'.$order_id.' изменилась с '.$data['order']['total'].' на '.$save['total'];
                        $this->customerbalance_model->add_transaction($save['customer_id'],-$value, $description, 2, '', $this->session->userdata('user_id'));
                    }
                }
                //Возвращаем товары на склад если у поставщик отмечено "Наш склад"
                if ($data['products']) {
                    foreach ($data['products'] as $return_product) {
                        $supplier_info = $this->supplier_model->get($return_product['supplier_id']);
                        if ($supplier_info['stock']) {
                            $this->product_model->update_stock($return_product, '+');
                        }
                    }
                }

                if ($order_id) {
                    foreach ($this->input->post('products') as $product_id => $item) {
                        $product = [
                            'order_id' => $order_id,
                            'slug' => $item['slug'],
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'delivery_price' => (float)$item['delivery_price'],
                            'price' => (float)$item['price'],
                            'name' => $item['name'],
                            'sku' => $item['sku'],
                            'brand' => $item['brand'],
                            'supplier_id' => (int)$item['supplier_id'],
                            'status_id' => $this->input->post('set_products_status') ? $save['status'] : $item['status_id'],
                            'term' => (int)$item['term']
                        ];

                        $this->order_product_model->insert($product,$product_id);
                    }


                    $this->session->set_flashdata('success', lang('text_success'));

                    $contacts = $this->settings_model->get_by_key('contact_settings');

                    //История по заказу
                    if ($this->input->post('history')) {
                        $history = [];
                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = $this->input->post('history', true);
                        $history['send_sms'] = false;
                        $history['send_email'] = false;
                        $history['user_id'] = $this->User_model->is_login();


                        if ($save['email'] != '' && (bool)$this->input->post('send_email')) {
                            $history['send_email'] = true;
                            $this->sender->email('Комментарий к заказу '.$order_id, $history['text'], $save['email'], explode(';', $contacts['email']));
                        }
                        if ($save['telephone'] != '' && (bool)$this->input->post('send_sms')) {
                            $history['send_sms'] = true;
                            $this->sender->sms($save['telephone'], $history['text']);
                        }

                        $this->order_history_model->insert($history);
                    }
                    //Предоплата
                    if($save['prepayment'] != $data['order']['prepayment']){
                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = 'Предоплата: '.$save['prepayment'];
                        $history['user_id'] = $this->User_model->is_login();
                        $this->order_history_model->insert($history);
                    }
                    //статус оплаты
                    if($save['paid'] != $data['order']['paid']){
                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = $save['paid'] ? 'Оплачен' : 'Не оплачен';
                        $history['user_id'] = $this->User_model->is_login();
                        $this->order_history_model->insert($history);
                    }
                    //order_status
                    if ($save['status'] != $data['order']['status']) {
                        $order_info = $save;
                        $order_info['order_id'] = $order_id;
                        $order_info['status'] = $data['status'][$save['status']]['name'];
                        $order_info['payment_method'] = $data['payment'][$save['payment_method_id']]['name'];
                        $order_info['delivery_method'] = $data['delivery'][$save['delivery_method_id']]['name'];
                        //Получаем шаблон сообщения 2 - Смена статуса заказа
                        $message_template = $this->message_template_model->get(2);
                        foreach ($order_info as $field => $value) {
                            if (in_array($field, ['total', 'commission', 'delivery_price'])) $value = format_currency($value);
                            $message_template['subject'] = str_replace('{' . $field . '}', $value, $message_template['subject']);
                            $message_template['text'] = str_replace('{' . $field . '}', $value, $message_template['text']);
                            $message_template['text'] = str_replace('{products}', $this->load->view('email/order', ['products' => $data['products']], true), $message_template['text']);
                            $message_template['text_sms'] = str_replace('{' . $field . '}', $value, $message_template['text_sms']);
                        }

                        //Добавляем историю смены статуса заказа
                        $history = [];

                        if ($save['email'] != '') {
                            $history['send_email'] = true;
                            $this->sender->email($message_template['subject'], $message_template['text'], $save['email'], explode(';', $contacts['email']));
                        }
                        if ($save['telephone'] != '') {
                            $history['send_sms'] = true;
                            $this->sender->sms($save['telephone'], $message_template['text_sms']);
                        }


                        $history['order_id'] = $order_id;
                        $history['date'] = date("Y-m-d H:i:s");
                        $history['text'] = $data['status'][$save['status']]['name'];
                        $history['user_id'] = $this->User_model->is_login();
                        $this->order_history_model->insert($history);

                    }
                    redirect('autoxadmin/order/edit/' . $id);
                }
            } else {
                $this->error = validation_errors();
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/order/edit', $data);
        $this->load->view('admin/footer');
    }

    public function delete($id)
    {
        $this->order_model->delete($id);
        $this->order_product_model->delete_by_order($id);
        $this->order_history_model->delete_by_order($id);
        $this->session->set_flashdata('success', lang('text_success'));
        redirect('autoxadmin/order');
    }

    //ajax сумма заказа при редактировании
    public function get_total()
    {
        $json = [];
        $delivery_price = 0;
        $commissionpay = 0;
        $total = 0;
        $delivery_total = 0;
        $revenue = 0;

        $return_order_status_id = $this->orderstatus_model->get_return()['id'];

        if ($this->input->post('products')) {
            foreach ($this->input->post('products') as $product) {
                if((bool)$this->input->post('set_products_status')){
                    $product['status_id'] = (int)$this->input->post('status');
                }
                if($product['status_id'] != $return_order_status_id){
                    $total += $product['quantity'] * $product['price'];
                    $delivery_total += $product['delivery_price'];
                }
            }
        }

        $revenue = $total - $delivery_total;

        $delivery_id = (int)$this->input->post('delivery_method', true);
        if ($delivery_id) {

            $deliveryInfo = $this->delivery_model->get($delivery_id);
            if ($this->input->post('delivery_price')) {
                $delivery_price = (float)$this->input->post('delivery_price');
            } else {
                $delivery_price = (float)$deliveryInfo['price'];
            }

            $json['delivery_description'] = $deliveryInfo['description'];
        }


        $payment_id = (int)$this->input->post('payment_method', true);
        if ($payment_id) {
            $paymentInfo = $this->payment_model->get($payment_id);
            if ($paymentInfo['fix_cost'] > 0 || $paymentInfo['comission'] > 0) {
                if ($paymentInfo['comission'] > 0) {
                    $commissionpay = $paymentInfo['comission'] * ($total + $delivery_price) / 100;
                }
                if ($paymentInfo['fix_cost'] > 0) {
                    $commissionpay = $commissionpay + $paymentInfo['fix_cost'];
                }
            }
        }

        $json['delivery_price'] = $delivery_price;
        $json['commission'] = $commissionpay;
        $json['subtotal'] = $total;
        $json['delivery_total'] = $delivery_total;
        $json['total'] = $total + $delivery_price + $commissionpay;
        $json['revenue'] = $revenue;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    //Обновление суммы заказа после удаления или добавления позиции
    private function _get_total($order_id){
        $delivery_price = 0;
        $commissionpay = 0;
        $total = 0;

        $order_info = $this->order_model->get($order_id);
        $return_order_status_id = $this->orderstatus_model->get_return()['id'];
        $products = $this->order_product_model->get_all(false, false, ['order_id' => (int)$order_id]);
        if ($products) {
            foreach ($products as $product) {
                if((bool)$this->input->post('set_products_status')){
                    $product['status_id'] = (int)$this->input->post('status');
                }
                if($product['status_id'] != $return_order_status_id){
                    $total += $product['quantity'] * $product['price'];
                }
            }
        }

        $delivery_price = $order_info['delivery_price'];


        $payment_id = $order_info['payment_method_id'];
        if ($payment_id) {
            $paymentInfo = $this->payment_model->get($payment_id);
            if ($paymentInfo['fix_cost'] > 0 || $paymentInfo['comission'] > 0) {
                if ($paymentInfo['comission'] > 0) {
                    $commissionpay = $paymentInfo['comission'] * ($total + $delivery_price) / 100;
                }
                if ($paymentInfo['fix_cost'] > 0) {
                    $commissionpay = $commissionpay + $paymentInfo['fix_cost'];
                }
            }
        }

        $save['commission'] = $commissionpay;
        $save['delivery_price'] = $delivery_price;
        $save['total'] = $total + $delivery_price + $commissionpay;

        //Возвращием или списываем деньги с баланса клиента если это
        if($order_info && $order_info['customer_id'] != 0 && $order_info['payment_method_id'] == 0 && $order_info['paid']){
            $value = $order_info['total'] - $save['total'];
            if($value > 0){
                $description = 'Сумма заказ №'.$order_id.' изменилась с '.$order_info['total'].' на '.$save['total'];
                $this->customerbalance_model->add_transaction($order_info['customer_id'],$value, $description, 1, '', $this->session->userdata('user_id'));
            }else if($value < 0){
                $description = 'Сумма заказ №'.$order_id.' изменилась с '.$order_info['total'].' на '.$save['total'];
                $this->customerbalance_model->add_transaction($order_info['customer_id'],-$value, $description, 2, '', $this->session->userdata('user_id'));
            }
        }

        $this->order_model->insert($save,$order_id);
    }

    //Добавление товара
    public function add_product()
    {

        $product_id = (int)$this->input->post('product_id');
        $supplier_id = (int)$this->input->post('supplier_id');
        $term = (int)$this->input->post('term');
        $order_id = (int)$this->input->post('order_id');
        $results = $this->product_model->get_product_for_cart($product_id, $supplier_id, $term);
        $order_info = $this->order_model->get($order_id);
        if ($results) {
            $product = [
                'order_id' => $order_id,
                'product_id' => $product_id,
                'slug' => $results['slug'],
                'supplier_id' => $supplier_id,
                'quantity' => 1,
                'delivery_price' => $results['delivery_price'] * $this->currency_model->currencies[$results['currency_id']]['value'],
                'price' => 0,
                'name' => $results['name'],
                'sku' => $results['sku'],
                'brand' => $results['brand'],
                'status_id' => 0,
                'term' => $results['term']
            ];

            $this->order_product_model->insert($product);
            $this->_get_total($order_id);
            exit('success');
        } else {
            exit('error');
        }
    }

    //Удаление товара с заказа
    public function delete_product(){
        $product_id = (int)$this->input->get('product_id');
        $order_id = (int)$this->input->get('order_id');
        if($product_id && $order_id){
            $this->order_product_model->delete($product_id);
            $this->_get_total($order_id);
        }
        redirect('/autoxadmin/order/edit/'.$order_id);
    }

    /**
     * Метод получения товаровр при добавлениее в заказе
     * @html
     */
    public function search_products()
    {
        $search = $this->input->post('search', true);
        $products = $this->product_model->get_search_text($search);
        $html = 'Ничего не найдено';
        if ($products) {
            $html = '<ul class="list-group">';
            foreach ($products as $product) {
                $html .= '<li class="list-group-item">' . $this->supplier_model->suppliers[$product['supplier_id']]['name'] . ' ' . $product['name'] . ' ' . $product['sku'] . ' ' . $product['brand'] . ' ' . format_currency($product['price']) . ' ' . format_term($product['term']) . '<a href="#" onclick="add_product(' . $product['id'] . ',' . $product['supplier_id'] . ',' . $product['term'] . '); return false;"> Добавить</a> </li>';
            }
            $html .= '</ul>';
        }
        exit($html);
    }

    //Массове редактирование статусов товаров в заказах
    public function change_status_products(){
        $this->db->set('status_id',(int)$this->input->post('status_id'));
        if($this->input->get()){
            foreach ($this->input->get() as $field =>$value){
                if($value != ''){
                    $this->db->where($field,$value);
                }
            }
        }
        $query = $this->db->update('order_product');
        $this->session->set_flashdata('success', lang('text_success'));
        exit('success');
    }

    public function export_xls()
    {
        require_once './application/libraries/excel/PHPExcel.php';

        $this->load->model('order_model');
        $products = $this->order_product_model->get_all(false, false, $this->input->get());
        if ($products) {
            $columns = array_keys($products[0]);

            $phpexcel = new PHPExcel();
            $page = $phpexcel->setActiveSheetIndex(0);

            $c = 0;
            foreach ($columns as $column) {
                $page->setCellValueByColumnAndRow($c, 1, $column);
                $c++;
            }


            $row = 2;
            foreach ($products as $product) {
                $c = 0;
                foreach ($product as $column => $value){
                    switch ($column){
                        case 'supplier_id':
                            $value = @$this->supplier_model->suppliers[$value]['name'];
                            break;
                        case 'status_id':
                            $statuses = $this->orderstatus_model->status_get_all();
                            $value = @$statuses[$value]['name'];
                            break;
                    }
                    $page->setCellValueByColumnAndRow($c, $row, $value);
                    $c++;
                }
                $row++;
            }


            $sheet = $phpexcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }

            $page->setTitle("order_products");
            $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
            // We'll be outputting an excel file
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="order_products.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
        }

        exit();
    }

    public function importstatus(){
        $data['statuses'] = $this->orderstatus_model->status_get_all();

        if(isset($_FILES['userfile'])){
            $this->form_validation->set_rules('userfile', 'File', 'file');
            if ($this->form_validation->run() !== false){
                $status_id = (int)$this->input->post('status_id');
                $new_status_id = (int)$this->input->post('new_status_id');
                $supplier_id = (int)$this->input->post('supplier_id');
                $data['products'] = [];
                $data['similar_products'] = [];
                $data['error_products'] = [];

                //Подключаме бтблиотеку для работы с xls
                error_reporting(E_ALL ^ E_NOTICE);
                require_once APPPATH . 'libraries/excel_reader2.php';
                $file_name = $_FILES['userfile']['tmp_name'];
                $excel = new Spreadsheet_Excel_Reader($file_name, false);
                if ($excel->sheets[0]['numRows'] > 0) {
                    for ($i = 2; $i <= $excel->sheets[0]['numRows']; $i++) {
                        $sku = $this->product_model->clear_sku($excel->sheets[0]['cells'][$i][1]);
                        $quan = $this->product_model->clear_quan($excel->sheets[0]['cells'][$i][3]);
                        $brand = $this->product_model->clear_brand($excel->sheets[0]['cells'][$i][2]);
                        if($sku && $quan){
                            //Ищем точное совпадение по заказу
                            $result = $this->db->where('supplier_id',$supplier_id)
                                ->where('sku',$sku)
                                ->where('quantity',(int)$quan)
                                ->where('brand',$brand)
                                ->where('status_id',(int)$status_id)
                                ->get('order_product')->row_array();
                            if($result){
                                $data['products'][] = [
                                    'product_id' => $result['id'],
                                    'order_id' => $result['order_id'],
                                    'sku_order' => $result['sku'],
                                    'brand_order' => $result['brand'],
                                    'quan_order' => $result['quantity'],
                                    'sku_file' => $sku,
                                    'brand_file' => $brand,
                                    'quan_file' =>  $quan,
                                    'status_id' => $status_id,
                                    'new_status_id' => $new_status_id
                                ];
                            }else{
                                //Ищем похожее по заказу
                                $results = $this->db->where('supplier_id',$supplier_id)
                                    ->where('sku',$sku)
                                    ->where('status_id',(int)$status_id)
                                    ->where('supplier_id',(int)$supplier_id)
                                    ->where('brand',$brand)
                                    ->get('order_product')->result_array();
                                if($results){
                                    foreach ($results as $result){
                                        $data['similar_products'][] = [
                                            'product_id' => $result['id'],
                                            'order_id' => $result['order_id'],
                                            'sku_order' => $result['sku'],
                                            'brand_order' => $result['brand'],
                                            'quan_order' => $result['quantity'],
                                            'sku_file' => $sku,
                                            'brand_file' => $brand,
                                            'quan_file' =>  $quan,
                                            'status_id' => $status_id,
                                            'new_status_id' => $new_status_id
                                        ];
                                    }
                                }else{
                                    //Пишем в масив ненайденных
                                    $data['error_products'][] = [
                                        'sku' => $sku,
                                        'quan' => $quan
                                    ];
                                }
                            }
                        }
                    }
                }else{
                    exit('В импортируемом файле 0 строк');
                }
            }else{
                exit(validation_errors());
            }
            $this->load->view('admin/header');
            $this->load->view('admin/order/importstatus', $data);
            $this->load->view('admin/footer');
        }
    }

    public function update_status(){
        if($this->input->post('products')){
            foreach ($this->input->post('products') as $product){
                $this->db->where('id',$product['product_id'])->set('status_id',$product['status_id'])->update('order_product');
            }
        }
    }

    public function pay($order_id)
    {
        $orderInfo = $this->order_model->get($order_id);

        if(!$orderInfo['paid']){
            if ($this->customerbalance_model->add_transaction($orderInfo['customer_id'],$orderInfo['total'],'Оплата заказа №' . $orderInfo['id'])) {
                //Ставим ОПЛАЧЕН заказу и способ оплаты С БАЛАНСА
                $save3['paid'] = 1;
                $save3['payment_method_id'] = 0;

                $this->order_model->insert($save3, $orderInfo['id']);

                //Комментарий к заказу
                $this->load->model('order_history_model');
                $history['order_id'] = $order_id;
                $history['date'] = date("Y-m-d H:i:s");
                $history['text'] = 'Оплата заказа c баланса. Сумма '.$orderInfo['total'];
                $history['user_id'] = 0;
                $this->order_history_model->insert($history);

            }
        }
        $this->session->set_flashdata('success', 'Заказ оплачен');

        redirect('/autoxadmin/order/edit/'.$order_id);
    }
}