<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Np extends CI_Controller
{
    private $params;
    public function __construct()
    {
        parent::__construct();
        $this->params = $this->settings_model->get_by_key('np');
        $this->load->library('novaposhta',$this->params);
        $this->load->library('sender');
        $this->load->model('order_model');
        $this->load->model('delivery/np_model');
        $this->load->model('message_template_model');
    }

    public function create_en(){

        $order_info = $this->order_model->get($this->input->post('order_id',true));
        if($order_info){
            $save['RecipientCityName'] = $this->input->post('RecipientCityName',true);
            $save['RecipientArea'] = $this->input->post('RecipientArea',true);
            $save['RecipientAreaRegions'] = $this->input->post('RecipientAreaRegions',true);
            if($this->input->post('RecipientAddressName',true)){
                $save['RecipientAddressName'] = $this->input->post('RecipientAddressName',true);
                $save['RecipientAddressName2'] = '';
            }else{
                $save['RecipientAddressName'] = '';
                $save['RecipientAddressName2'] = $this->input->post('RecipientAddressName2',true);
            }

            $save['RecipientHouse'] = $this->input->post('RecipientHouse',true);
            $save['RecipientFlat'] = $this->input->post('RecipientFlat',true);

            if($this->input->post('np_id')){
                $np_id = (int)$this->input->post('np_id');
            }else{
                $np_id = false;
                $save['order_id'] = $order_info['id'];
            }
            $this->np_model->insert($save,$np_id);

            if($this->input->post('create_ttn')){
                $result = $this->novaposhta->InternetDocument($this->input->post());
                if($result['success']){
                    $this->load->model('order_ttn_model');
                    $save2 = [];
                    $save2['order_id'] = (int)$this->input->post('order_id',true);
                    $save2['ttn'] = $result['data'][0]['IntDocNumber'];
                    $save2['library'] = 'np';
                    $save2['data'] = json_encode($result['data']);
                    $this->order_ttn_model->insert($save2);

                    $message_template = $this->message_template_model->get(6);

                    $sms_text = str_replace(
                        ['{ttn}', '{seats}', '{cost}'],
                        [$save2['ttn'], $this->input->post('SeatsAmount',true), $result['data'][0]['CostOnSite']],
                        $message_template['text_sms']
                    );

                    if($this->input->post('send_sms') && $order_info['telephone']){
                        $this->sender->sms($order_info['telephone'], $sms_text);
                    }

                    $email_text = str_replace(
                        ['{ttn}', '{seats}', '{cost}'],
                        [$save2['ttn'], $this->input->post('SeatsAmount',true), $result['data'][0]['CostOnSite']],
                        $message_template['text']
                    );

                    $email_subject = str_replace(
                        ['{ttn}', '{seats}', '{cost}'],
                        [$save2['ttn'], $this->input->post('SeatsAmount',true), $result['data'][0]['CostOnSite']],
                        $message_template['subject']
                    );

                    if($this->input->post('send_email') && $order_info['email']){
                        $contacts = $this->settings_model->get_by_key('contact_settings');
                        $this->sender->email($email_subject, $email_text, explode(';', $contacts['email']));
                    }

                }else{
                    foreach ($result['errors'] as $error){
                        echo $error.'\n';
                    }
                }
            }

        }
    }

    public function searchSettlements(){
        $json  = [];
        $city = $this->input->get('city',true);
        $json = $this->novaposhta->searchSettlements($city);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function getWarehouses()
    {
        $ref = $this->input->get('Ref');
        $json = $this->novaposhta->getWarehouses($ref);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function getSenders(){
        $json = $this->novaposhta->getCounterparties('Sender');
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function getContactSender(){
        $Ref = $this->input->get('Ref');
        $json = $this->novaposhta->getCounterpartyContactPerson($Ref);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function getSenderAddress(){
        $Ref = $this->input->get('Ref');
        $json = $this->novaposhta->getWarehouses($Ref);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }

    public function delete_en(){
        $order_ttn_id = $this->input->get('id');
        $this->load->model('order_ttn_model');
        $order_ttn_info = $this->order_ttn_model->get($order_ttn_id);

        if($order_ttn_info){
            $this->order_ttn_model->delete($order_ttn_id);
            $data = json_decode($order_ttn_info['data']);

            $result = $this->novaposhta->InternetDocumentDelete([$data[0]->Ref]);
            if($result['success']){

                redirect('/autoxadmin/order/edit/'.$order_ttn_info['order_id']);
            }else{
                print_r($result['errors']);
            }
        }
    }

    public function print_delivery(){
        $order_ttn_id = $this->input->get('id');
        $this->load->model('order_ttn_model');
        $order_ttn_info = $this->order_ttn_model->get($order_ttn_id);
        if($order_ttn_info){
            redirect('https://my.novaposhta.ua/orders/printDocument/orders[]/'.$order_ttn_info['ttn'].'/type/pdf/apiKey/'.$this->params['api_key']);
        }
    }
}