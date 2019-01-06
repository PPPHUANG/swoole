<?php
namespace Family\Pool;

use Family\Coroutine\Coroutine;

/**
 * Class Context
 * @package Family\Coroutine
 * @desc context pool,请求隔离，请求之内任何地方可以存取
 */
class Context
{
    /**
     * @var array context pool
     */
    public static $pool = [];

    /**
     * @return \Family\Coroutine\Context
     * @desc 可以在任意协程获取到context
     */
    public static function getContext()
    {
        $id = Coroutine::getPid();
        if (isset(self::$pool[$id])) {
            return self::$pool[$id];
        }
        return null;
    }

    /**
     * @desc 清除context
     */
    public static function clear()
    {
        $id = Coroutine::getPid();
        if (isset(self::$pool[$id])) {
            unset(self::$pool[$id]);
            Coroutine::clear($id);
        }
    }

    /**
     * @param $context
     * @desc 设置context
     */
    public static function set($context)
    {
        $id = Coroutine::getPid();
        self::$pool[$id] = $context;
    }
}
