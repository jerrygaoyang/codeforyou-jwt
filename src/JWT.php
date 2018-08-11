<?php
/**
 * Created by PhpStorm.
 * User: gaoyang
 * Date: 2018/8/11
 * Time: 上午11:26
 */

namespace Codeforyou\Jwt;

use Codeforyou\Jwt\JwtException;

class JWT
{
    public static function header()
    {
        return [
            'typ' => 'jwt'
        ];
    }

    public static function safe_base64_decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $len = 4 - $mod4;
            $data .= str_repeat('=', $len);
        }
        return base64_decode($data);
    }


    public static function safe_base64_encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public static function signature(array $header, array $payload, string $secret, string $alg)
    {
        $base64_header = self::safe_base64_encode(json_encode($header));
        $base64_payload = self::safe_base64_encode(json_encode($payload));
        $signature = hash_hmac($alg, $base64_header . $base64_payload, $secret, true);
        $base64_signature = self::safe_base64_encode($signature);
        return $base64_signature;
    }

    public static function encode(array $payload, string $secret, string $alg = 'sha256')
    {
        $header = self::header();
        $header['alg'] = $alg;
        $base64_header = self::safe_base64_encode(json_encode($header));
        $base64_payload = self::safe_base64_encode(json_encode($payload));
        $base64_signature = self::signature($header, $payload, $secret, $alg);
        $token = $base64_header . '.' . $base64_payload . '.' . $base64_signature;
        return $token;
    }

    public static function decode(string $token, string $secret, string $alg = 'sha256')
    {
        $arr = explode('.', $token);
        if (count($arr) != 3) {
            throw new JwtException('invalid token');
        }
        $header = json_decode(self::safe_base64_decode($arr[0]));
        $payload = json_decode(self::safe_base64_decode($arr[1]));
        $base64_signature = self::signature($header, $payload, $secret, $alg);
        if (!hash_equals($base64_signature, $arr[2])) {
            throw new JwtException('invalid token');
        }
        return $payload;
    }

}