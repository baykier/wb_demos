<?php

namespace Wb;

use \JsonRPC\Client as JsonRpcClient;

class Client
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
     * @the request instance
     * @var
     */
    protected $request;

    public function __construct($appId = null,Request $request = null)
    {
        if (is_null($request)) {
            $this->request = new Request();
        }
    }

    /**
     * @set the client app id
     * @param $appId
     * @throws \Exception
     */
    public function setAppId($appId)
    {
        $this->request->setAppId($appId);
    }

    /**
     * @get the client app id
     * @return mixed
     */
    public function getAppId()
    {
        return $this->request->getAppId();
    }

    /**
     * @set the client rsa private key
     * @param $rsaPrivateKey
     */
    public function setRsaPrivateKey($rsaPrivateKey)
    {
        $this->request->setRsaPrivateKey($rsaPrivateKey);
    }

    /**
     * @set the bizData
     * @param array $bizData
     */
    public function setBizData(array $bizData)
    {
        $this->request->setBizData($bizData);
    }

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

    /**
     * @get the client request method
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * @return mixed
     */
    public function send()
    {
        if (empty($this->apiUrl)) {
            throw new \Exception('JsonRpcServer Remote Url Can not be Empty');
        }
        if (empty($this->method)) {
            throw new \Exception('JsonRpcServer Remote Produce Can not be Empty');
        }

        $client = new JsonRpcClient($this->apiUrl);
        $client->getHttpClient()->withDebug();
        $params = $this->request->packParams();
        $response = $client->execute($this->method,$params);
        return $response;
    }
}