<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use Application\Task\DeviceTask;
use Application\Tcp\Parse;
use Application\Util\Pool\MysqlPool;
use Application\Util\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
        //PoolManager::getInstance()->register(RedisPool::class, Config::getInstance()->getConf('REDIS.POOL_MAX_NUM'));
    }

    public static function mainServerCreate(EventRegister $register)
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $subPort = $server->addListener(Config::getInstance()->getConf('MAIN_SERVER.LISTEN_ADDRESS'), 8888, SWOOLE_TCP);
        $socketConfig = new \EasySwoole\Socket\Config();
        $socketConfig->setType($socketConfig::TCP);
        $socketConfig->setParser(new Parse());
        //设置解析异常时的回调,默认将抛出异常到服务器
        $socketConfig->setOnExceptionHandler(function ($server, $throwable, $raw, $client, $response) {
            $log = [
                'fd' => $client->getFd(),
                'exception' => $throwable->getMessage(),
                'raw' => $raw,
                'response' => $response
            ];
            Logger::getInstance()->log(json_encode($log));
            $server->close($client->getFd());
        });
        $dispatch = new \EasySwoole\Socket\Dispatcher($socketConfig);
        $subPort->on('receive', function (\swoole_server $server, int $fd, int $reactor_id, string $data) use ($dispatch) {
            $log = [
                'fd' => $fd,
                'reactor_id' => $reactor_id,
                'data' => $data,
            ];
            Logger::getInstance()->log(json_encode($log));
            $dispatch->dispatch($server, $data, $fd, $reactor_id);
        });
        $subPort->set(
            [
                'open_length_check'     => false,
                'package_max_length'    => 81920,
                'package_length_type'   => 'N',
                'package_length_offset' => 0,
                'package_body_offset'   => 4,
//                'heartbeat_check_interval' => 5,
//                'heartbeat_idle_time'      => 30,
            ]
        );

        ################### mysql 热启动   #######################
        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
            if ($workerId == 0) {
                //每个worker进程都预创建连接
                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(5);//最小创建数量
                //PoolManager::getInstance()->getPool(RedisPool::class)->preLoad(5);

                Timer::getInstance()->loop(30 * 1000, function () {
                    $task = new DeviceTask();
                    $task->handle(1);
                });

                Timer::getInstance()->loop(30 * 60 * 60 * 1000, function () {
                    $task = new DeviceTask();
                    $task->handle(0);
                });
            }
        });

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}