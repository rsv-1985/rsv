<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Paypal
{

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('order_model');
    }

    /**
     * Последние сообщения об ошибках
     * @var array
     */
    protected $_errors = array();

    /**
     * Данные API
     * Обратите внимание на то, что для песочницы нужно использовать соответствующие данные
     * @var array
     */
    protected $_credentials = array(
        'USER' => 'mirza-facilitator_api1.apa67.fr',
        'PWD' => 'UV486UKAYY48KEB4',
        'SIGNATURE' => 'AVD1EnlN759eebp.rB733alG7x0PAhMlS85X73th94cg.K.HtJatqm5P',
    );

    /**
     * Указываем, куда будет отправляться запрос
     * Реальные условия - https://api-3t.paypal.com/nvp
     * Песочница - https://api-3t.sandbox.paypal.com/nvp
     * @var string
     */
    protected $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';

    /**
     * Версия API
     * @var string
     */
    protected $_version = '109.0';

    /**
     * Сформировываем запрос
     *
     * @param string $method Данные о вызываемом методе перевода
     * @param array $params Дополнительные параметры
     * @return array / boolean Response array / boolean false on failure
     */
    public function request($method,$params = array()) {

        $this -> _errors = array();
        if( empty($method) ) { // Проверяем, указан ли способ платежа
            $this -> _errors = array('Не указан метод перевода средств');
            return false;
        }

        // Параметры нашего запроса
        $requestParams = array(
                'METHOD' => $method,
                'VERSION' => $this -> _version
            ) + $this -> _credentials;

        // Сформировываем данные для NVP
        $request = http_build_query($requestParams + $params);

        // Настраиваем cURL
        $curlOptions = array (
            CURLOPT_URL => $this -> _endPoint,
            CURLOPT_VERBOSE => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', // Файл сертификата
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $request
        );

        $ch = curl_init();
        curl_setopt_array($ch,$curlOptions);

        // Отправляем наш запрос, $response будет содержать ответ от API
        $response = curl_exec($ch);

        // Проверяем, нету ли ошибок в инициализации cURL
        if (curl_errno($ch)) {
            $this -> _errors = curl_error($ch);
            curl_close($ch);
            return false;
        } else  {
            curl_close($ch);
            $responseArray = array();
            parse_str($response,$responseArray); // Разбиваем данные, полученные от NVP в массив
            return $responseArray;
        }
    }

    public function get_form($order_id = false)
    {
        if ($order_id) {
            $orderInfo = $this->CI->order_model->get($order_id);

            $requestParams = array(
                'RETURNURL' => base_url('cart/success/'.$order_id),
                'CANCELURL' => base_url(),
            );

            $orderParams = array(
                'PAYMENTREQUEST_0_AMT' => $orderInfo['total'],
                'PAYMENTREQUEST_0_SHIPPINGAMT' => $orderInfo['delivery_price'],
                'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
                'PAYMENTREQUEST_0_ITEMAMT' => $orderInfo['total'] - $orderInfo['delivery_price']
            );

            $item = array(
                'L_PAYMENTREQUEST_0_NAME0' => 'Order #'.$orderInfo['id'],
                'L_PAYMENTREQUEST_0_DESC0' => '',
                'L_PAYMENTREQUEST_0_AMT0' => $orderInfo['total'] - $orderInfo['delivery_price'],
                'L_PAYMENTREQUEST_0_QTY0' => '1'
            );
            $response = $this->request('SetExpressCheckout',$requestParams + $orderParams + $item);

            if(is_array($response) && $response['ACK'] == 'Success') { // Запрос был успешно принят
                $token = $response['TOKEN'];
                redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . urlencode($token));
            }
        }
    }

    public function success($orderInfo){

        if( isset($_GET['token']) && !empty($_GET['token']) ) { // Токен присутствует

            // Получаем детали оплаты, включая информацию о покупателе.
            // Эти данные могут пригодиться в будущем для создания, к примеру, базы постоянных покупателей

            $checkoutDetails = $this->request('GetExpressCheckoutDetails', array('TOKEN' => $_GET['token']));

            // Завершаем транзакцию
            $requestParams = array(
                'TOKEN' => $_GET['token'],
                'PAYMENTACTION' => 'Sale',
                'PAYMENTREQUEST_0_AMT' => $orderInfo['total'],
                'PAYERID' => $_GET['PayerID'],
                'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR'
            );

            $response = $this->request('DoExpressCheckoutPayment',$requestParams);

            if( is_array($response) && $response['ACK'] == 'Success') { // Оплата успешно проведена
                // Здесь мы сохраняем ID транзакции, может пригодиться во внутреннем учете
                $transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];
                $save = [];
                $save['paid'] = true;
                $this->CI->order_model->insert($save, $orderInfo['id']);
                $this->CI->session->set_flashdata('success', sprintf(lang('text_success_order'), $orderInfo['id']));
                redirect('/');
            }else{
                show_404();
            }
        }
    }
}
