<?php

namespace Wb;

use \JsonRPC\Client as JsonRpcClient;

abstract class Client
{
    /**
     * @the JsonRpcServer Remote Url
     * @var
     */
    protected $apiUrl;

    /**
     * @the JsonRpcServer Request Method
     * @var
     */
    protected $method;

    /**
     * @Set Current JsonRpcServer Remote Url
     * @param $url
     */
    public function setApiUrl($url)
    {
        if ($url) {
            $this->apiUrl = $url;
        }
    }

    /**
     * @ Get Current JsonRpcServer Remote Url
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @Set Current JsonRpcServer Remote Method
     * @param $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function send(Request $request)
    {
        try {
            $client = new JsonRpcClient($this->apiUrl);
            $params = $request->packParams();
            $response = $client->execute($this->method,$params);
            return $response;

        }catch (\Exception $e) {
            throw new $e;
        }
    }
}