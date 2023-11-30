<?php

namespace Arifabdd\PaymentApi\KapitalBank;

class Kapitalbank
{
    private string $baseUrl;
    private string $keyFile;
    private string $certFile;
    private string $merchantId;
    private string $approveUrl;
    private string $cancelUrl;
    private string $declineUrl;
    private bool $liveMode;
    private int $currency;
    private string $lang;

    public function __construct(
        $merchantId,
        $approveUrl,
        $cancelUrl,
        $declineUrl,
        $certFile = './certs/test.crt',
        $keyFile = './certs/test.key',
        $liveMode = false,
        $lang = 'AZ',
        $currency = 944
    ){
        $this->liveMode = $liveMode;
        $this->baseUrl = $liveMode ? 'https://3dsrv.kapitalbank.az:5443/Exec' : 'https://tstpg.kapitalbank.az:5443/Exec';
        $this->merchantId = $merchantId;
        $this->certFile = $certFile;
        $this->keyFile = $keyFile;
        $this->approveUrl = $approveUrl;
        $this->declineUrl = $declineUrl;
        $this->cancelUrl = $cancelUrl;
        $this->currency = $currency;
        $this->lang =  $lang;
    }

    public function createOrder(int $price,string $description = 'Payment description'){
        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->baseUrl ,
                CURLOPT_SSLCERT => $this->certFile ,
                CURLOPT_SSLKEY => $this->keyFile ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                    <TKKPG>
                          <Request>
                                  <Operation>CreateOrder</Operation>
                                  <Language>'.$this->lang.'</Language>
                                  <Order>
                                        <OrderType>Purchase</OrderType>
                                        <Merchant>'.$this->merchantId.'</Merchant>
                                        <Amount>'. $price * 100 .'</Amount>
                                        <Currency>'.$this->currency.'</Currency>
                                        <Description>'.$description.'</Description>
                                        <ApproveURL>'. $this->approveUrl.'</ApproveURL>
                                        <CancelURL>'. $this->cancelUrl.'</CancelURL>
                                        <DeclineURL>'. $this->declineUrl.'</DeclineURL>
                                  </Order>
                          </Request>
                    </TKKPG>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    'Cookie: JSESSIONID='.csrf_token()
                ),
            );

            curl_setopt_array($ch , $options);
            $output = curl_exec($ch);
            $array_data = json_decode(json_encode(simplexml_load_string($output)), true);
            return $array_data;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function completeOrder(int $orderId,string $sessionId,int $amount,string $description = 'Order description',string $lang = 'AZ'){
        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->baseUrl ,
                CURLOPT_SSLCERT => $this->certFile ,
                CURLOPT_SSLKEY => $this->keyFile ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                <TKKPG>
                      <Request>
                              <Operation>Completion</Operation>
                              <Language>'.$lang.'</Language>
                              <Order>
                                    <Merchant>'.$this->merchantId.'</Merchant>
                                    <OrderID>'.$orderId.'</OrderID>
                              </Order>
                              <SessionID>'.$sessionId.'</SessionID>
                              <Amount>'.$amount.'</Amount>
                              <Description>'.$description.'</Description>
                      </Request>
                </TKKPG>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    'Cookie: JSESSIONID='.csrf_token()
                ),
            );

            curl_setopt_array($ch , $options);
            $output = curl_exec($ch);
            return  $output;


        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function reverseOrder(int $orderId,string $sessionId,string $description = 'Reverse description',string $lang = 'AZ'){
        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->baseUrl ,
                CURLOPT_SSLCERT => $this->certFile ,
                CURLOPT_SSLKEY => $this->keyFile ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                <TKKPG>
                      <Request>
                              <Operation>Reverse</Operation>
                              <Language>'.$lang.'</Language>
                              <Order>
                                    <Merchant>'.$this->merchantId.'</Merchant>
                                    <OrderID>'.$orderId.'</OrderID>
                                    <Positions>
                                        <Position>
                                            <PaymentSubjectType>1</PaymentSubjectType>
                                            <Quantity>1</Quantity>
                                            <PaymentType>2</PaymentType>
                                            <PaymentMethodType>1</PaymentMethodType>
                                        </Position>
                                    </Positions>
                              </Order>
                              <Description>'.$description.'</Description>
                              <SessionID>'.$sessionId.'</SessionID>
                              <TranId></TranId>
                              <Source>1</Source>
                      </Request>
                </TKKPG>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    'Cookie: JSESSIONID='.csrf_token()
                ),
            );

            curl_setopt_array($ch , $options);
            $output = curl_exec($ch);
            return  $output;


        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function getOrderStatus(int $orderId,string $sessionId,string $lang = 'AZ'){
        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->baseUrl ,
                CURLOPT_SSLCERT => $this->certFile ,
                CURLOPT_SSLKEY => $this->keyFile ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                <TKKPG>
                    <Request>
                        <Operation>GetOrderStatus</Operation>
                        <Language>'.$lang.'</Language>
                        <Order>
                            <Merchant>'.$this->merchantId.'</Merchant>
                            <OrderID>'.$orderId.'</OrderID>
                        </Order>
                        <SessionID>'.$sessionId.'</SessionID>
                    </Request>
                </TKKPG>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    'Cookie: JSESSIONID='.csrf_token()
                ),
            );

            curl_setopt_array($ch , $options);
            $output = curl_exec($ch);
            return  $output;


        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function getOrderInformation(int $orderId,string $sessionId,string $lang = 'AZ'){
        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->baseUrl ,
                CURLOPT_SSLCERT => $this->certFile ,
                CURLOPT_SSLKEY => $this->keyFile ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                <TKKPG>
                    <Request>
                        <Operation>GetOrderInformation</Operation>
                        <Language>'.$lang.'</Language>
                        <Order>
                            <Merchant>'.$this->merchantId.'</Merchant>
                            <OrderID>'.$orderId.'</OrderID>
                        </Order>
                        <SessionID>'.$sessionId.'</SessionID>
                    </Request>
                </TKKPG>',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain',
                    'Cookie: JSESSIONID='.csrf_token()
                ),
            );

            curl_setopt_array($ch , $options);
            $output = curl_exec($ch);
            return  $output;


        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
