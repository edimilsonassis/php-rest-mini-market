<?php

namespace http\middleware;

use http\Exception;
use controllers\api\Auth;

class RequireLogin
{
    /**
     * Middleware Login
     * @var \http\Request $request 
     * @var \Closure $next 
     * @return \http\Response
     */
    public function handle($request, $next)
    {
        if (!Auth::isAuth($request))
            throw new Exception('Access is required to view this page', 401);

        return $next($request);
    }
}