<?php
namespace Family\Core;

class Route
{
    public static function dispatch($path)
    {
        if ($path == '/favicon.ico') {
           return '';
        }
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
}