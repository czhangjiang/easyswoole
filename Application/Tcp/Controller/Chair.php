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

    const NOT_RUNNING = "not_running";
    const RUNNING = "running";

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
        $this->response()->setMessage("{$actionName} not found \n");
    }

    public function index()
    {
        $this->response()->setMessage(time());
    }

    public function start()
    {
        $param = $this->caller()->getArgs();

        $deviceId = $param['deviceId'];
        $data = $this->device($deviceId);

        $client = $this->caller()->getClient();
        $fd = $client->getFd();

        $redis = $this->redis();
        $redis->set($fd, $param['deviceId']);
        $redis->set($param['deviceId'], $fd);
        $this->destoryRedis($redis);

        $sendData = [
            'action' => 'startResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => 1,
                'message' => 'success'
            ]
        ];

        ServerManager::getInstance()->getSwooleServer()->send($fd, json_encode($sendData));
    }

    public function status()
    {
        $param = $this->caller()->getArgs();
        if (!isset($param['deviceId'])) {
            $response['code'] = -1;
            $response['message'] = '设备ID不能为空';
            return $this->response()->setMessage(json_encode($response));
        }

        $data = $this->device($param['deviceId']);
        if (empty($data)) {
            $response['code'] = -1;
            $response['message'] = '设备不存在';
            return $this->response()->setMessage(json_encode($response));
        }

        $redis = $this->redis();
        if ($data->getEqStatus() == 0) {
            $response['code'] = 1;
            $response['message'] = '在线，未运行';
            $redis->sadd(static::NOT_RUNNING, $param['deviceId']);
        } else if ($data->getEqStatus() == 1) {
            $response['code'] = 2;
            $response['message'] = '运行中';
            $redis->sadd(static::RUNNING, $param['deviceId']);
        } else {
            $response['code'] = -1;
            $response['message'] = '设备离线';
        }
        // 销毁redis链接池
        $this->destoryRedis($redis);

        return $this->response()->setMessage($this->encrypt(json_encode($response)));
    }

    public function stop()
    {
        $response = [
            'code' => 1,
            'message' => '设备停止成功',
        ];
        $param = $this->caller()->getArgs();
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

        return $this->response()->setMessage($this->encrypt(json_encode($response)));

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