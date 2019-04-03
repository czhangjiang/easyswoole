<?php
/**
 * Created by PhpStorm.
 * User: zhangweitao
 * Date: 19-3-10
 * Time: 下午2:36
 */

namespace Application\Tcp;


use Application\Tcp\Controller\Chair;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;
use Illuminate\Encryption\Encrypter;
use EasySwoole\EasySwoole\Logger;

class Parse implements ParserInterface
{

    public function decode($raw, $client): ?Caller
    {
        $data = substr($raw, '4');
        Logger::getInstance()->log($data);
//        $key = Config::getInstance()->getConf('KEY');
//        $cipher = Config::getInstance()->getConf('CIPHER');
//        $encrypter = new Encrypter($key, $cipher);
//        $data = $encrypter->decryptString($data);

        //为了方便,我们将json字符串作为协议标准
        $data = json_decode($data, true);
        $bean = new Caller();
        $action = !empty($data['action']) ? $data['action'] : 'index';
        $param = !empty($data['param']) ? $data['param'] : [];
        $deviceId = !empty($data['deviceId']) ? $data['deviceId'] : '';
        $param['deviceId'] = $deviceId;
        $controller = 'Application\Tcp\Controller\Chair';
        $bean->setControllerClass($controller);
        $bean->setAction($action);
        $bean->setArgs($param);
        return $bean;
    }

    public function encode(Response $response, $client): ?string
    {
        return pack('N', strlen($response->getMessage())) . $response->getMessage();
    }
}