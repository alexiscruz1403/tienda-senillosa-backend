<?php

namespace App\Utils;
use Firebase\JWT\JWT;

class JWTUtil
{
    private static function secretKey()
    {
        return config('jwt.secret');
    }

    private static function algorithm()
    {
        return config('jwt.algorithm');
    }

    public static function generateToken($payload, $expiry = null)
    {
        try{
            $issuedAt = time();
            $expire = $issuedAt + ($expiry ?? config('jwt.ttl'));

            $tokenPayload = array_merge($payload, [
                'iat' => $issuedAt,
                'exp' => $expire,
            ]);

            return JWT::encode(
                $tokenPayload,
                self::secretKey(),
                self::algorithm()
            );
        }catch(\Exception $e){
            throw new \Exception('Error generating token: ' . $e->getMessage());
        }
    }

}
