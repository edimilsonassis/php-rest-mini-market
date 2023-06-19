<?php

namespace core;

use http\Request;

class JWT
{
    /**
     * Retrives the Bearer token
     * @var Request $request The request instance
     * @return string
     */
    public static function bearerJWT(Request $request)
    {
        $authorization = $request->headers('Authorization') ?? '';

        return str_replace('Bearer ', '', $authorization, $count);
    }

    /**
     * Validate an JWT
     * @var string $jwt base64 token
     * @return bool
     */
    public static function validateSignature(string $jwt)
    {
        $payload   = self::decodeJWT($jwt);
        $signature = self::createSignature($payload);

        return $signature == $jwt;
    }

    /**
     * Decodes a JWT
     * @var string $jwt base64 token
     * @return array|object
     */
    public static function decodeJWT(string $jwt, bool $associative = true)
    {
        $jwt_array = explode('.', $jwt);

        $base64_decode = base64_decode($jwt_array[1] ?? '');

        $payload = json_decode($base64_decode, $associative) ?? [];

        return $payload;
    }

    /**
     * Creates a new token with payload information
     * @var array $payload array to be encoded in JWT
     * @return string User, UUID, JWT
     */
    public static function createSignature(array $payload)
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        $base64UrlHeader = base64_encode($header);

        $base64UrlPayload = base64_encode(json_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $_ENV['JWT_SECRET'], true);

        $base64UrlSignature = base64_encode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
}