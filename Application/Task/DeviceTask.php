<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangweitao
 * Date: 2019-03-31
 * Time: 20:33
 */

namespace Application\Task;

use Application\Util\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\ServerManager;
use Illuminate\Encryption\Encrypter;

class DeviceTask
{

    /**
     * @param $eqStatus
     */
    public function handle($eqStatus)
    {
        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj(Config::getInstance()->getConf('MYSQL.POOL_TIME_OUT'));
        $db->where('eq_status', $eqStatus);
        $data = $db->get('lz_goods');
        foreach ($data as $value) {
            try {
                $fd = $value['meid'];
                if ($fd) {
                    $data = [
                        'action' => 'status',
                        'deviceId' => $value['goods_sn'],
                        'param' => []
                    ];
                    //$sendStr = $this->encode($this->encrypt(json_encode($data)));
                    $sendStr = $this->encode(json_encode($data));
                    ServerManager::getInstance()->getSwooleServer()->send($fd, $sendStr);
                }
            } catch (\Exception $e) {
                Logger::getInstance()->log($e->getMessage());
                $db->where('fd', $fd)->update(['meid' => '', 'status' => 0]);
                ServerManager::getInstance()->getSwooleServer()->close($fd);
            }

        }

        PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
    }

    public function encrypt($string)
    {
        $key = Config::getInstance()->getConf('KEY');
        $cipher = Config::getInstance()->getConf('CIPHER');
        $encrypter = new Encrypter($key, $cipher);
        return $encrypter->encryptString($string);
    }

    public function encode($str)
    {
        return pack('N', strlen($str)) . $str;
    }
}