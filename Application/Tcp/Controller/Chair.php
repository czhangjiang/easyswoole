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
use EasySwoole\Socket\AbstractInterface\Controller;


class Chair extends Controller
{

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
        $response = [
            'code' => 1,
            'message' => '设备连接成功',
            'data' => []
        ];
        $param = $this->caller()->getArgs();
        if(!isset($param['deviceId'])) {
            $response['code'] = -1;
            $response['message'] = '设备ID不能为空';
            $this->response()->setMessage(json_encode($response));
        }

        $deviceId = $param['deviceId'];
        $data = MysqlPool::invoke(function (MysqlObject $mysqlObject) use ($deviceId){
            $good = new Goods($mysqlObject);
            //new 一个条件类,方便传入条件
            $goodBean = new GoodBean();
            $goodBean->setGoodsSn($deviceId);

            return $good->getOne($goodBean);
        });
        $response['data'] = $data;
        $this->response()->setMessage(json_encode($response));
    }

}