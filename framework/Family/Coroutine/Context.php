<?php
namespace Family\Coroutine;

class Context
{
    /**
     * @var \swoole_http_request
     */
    private $request;

    /**
     * @var \swoole_http_response
     */
    private $response;

    private $map = [];
}