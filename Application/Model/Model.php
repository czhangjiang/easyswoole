<?php
/**
 * Created by PhpStorm.
 * User: zhangweitao
 * Date: 19-3-10
 * Time: 下午4:28
 */

namespace Application\Model;


use Application\Util\Pool\MysqlObject;

class Model
{
    private $db;

    public function __construct(MysqlObject $dbObject)
    {
        $this->db = $dbObject;
    }

    protected function getDb():MysqlObject
    {
        return $this->db;
    }

    public function getDbConnection():MysqlObject
    {
        return $this->db;
    }
}