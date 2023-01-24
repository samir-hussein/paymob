<?php

namespace PayMob;

class PayMob
{
    public static function AuthenticationRequest()
    {
        $userInfo = [
            'username' => env("PayMob_Username"),
            'password' => env("PayMob_Password"),
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
        $requestData['integration_id'] = env("PayMob_Integration_Id");
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

    public static function calcHMAC($request)
    {
        $data = $request->only([
            'obj.amount_cents',
            'obj.created_at',
            'obj.currency',
            'obj.error_occured',
            'obj.has_parent_transaction',
            'obj.id',
            'obj.integration_id',
            'obj.is_3d_secure',
            'obj.is_auth',
            'obj.is_capture',
            'obj.is_refunded',
            'obj.is_standalone_payment',
            'obj.is_voided',
            'obj.order.id',
            'obj.owner',
            'obj.pending',
            'obj.source_data.pan',
            'obj.source_data.sub_type',
            'obj.source_data.type',
            'obj.success'
        ]);
        $values = array_values($data['obj']);
        foreach ($values as &$val) {
            if (is_array($val)) {
                $val = array_values($val);
                $val = implode($val);
            }
            if ($val === true) $val = "true";
            if ($val === false) $val = "false";
        }
        $concatenate = implode($values);
        $hash = hash_hmac('sha512', $concatenate, env('PayMob_HMAC'));

        return $hash;
    }
}
