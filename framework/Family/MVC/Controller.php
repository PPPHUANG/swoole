<?php
namespace Family\MVC;

use Family\Pool\Context;

class Controller
{
    protected $request;

    const _CONTROLLER_KEY_ = '_CTR_';
    const _METHOD_KEY_ = '_METHOD_';

    public function __construct()
    {
        $context = Context::getContext();
        $this->request = $context->getRequest();
    }
}
