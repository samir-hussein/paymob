# paymob
paymob payment gateway


## Installation
composer require samir-hussein/paymob

## Usage
    
step 1 :
```php
    require_once "vendor/autoload.php";
```
step 2 :
```php
    $config = [
      'PayMob_User_Name' => 'your user name',
      'PayMob_Password' => 'your password',
      'PayMob_Integration_Id' => 'Integration_Id'
    ];
    
    $init = new PayMob($config);
    ```
step 3 :
```php
    $auth = PayMob::AuthenticationRequest();
    ```
step 4 : 
```php
    $order = PayMob::OrderRegistrationAPI([
      'auth_token' => $auth->token, // from step 3
      'amount_cents' => 150 * 100, //put your price
      'currency' => 'EGP',
      'delivery_needed' => false, // another option true
      'merchant_order_id' => 6, //put order id from your database must be unique id
      'items' => [[ // all items information
          "name" => "ASC1515",
          "amount_cents" => 150 * 100,
          "description" => "Smart Watch",
          "quantity" => "2"
      ]]
    ]);
    ```
    
step 5 : 
```php
    $PaymentKey = PayMob::PaymentKeyRequest([
      'auth_token' => $auth->token, // from step 3
      'amount_cents' => 150 * 100,//put your price
      'currency' => 'EGP',
      'order_id' => $order->id, // from step 4
      "billing_data" => [ // put your client information
          "apartment" => "803",
          "email" => "claudette09@exa.com",
          "floor" => "42",
          "first_name" => "Clifford",
          "street" => "Ethan Land",
          "building" => "8028",
          "phone_number" => "+86(8)9135210487",
          "shipping_method" => "PKG",
          "postal_code" => "01898",
          "city" => "Jaskolskiburgh",
          "country" => "CR",
          "last_name" => "Nicolas",
          "state" => "Utah"
      ]
    ]);
    ```
    
finally 
  <iframe width="100%" height="700" src="https://accept.paymob.com/api/acceptance/iframes/{{your frame id here}}?payment_token=<?= $PaymentKey->token // from step 5 ?>">
