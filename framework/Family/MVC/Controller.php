<?php
namespace Family\MVC;

use Family\Pool\Context;

class Controller
{
    protected $request;

    public function __construct()
    {
        $context = Context::getContext();
        $this->request = $context->getRequest();
    }
}
