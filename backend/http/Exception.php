<?php

namespace http;

final class Exception extends \Exception
{
    public function __construct(string $message, int $code = 500, \Throwable $previous = null)
    {
        $response = new Response(
            $code,
            ['message' => $message]
        );

        $response->display();
    }
}