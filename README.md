# codeforyou-jwt


## Installation

```
composer require codeforyou/jwt
```

## Get started


```
use Codeforyou\Jwt\JWT;


$payload = ['user_id' => 1];

$secret = '123456';

$token = JWT::encode($payload, $secret);

$payload = JWT::decode($token, $secret);
```
