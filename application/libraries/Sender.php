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

    /**
     * @param $subject
     * @param $body
     * @param $to
     * @param $from
     * @return bool
     */
    public function email($subject, $body, $to = false, $from = false){

        if(!$to || !$from){
            return false;
        }
        if(is_array($to)){
            $to = implode(',',$to);
        }
        if(is_array($from)){
            $from = $from[0];
        }
        $this->CI->load->library('email');
        if($smtp_config = $this->CI->config->item('smtp')){
            foreach ($smtp_config as $key => $value){
                $config[$key] = $value;
            }
            $this->CI->email->initialize($config);
        }
        if(isset($this->CI->contacts['email'])){
            $this->CI->email->from($this->CI->contacts['email'],$this->CI->config->item('company_name'));
        }else{
            $this->CI->email->from($from);
        }

        $this->CI->email->reply_to($from,'');
        $this->CI->email->to($to);
        $this->CI->email->mailtype = 'html';
        $this->CI->email->subject($subject);
        $this->CI->email->message($body);
        if(!$this->CI->email->send()){
            echo $this->CI->email->print_debugger();
        }
    }

    function sms($phone,$text){
        $method_send = 'smsc';
        $settings = $this->CI->settings_model->get_by_key('sms');
        if(!@$settings['login']){
            $method_send = 'turbosms';
            $settings = $this->CI->settings_model->get_by_key('sms2');
        }

        $login = @$settings['login'] ? $settings['login'] : $this->CI->config->item('smsc_login');
        $password = @$settings['password'] ? $settings['password'] : $this->CI->config->item('smsc_password');
        $sender = @$settings['sender'] ? $settings['sender'] : $this->CI->config->item('smsc_sender');

        if($login && $password && $sender){
            switch ($method_send){
                case 'smsc':
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
                    break;
                case 'turbosms':
                    $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

                    $auth = array('login' => $login, 'password' => $password);
                    $result = $client->Auth($auth);

                    $sms = array(
                        'sender' => $sender,
                        'destination' => '+' . preg_replace("/[^0-9]/", '', $phone),
                        'text' => $text);

                    $client->SendSMS($sms);
                    break;
            }

        }
    }
}