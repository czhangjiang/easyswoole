<?php
/**
 * Created by PhpStorm.
 * User: zhangweitao
 * Date: 19-3-10
 * Time: 下午2:51
 */

namespace Application\Tcp\Controller;

use Application\Model\Goods\GoodBean;
use Application\Model\Goods\Goods;
use Application\Util\Pool\MysqlObject;
use Application\Util\Pool\MysqlPool;
use Application\Util\Pool\RedisPool;
use Application\Util\Redis\RedisUtil;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Socket\AbstractInterface\Controller;
use Illuminate\Encryption\Encrypter;


class Chair extends Controller
{

    private $key;
    private $cipher;

    public function __construct()
    {
        parent::__construct();
        $key = Config::getInstance()->getConf('KEY');
        $cipher = Config::getInstance()->getConf('CIPHER');
        $this->key = $key;
        $this->cipher = $cipher;

    }

    function actionNotFound(?string $actionName)
    {
        return $this->response()->setMessage("{$actionName} not found \n");
    }

    public function index()
    {
        return $this->response()->setMessage(time());
    }

    public function start()
    {
        $param = $this->caller()->getArgs();

        $deviceId = $param['deviceId'];
        $data = $this->device($deviceId);
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        if (empty($data)) {
            $sendData = [
                'action' => 'startResp',
                'deviceId' => $param['deviceId'],
                'param' => [
                    'code' => -1,
                    'message' => 'device not exits'
                ]
            ];
            return ServerManager::getInstance()->getSwooleServer()->send($fd, $this->encrypt(json_encode($sendData)));
        }


        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $fd) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $result = $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'meid' => $fd
            ]);
            return $result;
        });

        $sendData = [
            'action' => 'startResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => 1,
                'message' => 'success'
            ]
        ];

        return ServerManager::getInstance()->getSwooleServer()->send($fd, $this->encrypt(json_encode($sendData)));
    }

    public function statusResp()
    {
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId']);
        if ($data['eq_status'] == 0 ) {
            $code = 2;
        } else {
            $code = 1;
        }

        $sendData = [
            'action' => 'startResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => $code,
                'message' => 'success'
            ]
        ];

        return ServerManager::getInstance()->getSwooleServer()->send($fd, $this->encrypt(json_encode($sendData)));
    }

    public function workResp()
    {
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId']);
        if ($data['eq_status'] == 0 ) {
            $code = 2;
        } else {
            $code = 1;
        }

        $sendData = [
            'action' => 'startResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => $code,
                'message' => 'success'
            ]
        ];

        return ServerManager::getInstance()->getSwooleServer()->send($fd, $this->encrypt(json_encode($sendData)));
    }


    public function startWork()
    {
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId']);
        if (empty($data)) {
            return $this->response()->setMessage(json_encode([
                'code' => -1,
                'message' => '没有此设备'
            ]));
        }

        $fd = $data['meid'];
        $sendData = [
            'action' => 'start',
            'deviceId' => $param['deviceId'],
            'param' => [
                'time' => time()
            ]
        ];
        ServerManager::getInstance()->getSwooleServer()->send($fd, $this->encrypt(json_encode($sendData)));
        return $this->response()->setMessage(json_encode([
            'code' => 1,
            'message' => 'success'
        ]));
    }

    public function stop()
    {
        $response = [
            'code' => 1,
            'message' => '设备停止成功',
        ];
        $param = $this->caller()->getArgs();
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        if(!isset($param['deviceId'])) {
            $response['code'] = -1;
            $response['message'] = '设备ID不能为空';
            return $this->response()->setMessage(json_encode($response));
        }

        $deviceId = $param['deviceId'];
        $data = $this->device($deviceId);
        if (empty($data)) {
            $response['code'] = -1;
            $response['message'] = '设备不存在';
            return $this->response()->setMessage(json_encode($response));
        }

        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $result = $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'eq_status' => 0
            ]);
            return $result;
        });

        $sendData = [
            'action' => 'stopResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => 1,
                'message' => 'success'
            ]
        ];
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendData);

    }

    private function device($deviceId)
    {
        $data = MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId){
            $good = new Goods($mysqlObject);
            //new 一个条件类,方便传入条件
            $goodBean = new GoodBean();
            $goodBean->setGoodsSn($deviceId);

            return $good->getOne($goodBean);
        });

        return $data;
    }

    public function encrypt($string)
    {
        $encrypter = new Encrypter($this->key, $this->cipher);
        return $encrypter->encryptString($string);
    }

    public function decrypt($string)
    {
        $decrypt = new Encrypter($this->key, $this->cipher);
        return $decrypt->decryptString($string);
    }

    public function redis()
    {
        $redis = PoolManager::getInstance()->getPool(RedisPool::class)->getObj();
        $redisUtil = new RedisUtil($redis);
        return $redisUtil;
    }

    public function destoryRedis($redis)
    {
        PoolManager::getInstance()->getPool(RedisPool::class)->recycleObj($redis);
    }

}