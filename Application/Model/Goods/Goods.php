<?php
/**
 * Created by PhpStorm.
 * User: zhangweitao
 * Date: 19-3-10
 * Time: 下午4:39
 */

namespace Application\Model\Goods;


use Application\Model\Model;

class Goods extends Model
{
    protected $table = 'lz_goods';

    function getOne(GoodBean $goodBean): ?GoodBean
    {
        $good = $this->getDb()
            ->where('goods_sn', $goodBean->getGoodsSn())
            ->getOne($this->table);
        if (empty($good)) {
            return null;
        }
        return new GoodBean($good);
    }

}