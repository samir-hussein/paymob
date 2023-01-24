# paymob

paymob payment gateway https://paymob.com

<p>paymob payment gateway API documentation https://docs.paymob.com/docs/accept-standard-redirect</p>

## Installation

```bash
composer require samir-hussein/paymob
```

## Contents

# php

- [Authentication Request](#Authentication-Request)
- [Order Registration](#Order-Registration)
- [Payment Key Request](#Payment-Key-Request)
- [Refund Transaction](#Refund-Transaction)
- [Void Transaction](#Void-Transaction)

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
      'PayMob_Integration_Id' => 'Integration_Id',
    ];

    $init = new PayMob($config);
```

# Authentication Request

step 3 :

```php
    $auth = PayMob::AuthenticationRequest();
```

# Order Registration

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

# Payment Key Request

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
<iframe
  width="100%"
  height="800"
  src="https://accept.paymob.com/api/acceptance/iframes/{{your_frame_id_here}}?payment_token=<?= $PaymentKey->token // from step 5 ?>"
></iframe>
```

# Refund Transaction

```php
    PayMob::refundTransaction(
        $auth_token, // from step 3
        $transaction_id,
        $amount_cents // amount in cent 100 EGP = 100 * 100 cent
    );
```

# Void Transaction

```php
    PayMob::voidTransaction(
        $auth_token, // from step 3
        $transaction_id,
    );
```

### card information testing

Card number : 4987654321098769\
Cardholder Name : Test Account\
Expiry Month : 12\
Expiry year : 25\
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
PayMob_HMAC="HMAC" // from your dashboard
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
            'items' => [] // all items information or leave it empty
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
<iframe
  width="100%"
  height="800"
  src="https://accept.paymob.com/api/acceptance/iframes/your_iframe_id?payment_token={{$token}}"
>
</iframe>
```

step 6 : update your database after payment is done

```php
Route::post('/checkout/processed',function(Request $request){
    $request_hmac = $request->hmac;
    $calc_hmac = PayMob::calcHMAC($request);

    if ($request_hmac == $calc_hmac) {
        $order_id = $request->obj['order']['merchant_order_id'];
        $amount_cents = $request->obj['amount_cents'];
        $transaction_id = $request->obj['id'];

        $order = Order::find($order_id);

        if ($request->obj['success'] == true && ($order->total_price * 100) == $amount_cents) {
            $order->update([
                'payment_status' => 'finished',
                'transaction_id' => $transaction_id
            ]);
        } else {
            $order->update([
                'payment_status' => "failed",
                'transaction_id' => $transaction_id
            ]);
        }
    }
});
```

# Refund Transaction

```php
Route::post('/refund', function () {
    $auth = PayMob::AuthenticationRequest();
    return PayMob::refundTransaction(
        $auth->token,
        $transaction_id,
        $amount_cents // amount in cent 100 EGP = 100 * 100 cent
    );
});
```

# Void Transaction

```php
Route::post('/void', function () {
    $auth = PayMob::AuthenticationRequest();
    return  PayMob::voidTransaction(
        $auth->token,
        $transaction_id,
    );
});
```
