<?php
namespace controller;

use Family\MVC\Controller;

class Index extends Controller
{
    public function index()
    {
        // //通过context拿到$request,不用担心数据错乱
        // $context = Context::getContext();
        // $request = $context->getRequest();
        return 'i am family by route';
    }

    public function ppphuang()
    {
        return 'i am ppphuang';
    }

    public function user()
    {
        if (empty($this->request->get['uid'])) {
            throw new \Exception('uid 不能为空');
        }
        $result = UserService::getInstance()->getUserInfoByUId($this->request->get['uid']);
        return \json_encode($result);
    }

    public function list()
    {
        $result = UserService::getInstance()->getUserInfoList();
        return \json_encode($result);
    }

    public function add()
    {
        $array = [
            'name' => $this->request->get['name'],
            'password' => $this->request->get['password']
        ];

        return UserService::getInstance()->add($array);
    }

    public function update()
    {
        $array = [
            'name' => $this->request->get['name'],
            'password' => $this->request->get['password']
        ];

        $id = $this->request->get['id'];
        return UserService::getInstance()->updateById($array, $id);
    }

    public function delete()
    {
        $id = $this->request->get['id'];
        return UserService::getInstance()->deleteById($id);
    }
}
