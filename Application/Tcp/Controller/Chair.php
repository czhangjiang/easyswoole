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

    /**
     * 设备通知云端服务开启，云端返回给设备开启
     *
     * @return bool|mixed
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
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
                ]
            ];
            //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
            $sendStr = $this->encode(json_encode($sendData));
            return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
        }

        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $fd) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'meid' => $fd
            ]);
            Logger::getInstance()->log(json_encode($mysqlObject->getLastQuery()));
        });

        $sendData = [
            'action' => 'startResp',
            'deviceId' => $param['deviceId'],
            'param' => [
                'code' => 1,
            ]
        ];
        //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
        $sendStr = $this->encode(json_encode($sendData));
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
    }

    /**
     * 设备通知监控云端的心跳
     */
    public function statusResp()
    {
        $client = $this->caller()->getClient();
        $fd = $client->getFd();
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId']);
    }

    /**
     * 微信通知云端，云端通知设备开始工作
     *
     * @return bool|mixed|void
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function work()
    {
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId']);
        $data = $data->toArray();
        if ($data['eq_status'] == 1) {
            return $this->response()->setMessage(json_encode(['code' => -1, 'message' => '设备已经在运行']));
        }
        $fd = $data['meid'];
        $time = $param['time'];
        $deviceId = $param['deviceId'];
        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $time) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'time' => $time
            ]);
            Logger::getInstance()->log(json_encode($mysqlObject->getLastQuery()));
        });
        $sendData = [
            'action' => 'work',
            'deviceId' => $param['deviceId'],
            'param' => [
                'time' => $time
            ]
        ];
        //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
        $sendStr = $this->encode(json_encode($sendData));
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
    }

    /**
     * 设备通知云端工作状态
     *
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function workResp()
    {
        $param = $this->caller()->getArgs();
        $this->device($param['deviceId']);
        $deviceId = $param['deviceId'];
        $code = $param['code'];
        if ($code == 1) {
            $eqStatus = 1;
        } else {
            $eqStatus = 0;
        }

        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $eqStatus) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'eq_status' => $eqStatus
            ]);
            Logger::getInstance()->log(json_encode($mysqlObject->getLastQuery()));
        });
    }

    /**
     * 设备通知云端停止
     *
     * @return bool|mixed|void
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function stop()
    {
        $param = $this->caller()->getArgs();
        $deviceId = $param['deviceId'];
        $data = $this->device($deviceId)->toArray();
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
            ]
        ];
        $fd = $data['meid'];
        //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
        $sendStr = $this->encode(json_encode($sendData));
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
    }

    /**
     * 微信通知云端，云端通知设备停止
     *
     * @return bool|mixed
     */
    public function wxStop()
    {
        $param = $this->caller()->getArgs();
        $data = $this->device($param['deviceId'])->toArray();
        $fd = $data['meid'];
        $sendData = [
            'action' => 'stop',
            'deviceId' => $param['deviceId'],
            'param' => []
        ];
        //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
        $sendStr = $this->encode(json_encode($sendData));
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
    }

    /**
     * 设备返回云端设备停止
     *
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function stopResp()
    {
        $param = $this->caller()->getArgs();
        $this->device($param['deviceId']);
        $deviceId = $param['deviceId'];
        $code = $param['code'];
        if ($code == 1) {
            $eqStatus = 0;
        } else {
            $eqStatus = 1;
        }

        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $eqStatus) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'eq_status' => $eqStatus
            ]);
            Logger::getInstance()->log(json_encode($mysqlObject->getLastQuery()));
        });
    }

    /**
     * 微信通知云端设备暂停，云端通知设备暂停
     *
     * @return bool|mixed
     */
    public function wxPause()
    {
        $param = $this->caller()->getArgs();
        $deviceId = $param['deviceId'];
        $data = $this->device($deviceId)->toArray();
        $fd = $data['meid'];
        $sendData = [
            'action' => 'pause',
            'deviceId' => $param['deviceId'],
            'param' => []
        ];
        //$sendStr = $this->encode($this->encrypt(json_encode($sendData)));
        $sendStr = $this->encode(json_encode($sendData));
        return ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
    }

    /**
     * 设备返回云端暂停状态
     *
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @throws \Throwable
     */
    public function pauseResp()
    {
        $param = $this->caller()->getArgs();
        $this->device($param['deviceId']);
        $deviceId = $param['deviceId'];
        $code = $param['code'];
        if ($code == 1) {
            $eqStatus = 0;
        } else {
            $eqStatus = 1;
        }

        MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId, $eqStatus) {
            Logger::getInstance()->log(json_encode(['deviceId' => $deviceId]));
            $mysqlObject->where('goods_sn', $deviceId)->update('lz_goods', [
                'eq_status' => $eqStatus
            ]);
            Logger::getInstance()->log(json_encode($mysqlObject->getLastQuery()));
        });
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

    public function encode($str)
    {
        return pack('N', strlen($str)) . $str;
    }
}