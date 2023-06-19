<?php

namespace http;

class Response
{
    /**
     * JSON content type
     */
    const RPS_CT_JSON = 'application/json;charset=utf-8';

    /**
     * HTML content type
     */const RPS_CT_HTML = 'text/html';

    /**
     * Response status code
     * @var integer
     */
    private $code = 200;

    /**
     * Response Headers
     * @var array
     */
    private $headers = [];

    /**
     * Response content type
     * @var string
     */
    private $contentType;

    /**
     * Response Content
     * @var mixed
     */
    private $content;

    /**
     * Response constructor.
     * @var mixed
     */
    public function __construct(int|string $code, mixed $content, string $type = self::RPS_CT_JSON)
    {
        $this->code    = $code;
        $this->content = $content;
        $this->setContetType($type);
    }

    /**
     * Defines the content type of response
     */
    public function setContetType(string $contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Add headers
     */
    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * Sets the HTTP response headers.
     */
    public function buildheaders()
    {
        foreach ($this->headers as $key => $value)
            header($key . ': ' . $value);

        // If it is not a redirect, add response code
        if (!in_array('Location', array_keys($this->headers)))
            http_response_code($this->code);
    }

    /**
     * Display the response
     */
    public function display()
    {
        $this->buildheaders();

        switch ($this->contentType) {
            case self::RPS_CT_HTML:
                echo $this->content;
                exit;

            case self::RPS_CT_JSON:
                echo json_encode($this->content);
                exit;
        }
        ;
    }
}