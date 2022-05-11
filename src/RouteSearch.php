<?php
namespace Terrazza\Component\Routing;

class RouteSearch {
    /**
     * @var string
     */
    private string $uri;
    /**
     * @var string
     */
    private string $method;

    /**
     * @param string $uri
     * @param string $method
     */
    public function __construct(string $uri, string $method="get"){
        $this->uri                                  = $uri;
        $this->method                               = strtolower($method);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}