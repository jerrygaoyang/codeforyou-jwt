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
        return ['typ' => 'JWT', 'alg' => 'HS256'];
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

    public static function signature(array $header, array $payload, string $secret)
    {
        $base64_header = self::safe_base64_encode(json_encode($header));
        $base64_payload = self::safe_base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $base64_header . $base64_payload, $secret, true);
        $base64_signature = self::safe_base64_encode($signature);
        return $base64_signature;
    }

    public static function encode(array $payload, string $secret)
    {
        $header = self::header();
        $base64_header = self::safe_base64_encode(json_encode($header));
        $base64_payload = self::safe_base64_encode(json_encode($payload));
        $base64_signature = self::signature($header, $payload, $secret);
        $token = $base64_header . '.' . $base64_payload . '.' . $base64_signature;
        return $token;
    }

    public static function decode(string $token, string $secret)
    {
        $arr = explode('.', $token);
        if (count($arr) != 3) {
            throw new JwtException('invalid token');
        }
        $header = json_decode(self::safe_base64_decode($arr[0]), true);
        $payload = json_decode(self::safe_base64_decode($arr[1]), true);
        $base64_signature = self::signature($header, $payload, $secret);
        if (!hash_equals($base64_signature, $arr[2])) {
            throw new JwtException('invalid token');
        }
        return $payload;
    }

}