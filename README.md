# codeforyou-jwt

user guides


```
use codeforyou\jwt\Jwt;

$payload = {'user_id': 1};
$secret = '123456';
$alg = 'sha256';

$token = Jwt::encode($payload, $secret , $alg);

$payload = Jwt::decode($token, $secret, $alg);
```