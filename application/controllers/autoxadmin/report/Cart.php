<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->language('admin/report/cart');
        $this->load->model('cart_model');
        $this->load->model('customer_model');
    }

    public function index(){
        $data = [];
        $this->load->library('pagination');

        $config['base_url'] = base_url('autoxadmin/report/cart/index');
        $config['total_rows'] = $this->cart_model->count_all();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);

        $data['carts'] = [];

        $carts = $this->cart_model->get_all($config['per_page'], $this->uri->segment(5));
        if($carts){
            foreach ($carts as $cart){

                $customer_info = $this->customer_model->get($cart['customer_id']);
                $cart_contents = unserialize($cart['cart_data']);

                $cart_total = $cart_contents['cart_total'];
                unset($cart_contents['cart_total']);
                unset($cart_contents['total_items']);

                $data['carts'][] = [
                    'customer_id' => $cart['customer_id'],
                    'customer' => $customer_info ? '<a target="_blank" href="/autoxadmin/customer/edit/'.$customer_info['id'].'">'.$customer_info['first_name'].' '.$customer_info['second_name'].'</a>' : '---',
                    'cart_total' => $cart_total,
                    'products' => $cart_contents,
                    'comment' => $cart['comment']
                ];
            }
        }

        $this->load->view('admin/header');
        $this->load->view('admin/report/cart/cart', $data);
        $this->load->view('admin/footer');
    }

    public function delete($customer_id){
        $this->cart_model->delete($customer_id);
        redirect('/autoxadmin/report/cart');
    }

    public function addcomment(){
        $customer_id = $this->input->post('customer_id');
        $comment = $this->input->post('comment');
        if($customer_id && $comment){
            $this->cart_model->addComment($comment,$customer_id);
        }
    }
}