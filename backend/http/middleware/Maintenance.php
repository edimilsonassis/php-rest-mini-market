<?php

namespace http\middleware;

use http\Exception;

class Maintenance
{
    /**
     * Middleware Maintenance
     * @var \http\Request $request 
     * @var \Closure $next 
     * @return \http\Response
     */
    public function handle($request, $next)
    {
        throw new Exception('Maintenance page', 204);
    }
}