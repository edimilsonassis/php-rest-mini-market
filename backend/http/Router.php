<?php

namespace http;

use Closure;
use ReflectionFunction;
use http\Exception;

class Router
{
    /**
     * The site URL
     * @var string
     */
    private $url = '';

    /**
     * Retrieves the router instance
     * @var string
     */
    private static ?Router $instance;

    /**
     * The current prefix route
     * eg.: /api/route_name
     * @var string
     */
    private $prefix = '';

    /**
     * List of routes registered
     * @var array
     */
    private $routes = [];

    /**
     * Instace of the current Request
     * @var Request
     */
    private ?Request $request;

    /**
     * Instace of the current Router
     * @var string $url The URL of the requisition
     */
    public static function getInstance(string $url = null): Router
    {
        if (isset(self::$instance))
            return self::$instance;
        return new static($url);
    }

    /**
     * Router constructor
     * @var string $url The URL of the requisition
     */
    public function __construct(string $url)
    {
        $this->request = new Request;
        $this->url     = $url;
        $this->setPrefix();

        self::$instance = $this;
    }

    /**
     * Set the current prefix route
     */
    private function setPrefix()
    {
        $parse_url    = parse_url($this->url);
        $this->prefix = $parse_url['path'] ?? '';
    }

    /**
     * Add new route to the router
     * @var string $method  
     * @var string $route 
     * @var array  $params 
     */
    private function addRoute(string $method, string $route, array $params = [])
    {
        foreach ($params as $key => $value)
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
            }

        $params['middleware'] = $params['middleware'] ?? [];

        $params['variables'] = [];
        $pattern_variables   = '/{(.*?)}/';

        if (preg_match_all($pattern_variables, $route, $matches)) {
            $route               = preg_replace($pattern_variables, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $pattern_route = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$pattern_route][$method] = $params;
    }

    /**
     * Register a PUT route
     * @var string $route 
     * @var array $params 
     */
    public function put(string $route, array $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Register a POST route
     * @var string $route 
     * @var array $params 
     */
    public function post(string $route, array $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Register a GET route
     * @var string $route 
     * @var array $params 
     */
    public function get(string $route, array $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Retrive the current URI without the prefix
     */
    public function getUri()
    {
        $uri    = rtrim($this->request->uri(), '/');
        $return = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        return end($return);
    }

    /**
     * Get the current route
     */
    public function getRoute()
    {
        $uri = $this->getUri();

        $request_method = $this->request->method();

        foreach ($this->routes as $pattern => $methods) {

            if (preg_match($pattern, $uri, $matches))
                if (isset($methods[$request_method])) {

                    unset($matches[0]);

                    $variables = $methods[$request_method]['variables'];

                    $methods[$request_method]['variables']            = array_combine($variables, $matches);
                    $methods[$request_method]['variables']['request'] = $this->request;

                    if (!isset($methods[$request_method]))
                        throw new Exception("Sorry, we couldn't show this route.", 404);

                    return $methods[$request_method];
                }
        }

        throw new Exception("Sorry, we couldn't find the route'$request_method::$uri'.", 404);
    }

    /**
     * Run the router
     * @return Response
     */
    public function run()
    {
        $params = [];
        $route  = $this->getRoute();

        if (!isset($route['controller']))
            throw new Exception('Error when accessing page', 500);

        $reflection = new ReflectionFunction($route['controller']);

        foreach ($reflection->getParameters() as $parameter) {
            $name          = $parameter->getName();
            $params[$name] = $route['variables'][$name] ?? '';
        }

        // call the middlewares queue
        $queue = new \http\middleware\Queue($route['middleware'], $route['controller'], $params);

        return $queue->next($this->request);
    }
}