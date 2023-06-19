<?php

namespace http;

class Request
{
    /**
     * Page URI
     * @var string
     */
    private $uri = [];

    /**
     * The request body
     * @var array
     */
    private static $body = null;

    /**
     * Request GET params
     * @var array
     */
    private $_get = [];

    /**
     * Request POST params
     * @var array
     */
    private $_post = [];

    /**
     * Request Method
     * @var string
     */
    private $method = '';

    /**
     * Request Headers
     * @var array
     */
    private $headers = [];

    /**
     * Create a new Request instance
     * @return void
     */
    public function __construct()
    {
        $this->_get    = $_GET ?? [];
        $this->_post   = $_POST ?? [];
        $this->headers = getallheaders();
        $this->method  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri     = explode('?', $_SERVER['REQUEST_URI'] ?? '')[0];
    }

    /**
     * Retrieves the Request Body
     * @param bool $associative
     * @return mixed
     */
    public static function body(bool $required = true, ?bool $associative = true, ?bool $raw = false)
    {
        $body = file_get_contents('php://input');

        if ($required && empty($body))
            throw new Exception('Error when obtaining form data', 403);

        if (!$raw) {
            $data = json_decode($body, $associative);
            if ($required && !$data)
                throw new Exception('Invalid form data', 403);

            return json_decode($body, $associative);
        }

        return $body;
    }

    /**
     * Retrieves the variables passed to the current router via the HTTP GET method.
     */
    public function get()
    {
        return $this->_get;
    }

    /**
     * Retrieves the variables passed to the current router via the HTTP POST method.
     */
    public function post()
    {
        return $this->_post;
    }

    /**
     * Retrieves the Request Headers
     */
    public function headers(string|null $header = null)
    {
        if ($header) {
            $headers = array_change_key_case($this->headers, CASE_LOWER);
            return $headers[strtolower($header)] ?? null;
        } else
            return $this->headers;
    }

    /**
     * Retrieves the URI
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Retrieves the Method
     */
    public function method()
    {
        return $this->method;
    }
}