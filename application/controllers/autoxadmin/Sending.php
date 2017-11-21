<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sending extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index(){
        $data = [];
        if($this->input->post()){
            $emails = [];
            $phones = [];

            if($this->input->post('email')){
                $emails[] = $this->input->post('email',true);
            }

            if($this->input->post('phone')){
                $phones[] = $this->input->post('phone',true);
            }

            if($this->input->post('newsletter')){
                $newsletter = $this->db->distinct()->select('email')->get('newsletter')->result_array();
                if($newsletter){
                    foreach ($newsletter as $newsletter){
                        $emails[] = $newsletter['email'];
                    }
                }
            }

            if($this->input->post('customer')){
                $customer_emails = $this->db->distinct()->select('email')->get('customer')->result_array();
                if($customer_emails){
                    foreach ($customer_emails as $customer_email){
                        $emails[] = $customer_email['email'];
                    }
                }
                $customer_phones = $this->db->distinct()->select('phone')->get('customer')->result_array();
                if($customer_phones){
                    foreach ($customer_phones as $customer_phone){
                        $phones[] = $customer_phone['phone'];
                    }
                }
            }

            if($this->input->post('orders')){
                $order_emails = $this->db->distinct()->select('email')->get('order')->result_array();
                if($order_emails){
                    foreach ($order_emails as $order_email){
                        $emails[] = $order_email['email'];
                    }
                }
                $order_phones = $this->db->distinct()->select('telephone')->get('order')->result_array();
                if($order_phones){
                    foreach ($order_phones as $order_phone){
                        $phones[] = $order_phone['telephone'];
                    }
                }
            }

            $emails = array_diff($emails,['']);
            $phones = array_diff($phones,['']);

            if($this->input->post('send_email') && $emails){
                $contacts = $this->settings_model->get_by_key('contact_settings');
                $unique_emails = array_unique($emails);
                $this->load->library('email');
                if($smtp_config = $this->config->item('smtp')){
                    foreach ($smtp_config as $key => $value){
                        $config[$key] = $value;
                    }
                    $this->email->initialize($config);
                }

                $this->email->from($contacts['email'],$this->config->item('company_name'));


                $this->email->reply_to($contacts['email'],$this->config->item('company_name'));
                $this->email->to($unique_emails[0]);

                unset($unique_emails[0]);
                if(count($unique_emails)){
                    $this->email->bcc($unique_emails);
                }

                $this->email->mailtype = 'html';
                $this->email->subject($this->input->post('subject',true));
                $this->email->message($this->input->post('text',true));
                try {
                    if(!$this->email->send()){
                        echo $this->email->print_debugger();
                    }
                } catch (Exception $e) {
                    //echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                }
            }

            if($this->input->post('send_sms') && $phones){
                $unique_phones = array_unique($phones);
                $this->load->library('sender');
                foreach ($unique_phones as $phone){
                    $this->sender->sms($phone,$this->input->post('text',true));
                }
            }
        }
        $this->load->view('admin/header');
        $this->load->view('admin/sending/sending', $data);
        $this->load->view('admin/footer');
    }
}