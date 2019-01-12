<?php
namespace Family\Core;

use Family\MVC\Controller;
use Family\Pool\Context;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

class Route
{
    public static function dispatchOld($path)
    {
        //默认访问Controller/index.php的index方法
        if (empty($path) || '/' == $path) {
            $controller = 'Index';
            $method = 'Index';
        } else {
            $maps = \explode('/', $path);
            $controller = $maps[1];
            $method = $maps[2];
        }

        $controllerClass = "controller\\{$controller}";
        $class = new $controllerClass;

        return $class->$method();
    }

    public static function dispatch()
    {
        $context = Context::getContext();
        $request = $context->getRequest();
        $path = $request->getUri()->getPath();
        if ('/favicon.ico' == $path) {
            return '';
        }
        $r = Config::get('router');

        //没有路由配置或者配置不可执行，则走默认路由
        if (empty($r) || !is_callable($r)) {
            return self::normal($path, $request);
        }

        //引入fastrouter，进行路由检测
        $diapatcher = simpleDispatcher($r);
        $routeInfo = $diapatcher->dispatch($request->getMethod(), $path);

        //匹配到了
        if (Dispatcher::FOUND === $routeInfo[0]) {
            //匹配的是数组，格式：['controllerName','methodName']
            if (is_array($routeInfo[1])) {
                if (!empty($routeInfo[2]) && \is_array($routeInfo[2])) {
                    //有默认参数
                    $params = $request->getQueryParams() + $routeInfo[2];
                    $request->withQueryParams($params);
                }
                $request->withAttribute(Controller::_CONTROLLER_KEY_, $routeInfo[1][0]);
                $request->withAttribute(Controller::_METHOD_KEY_, $routeInfo[1][1]);
                $controller = new $routeInfo[1][0]();
                $methodName = $routeInfo[1][1];
                $result = $controller->$methodName();
            } elseif (is_string($routeInfo[1])) {
                //字符串，格式：controllerName@MethodName
                list($controllerName, $methodName) = \explode('@', $routeInfo[1]);
                if (!empty($routeInfo[2]) && \is_array($routeInfo[2])) {
                    //有默认参数
                    $params = $request->getQueryParams() + $routeInfo[2];
                    $request->withQueryParams($params);
                }
                $request->withAttribute(Controller::_CONTROLLER_KEY_, $controllerName);
                $request->withAttribute(Controller::_METHOD_KEY_, $methodName);
                $controller = new $controllerName();
                $result = $controller->$methodName();
            } elseif (is_callable($routeInfo[1])) {
                //回调函数，直接执行
                $result = $routeInfo[1](...$routeInfo[2]);
            } else {
                throw new Exception('router error');
            }

            return $result;
        }

        //没找到路由，走默认的路由http://xxx.com/{controllerName}/{MethodName}
        if (Dispatcher::NOT_FOUND === $routeInfo[0]) {
            return self::normal($path, $request);
        }

        //匹配到了，但不允许的http method
        if (Dispatcher::METHOD_NOT_ALLOWED === $routeInfo[0]) {
            throw new Exception("METHOD_NOT_ALLOWED");
        }
    }

    public static function normal($path, $request)
    {
        //默认访问 Controller/index.php的index方法
        if (empty($path) || '/' == $path) {
            $controllerName = 'Index';
            $methodName = 'Index';
        } else {
            $maps = \explode('/', $path);
            if (count($maps) < 2) {
                $controllerName = 'Index';
                $methodName = 'Index';
            } else {
                $controllerName = $maps[1];
                if (empty($maps[2])) {
                    $methodName = 'Index';
                } else {
                    $methodName = $maps[2];
                }
            }
        }
        $controllerName = "controller\\{$controllerName}";
        $request->withAttribute(Controller::_CONTROLLER_KEY_, $controllerName);
        $request->withAttribute(Controller::_METHOD_KEY_, $methodName);
        $controller = new $controllerName();
        return $controller->$methodName();
    }
}
