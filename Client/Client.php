<?php
include "../vendor/autoload.php";
define('EASYSWOOLE_ROOT', realpath(dirname(getcwd())));
\EasySwoole\EasySwoole\Core::getInstance()->initialize();
/**
 * tcp 客户端3,验证数据包处理粘包 以及转发到控制器写法
 */
go(function () {
    $client = new \Swoole\Client(SWOOLE_SOCK_TCP);
    $client->set(
        [
            'open_length_check'     => true,
            'package_max_length'    => 81920,
            'package_length_type'   => 'N',
            'package_length_offset' => 0,
            'package_body_offset'   => 4,
        ]
    );
    if (!$client->connect('127.0.0.1', 8888, 0.5)) {
        exit("connect failed. Error: {$client->errCode}\n");
    }
    $data = [
        'controller' => 'Chair',
        'action'     => 'stop',
        'param'      => [
            'deviceId' => 'JS004311'
        ],
    ];
    $str = json_encode($data);
    $key = '1234567891234567';
    $cipher = 'AES-128-CBC';
    $encrypter = new \Illuminate\Encryption\Encrypter($key, $cipher);
    $str = $encrypter->encryptString($str);
    $client->send(encode($str));
    $data = $client->recv();//服务器已经做了pack处理
    $data = decode($data);//需要自己剪切解析数据
    echo "服务端回复: $data \n";
    $data = $encrypter->decryptString($data);
    print_r(json_decode($data));
//    $client->close();
});
/**
 * 数据包 pack处理
 * encode
 * @param $str
 * @return string
 * @author Tioncico
 * Time: 9:50
 */
function encode($str)
{
    return pack('N', strlen($str)) . $str;
}
function decode($str)
{
    $data = substr($str, '4');
    return $data;
}