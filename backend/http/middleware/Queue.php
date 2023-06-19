<?php

namespace http\middleware;

use http\Exception;

class Queue
{
    /**
     * Available Middwares
     * @var array
     */
    private static $map = [];

    /** 
     * Standard Middleware
     * Will merged with $middlewares and be run always
     * @var array
     */
    private static $default = [];

    /**
     * Middlewares queue to be processed
     * @var array
     */
    private $middlewares = [];

    /**
     * Receives the function that performs the controller
     * That funcion is called when the queue is empty
     * @var array
     */
    private $constroller = [];

    /**
     * Controller function parameters
     * @var array
     */
    private $constrollerArgs = [];

    /**
     * Create Middlewares
     * @var array $middlewares 
     * @var Closure $constroller 
     * @var array $constrollerArgs 
     */
    public function __construct($middlewares, $constroller, $constrollerArgs)
    {
        $this->middlewares     = array_merge(self::$default, $middlewares);
        $this->constroller     = $constroller;
        $this->constrollerArgs = $constrollerArgs;
    }

    /**
     * Execute the next index of the line
     * @var \http\Request $request 
     */
    public function next($request)
    {
        // Execute if the queue is empty
        if (empty($this->middlewares))
            return call_user_func(
                $this->constroller,
                ...$this->constrollerArgs
            );

        $middleware = array_shift($this->middlewares);

        if (!isset(self::$map[$middleware]))
            throw new Exception('Internal error, middleware cannot be processed', 500);

        // Define the next Middleware
        $queue = $this;
        $next  = function ($request) use ($queue) {
            return $queue->next($request);
        };

        // Run the middleware
        $middleware = new self::$map[$middleware];
        return $middleware->handle($request, $next);
    }

    /**
     * Defines available middleware
     * @var array $map 
     */
    public static function setMap(array $map)
    {
        self::$map = $map;
    }

    /**
     * Defines default Middlewares
     * @var array $default 
     */
    public static function setDefault(array $default)
    {
        self::$default = $default;
    }
}