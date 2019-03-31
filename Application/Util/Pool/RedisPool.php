<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangweitao
 * Date: 2019-03-31
 * Time: 17:17
 */

namespace Application\Util\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\EasySwoole\Config;

class RedisPool extends AbstractPool
{
    protected function createObject()
    {
        // TODO: Implement createObject() method.
        $redis = new RedisObject();
        $conf = Config::getInstance()->getConf('REDIS');
        if( $redis->connect($conf['host'],$conf['port'])){
            if(!empty($conf['auth']) && isset($conf['auth'])) {
                $redis->auth($conf['auth']);
            }
            return $redis;
        }else{
            return null;
        }
    }
}