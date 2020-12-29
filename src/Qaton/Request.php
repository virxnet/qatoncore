<?php

namespace VirX\Qaton;

use VirX\Qaton\Http;

class Request
{
    public $sub_dir;
    public $path;
    public $headers;
    public $request;
    public $query;
    public $body;
    public $files;
    public $method;

    public function __construct(string $sub_dir = '/')
    {
        $this->sub_dir = $sub_dir;
        $this->setRequestUrl();
        $this->headers = Http::getHeaders();
        $this->request = Http::getRequest();
        $this->query = Http::getQuery();
        $this->get = $this->query;
        $this->body = Http::getPost();
        $this->post = $this->body;
        $this->files = Http::getFiles();
        $this->method = Http::getMethod();
    }

    public function input($key)
    {
        return $this->get[$key];
    }

    public function setRequestUrl()
    {
        if (mb_substr(Http::server('REQUEST_URI'), 0, mb_strlen($this->sub_dir)) === $this->sub_dir) {
            $this->path = '/' . mb_substr(Http::server('REQUEST_URI'), mb_strlen($this->sub_dir));
        } else {
            $this->path = Http::server('REQUEST_URI');
        }
    }
}
