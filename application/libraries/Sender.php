<?php
/**
 * Developer: Распутний Сергей Викторович
 * Email: sergey.rasputniy@gmail.com
 * Company: lmg.link
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sender{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function email($subject, $body, $to = false, $from = false){

        if(!$to){
           return false;
        }
        if(!$from){
            return false;
        }
        if(is_array($to)){
            $to = implode(',',$to);
        }
        if(is_array($from)){
            $from = $from[0];
        }
        $this->CI->load->library('email');
        $this->CI->email->from($from, $this->CI->config->item('company_name'));
        $this->CI->email->to($to);
        $this->CI->email->mailtype = 'html';
        $this->CI->email->subject($subject);
        $this->CI->email->message($body);
        $this->CI->email->send();
    }

    function sms($phone,$text){
        $login = $this->CI->config->item('smsc_login');
        $password = $this->CI->config->item('smsc_password');
        $sender = $this->CI->config->item('smsc_sender');
        if($login && $password && $sender){
            $client = new SoapClient('http://smsc.ua/sys/soap.php?wsdl');
            $res = $client->send_sms(array(
                    'login'=>$login,
                    'psw'=>$password,
                    'phones'=>preg_replace("/[^0-9]/", '', $phone),
                    'mes'=>strip_tags($text),
                    'id'=>'',
                    'sender'=>$sender,
                    'time'=>0)
            );
        }
    }
}