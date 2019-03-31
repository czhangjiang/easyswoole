<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangweitao
 * Date: 2019-03-31
 * Time: 17:17
 */

namespace Application\Util\Pool;

use Co\Redis;
use EasySwoole\Component\Pool\PoolObjectInterface;
class RedisObject extends Redis implements PoolObjectInterface
{
    function gc()
    {
        // TODO: Implement gc() method.
        $this->close();
    }
    function objectRestore()
    {
        // TODO: Implement objectRestore() method.
    }
    function beforeUse(): bool
    {
        // TODO: Implement beforeUse() method.
        return true;
    }
}
