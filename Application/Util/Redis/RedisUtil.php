<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangweitao
 * Date: 2019-03-31
 * Time: 20:08
 */

namespace Application\Util\Redis;


use Application\Util\Pool\RedisObject;

class RedisUtil
{
    private $redis;

    public function __construct(RedisObject $redis)
    {
        $this->redis = $redis;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->redis, $name], $arguments);
    }
}