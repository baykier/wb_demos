<?php

namespace Wb\Client;

use Wb\Client;

/**
 * @query order status
 * Class QueryOrderStatusClient
 * @package Wb\Client
 */
class QueryOrderStatusClient extends Client
{
    protected $apiUrl = 'http://dev.borrow.cn/open.php';

    protected $method = 'queryOrderStatus';
}