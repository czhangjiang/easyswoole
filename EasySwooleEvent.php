<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use Application\Tcp\Parse;
use Application\Util\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use mysql_xdevapi\Exception;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        $mysqlConf = PoolManager::getInstance()->register(MysqlPool::class,
            Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));

        if ($mysqlConf === null) {
            throw new Exception('mysql 注册失败');
        }

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
            echo  "tcp服务3  fd:{$client->getFd()} 发送数据异常 \n";
            $server->close($client->getFd());
        });
        $dispatch = new \EasySwoole\Socket\Dispatcher($socketConfig);
        $subPort->on('receive', function (\swoole_server $server, int $fd, int $reactor_id, string $data) use ($dispatch) {
            echo "tcp服务  fd:{$fd} 发送消息:{$data}\n";
            $dispatch->dispatch($server, $data, $fd, $reactor_id);
        });
        $subPort->set(
            [
                'open_length_check'     => true,
                'package_max_length'    => 81920,
                'package_length_type'   => 'N',
                'package_length_offset' => 0,
                'package_body_offset'   => 4,
            ]
        );

        ################### mysql 热启动   #######################
//        $register->add($register::onWorkerStart, function (\swoole_server $server, int $workerId) {
//            if ($server->taskworker == false) {
//                //每个worker进程都预创建连接
//                PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(5);//最小创建数量
//            }
//        });

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