<?php
namespace controller;

use Family\Pool\Context;

class Index
{
    public function index()
    {
        //通过context拿到$request,不用担心数据错乱
        $context = Context::getContext();
        $request = $context->getRequest();
        return 'i am family by route';
    }

    public function ppphuang()
    {
        return 'i am ppphuang';
    }
}