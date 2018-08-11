# codeforyou-jwt

## Description

> JWT auth for encode and decode; alg: HS256


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
