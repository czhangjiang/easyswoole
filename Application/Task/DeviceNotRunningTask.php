<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangweitao
 * Date: 2019-03-31
 * Time: 20:36
 */

namespace Application\Task;


use Application\Util\Pool\RedisObject;

class DeviceNotRunningTask
{
    private $redisObject;

    public function __construct(RedisObject $redisObject)
    {
        $this->redisObject = $redisObject;
    }

    public function handle()
    {

    }
}