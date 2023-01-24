<?php

namespace Paymob;

class PayMob
{
    private static $username;
    private static $password;
    private static $integration_id;

    public function __construct(array $config)
    {
        self::$username = $config['PayMob_User_Name'];
        self::$password = $config['PayMob_Password'];
        self::$integration_id = $config['PayMob_Integration_Id'];
    }

    public static function AuthenticationRequest()
    {
        $userInfo = [
            'username' => self::$username,
            'password' => self::$password
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
        $requestData['integration_id'] = self::$integration_id;
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

    public static function refundTransaction(string $auth_token, int $transaction_id, int $amount_cents)
    {
        $requestData = [
            'auth_token' => $auth_token,
            'transaction_id' => $transaction_id,
            'amount_cents' => $amount_cents,
        ];

        $postData = json_encode($requestData);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://accept.paymob.com/api/acceptance/void_refund/refund');
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

    public static function voidTransaction(string $auth_token, int $transaction_id)
    {
        $requestData = [
            'auth_token' => $auth_token,
            'transaction_id' => $transaction_id,
        ];

        $postData = json_encode($requestData);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://accept.paymob.com/api/acceptance/void_refund/void?token=' . $auth_token);
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
