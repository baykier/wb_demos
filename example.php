<?php
/**
 * Created by PhpStorm.
 * Author: Baykier<1035666345@qq.com>
 * Date: 2017/8/1
 * Time: 17:16
 */
use Wb\Client\QueryOrderStatusClient as Client;
use Wb\Request;


require_once __DIR__ . '/vendor/autoload.php';

$request = new Request();
$request->setAppId('170040');
$request->order_num = '17170040073130486552';

$client = new Client();
$resp = $client->send($request);
var_dump($resp);