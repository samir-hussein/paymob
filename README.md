# paymob
paymob payment gateway https://paymob.com


## Installation
```bash
composer require samir-hussein/paymob
```
## Usage In php native
    
step 1 :
```php
    require_once "vendor/autoload.php";
    use Paymob\PayMob;
```
step 2 :
```php
    $config = [
      'PayMob_User_Name' => 'your_username',
      'PayMob_Password' => 'your_password',
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
```html
  <iframe width="100%" height="800" src="https://accept.paymob.com/api/acceptance/iframes/{{your_frame_id_here}}?payment_token=<?= $PaymentKey->token // from step 5 ?>">
```
### card information testing
Card number : 4987654321098769\
Cardholder Name : Test Account\
Expiry Month : 05\
Expiry year : 21\
CVV : 123

## Usage in laravel
step 1 :
in config/app.php
```php
//in providers
PayMob\PayMobServiceProvider::class,
//in aliases
'PayMob' => PayMob\Facades\PayMob::class,
```
step 2 : in .env file
```bash
PayMob_Username="Your_Username"
PayMob_Password="Your_Password"
PayMob_Integration_Id="Integration_Id"
```
step 3 : run command
```bash
php artisan vendor:publish --provider="PayMob\PayMobServiceProvider"
```
step 4 : create controller like this
```php
<?php

namespace App\Http\Controllers;

use PayMob\Facades\PayMob;
use Illuminate\Http\Request;

class PayMobController extends Controller
{
    public function index()
    {
        $auth = PayMob::AuthenticationRequest();
        $order = PayMob::OrderRegistrationAPI([
            'auth_token' => $auth->token,
            'amount_cents' => 150 * 100, //put your price
            'currency' => 'EGP',
            'delivery_needed' => false, // another option true
            'merchant_order_id' => 1000, //put order id from your database must be unique id
            'items' => [[ // all items information
                "name" => "ASC1515",
                "amount_cents" => 150 * 100,
                "description" => "Smart Watch",
                "quantity" => "2"
            ]]
        ]);
        $PaymentKey = PayMob::PaymentKeyRequest([
            'auth_token' => $auth->token,
            'amount_cents' => 150 * 100, //put your price
            'currency' => 'EGP',
            'order_id' => $order->id,
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

        return view('paymob')->with(['token' => $PaymentKey->token]);
    }
}
```
step 5 : create view paymob.blade.php and use your iframe like this
```html
<iframe width="100%" height="800" src="https://accept.paymob.com/api/acceptance/iframes/your_iframe_id?payment_token={{$token}}"> 
```
