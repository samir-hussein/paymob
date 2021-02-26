<?php

namespace PayMob;

class PayMob
{

    public function __construct()
    {
        //
    }

    public static function AuthenticationRequest()
    {
        $userInfo = [
            'username' => config('paymob.username'),
            'password' => config('paymob.password'),
        ];

        $postData = json_encode($userInfo);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accept.paymobsolutions.com/api/auth/tokens');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        if ($response === false) {
            echo curl_error($ch);
        }
        curl_close($ch);
        return json_decode($response);
    }

    public static function OrderRegistrationAPI(array $requestData)
    {
        $postData = json_encode($requestData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accept.paymobsolutions.com/api/ecommerce/orders');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);
        if ($response === false) {
            echo curl_error($ch);
        }
        curl_close($ch);
        return json_decode($response);
    }

    public static function PaymentKeyRequest($requestData)
    {
        $requestData['expiration'] = 3600;
        $requestData['integration_id'] = config('paymob.integration_id');
        $postData = json_encode($requestData);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://accept.paymobsolutions.com/api/acceptance/payment_keys');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $response = curl_exec($ch);
        if ($response === false) {
            echo curl_error($ch);
        }
        curl_close($ch);
        return json_decode($response);
    }
}
