<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Novaposhta
{
    private $api_key;
    private $params;
    private $CI;

    public function __construct($params)
    {
        $this->CI = &get_instance();
        $this->api_key = $params['api_key'];
        $this->params = $params;
    }

    /**
     *Загрузить список контактных лиц Контрагента
     */
    public function getCounterpartyContactPerson($Ref)
    {
        $model = 'Counterparty';
        $method = 'getCounterpartyContactPersons';
        $params = ['Ref' => $Ref];
        $results = $this->request($model, $method, $params);
        if ($results && $results['success'] == 1) {
            return $results['data'];
        }
    }

    public function getCounterpartyAddresses($Ref, $type)
    {
        $model = 'Counterparty';
        $method = 'getCounterpartyAddresses';
        $params = ['Ref' => $Ref, 'CounterpartyProperty' => $type];
        $results = $this->request($model, $method, $params);
        if ($results && $results['success'] == 1) {
            return $results['data'];
        }
    }

    /**
     *Загрузить список Контрагентов отправителей/получателей/третье лицо
     */
    public function getCounterparties($type)
    {
        $model = 'Counterparty';
        $method = 'getCounterparties';
        $params = [
            'CounterpartyProperty' => $type
        ];
        $results = $this->request($model, $method, $params);
        if ($results && $results['success'] == 1) {
            return $results['data'];
        }
    }

    public function getWarehouses($Ref)
    {
        $model = 'Address';
        $method = 'getWarehouses';
        $params = [
            'CityRef' => trim($Ref),
        ];


        $cache = $this->CI->cache->file->get($Ref);
        if ($cache) {
            return $cache;
        }

        $results = $this->request($model, $method, $params);
        if ($results && $results['success'] == 1) {
            $this->CI->cache->file->save($Ref, $results['data'], 60 * 60 * 24 * 30);
            return $results['data'];
        }
    }

    public function InternetDocument($data)
    {
        $model = 'InternetDocument';
        $method = 'save';
        $params = [
            "NewAddress" => $data['NewAddress'],
            "PayerType" => $data['PayerType'],
            "PaymentMethod" => $data['PaymentMethod'],
            "CargoType" => $data['CargoType'],
            "VolumeGeneral" => $data['VolumeGeneral'],
            "Weight" => trim(str_replace(',','.',$data['Weight'])),
            "ServiceType" => $data['ServiceType'],
            "SeatsAmount" => $data['SeatsAmount'],
            "Description" => $data['Description'],
            "Cost" => $data['Cost'],
            "CitySender" => $this->params['CitySender'],
            "Sender" => $this->params['Sender'],
            "SenderAddress" => $this->params['SenderAddress'],
            "ContactSender" => $this->params['ContactSender'],
            "SendersPhone" => $this->params['SendersPhone'],
            "RecipientCityName" => $data['RecipientCityName'],
            "RecipientArea" => $data['RecipientArea'],
            "RecipientAreaRegions" => $data['RecipientAreaRegions'],
            "RecipientAddressName" => $data['ServiceType'] == 'WarehouseWarehouse' ? $data['RecipientAddressName'] :$data['RecipientAddressName2'] ,
            "RecipientHouse" => $data['RecipientHouse'],
            "RecipientFlat" => $data['RecipientFlat'],
            "RecipientName" => $data['RecipientName'],
            "RecipientType" => $data['RecipientType'],
            "RecipientsPhone" => preg_replace("/[^0-9]/", '', $data['RecipientsPhone']),
            "DateTime" => date('d.m.Y',strtotime($data['DateTime'])),
            "AdditionalInformation" => $data['AdditionalInformation']
        ];

        if($data['nalojka']){
            $params['BackwardDeliveryData'] = array(
                array(
                    "PayerType" => "Recipient",
                    "CargoType" => "Money",
                    "RedeliveryString" => (float)$data['RedeliveryString']
                )
            );
        }

        return $this->request($model, $method, $params);

    }

    public function TrackingDocumen($Documents){
        $model = 'TrackingDocument';
        $method = 'getStatusDocuments';
        $params = ['Documents' => $Documents];
        return $this->request($model, $method, $params);
    }

    public function InternetDocumentDelete($DocumentRefs){
        $model = 'InternetDocument';
        $method = 'delete';
        $params = ['DocumentRefs' => $DocumentRefs];
        return $this->request($model, $method, $params);
    }

    public function searchSettlements($CityName)
    {
        $model = 'Address';
        $method = 'searchSettlements';
        $params = [
            'CityName' => trim($CityName),
            'Limit' => '36'
        ];

        $result = $this->request($model, $method, $params);
        if ($result && $result['success'] == 1) {
            return $result['data'][0]['Addresses'];
        }
    }

    /**
     * Make request to NovaPoshta API
     *
     * @param string $model Model name
     * @param string $method Method name
     * @param array $params Required params
     */
    private function request($model, $method, $params = NULL)
    {
        // Get required URL
        $url = 'https://api.novaposhta.ua/v2.0/json/';

        $data = array(
            'apiKey' => $this->api_key,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => 'ru',
            'methodProperties' => $params
        );

        $post = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);


        return $this->prepare($result);
    }

    /**
     * Prepare data before return it
     *
     * @param json $data
     * @return mixed
     */
    private function prepare($data)
    {
        $result = is_array($data)
            ? $data
            : json_decode($data, 1);
        return $result;
    }
}