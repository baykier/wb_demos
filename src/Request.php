<?php
namespace Wb;

class Request
{
    /**
     * @current version
     */
    const VERSION = '1.0';

    protected $formatData = array(
        'version' => self::VERSION,
        'app_id' => '',
        'timestamp' => '',
        'sign' => '',
        'return_url' => '',
        'biz_data' => array(),
    );

    /**
     * @ Rsa PrivateKey
     * @var string
     */
    private $rsaPrivateKey;

    /**
     * @Rsa PublicKey
     * @var
     */
    private $rsaPublicKey;

    public function __construct($appId = null,$timestamp = null,$sign = null,$returnUrl = null,array $bizData = array())
    {

        if (!is_null($appId)) {
            $this->setAppId($appId);
        }
        if (!is_null($timestamp)) {
            $this->formatData['timestamp'] = $timestamp;
        }else {
            $this->formatData['timestamp'] = time();
        }
        if (!is_null($sign)) {
            $this->formatData['sign'] = $sign;
        }
        if (!is_null($returnUrl)) {
            $this->formatData['return_url'] = $returnUrl;
        }
        if (!is_null($bizData)) {
            $this->formatData['biz_data'] = $bizData;
        }else {
            $this->formatData['biz_data'] = array();
        }
    }

    /**
     * @set current request appId
     * @param $appId
     * @throws \Exception
     */
    public function setAppId($appId)
    {
        if (!is_numeric($appId) || strlen($appId) != 6) {
            throw new \Exception(sprintf("Invalid params [%s] for appId.",$appId));
        }
        $this->formatData['app_id'] = $appId;
    }

    /**
     * @get current request appId
     */
    public function getAppId()
    {
        return $this->formatData['app_id'];
    }

    /**
     * @Set Current Return Url
     * @param $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        if ($returnUrl) {
            $this->formatData['return_url'] = $returnUrl;
        }
    }

     /**
      * @set the biz_data for the formatData
      * @param array $bizData
      */
     public function setBizData(array $bizData)
     {
         $this->formatData['biz_data'] = $bizData;
     }

     /**
      * @get the biz_data
      * @return mixed
      */
     public function getBizData()
     {
         return $this->formatData['biz_data'];
     }

    /**
     * @get current return url
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->formatData['return_url'];
    }

    /**
     * @set the rsa private key
     * @param $rsaPrivateKey
     */
    public function setRsaPrivateKey($rsaPrivateKey)
    {
        $this->rsaPrivateKey = $rsaPrivateKey;
    }

    /**
     * @set the rsa public key
     * @param $rsaPublicKey
     */
    public function setRsaPublicKey($rsaPublicKey)
    {
        $this->rsaPublicKey = $rsaPublicKey;
    }

    /**
     * @generate sign str
     * @return string
     */
    protected function generateSignStr()
    {
        $data = $this->formatData;
        asort($data);
        $str = '';
        foreach ($data as $key => $value) {
            if (!empty($value) && $key != 'sign') {
                if ($key == 'biz_data') {
                    $value = md5(json_encode($value));
                }
                $str .= $key . '=' .  $value . '&';
            }
        }
        return rtrim($str,'&');
    }

    /**
     * @verify the request data signature
     * @return bool
     * @throws \Exception
     */
    public function verify()
    {
        if (empty($this->rsaPublicKey)) {
            throw new \Exception("The RSA PUBLIC KEY Can not Empty");
        }
        $rsaPubKeyHandle = openssl_pkey_get_public($this->rsaPublicKey);
        if (!$rsaPubKeyHandle)
        {
            return false;
        }
        $check = openssl_verify($this->generateSignStr(),base64_decode($this->formatData['sign']),$rsaPubKeyHandle);
        openssl_free_key($rsaPubKeyHandle);
        return (bool) ($check == 1);
    }

    /**
     * @sign the request data signature
     * @return string
     * @throws \Exception
     */
    public function sign()
    {
        if (empty($this->rsaPrivateKey)) {
            throw new \Exception("The RSA PRIVATE KEY Can not Empty");
        }
        $rsaPriKeyHandle = openssl_pkey_get_private ($this->rsaPrivateKey);
        if (!$rsaPriKeyHandle)
        {
            throw new \Exception('Sign Error');
        }
        openssl_sign($this->generateSignStr(), $sign, $rsaPriKeyHandle);
        openssl_free_key($rsaPriKeyHandle);
        return base64_encode($sign);
    }

    /**
     * @pack the request data
     * @return array
     * @throws \Exception
     */
    public function packParams()
    {
        $this->formatData['sign'] = $this->sign();
        return array($this->formatData);
    }

    /**
     * @parse the request data
     * @param $params
     * @throws \Exception
     */
    public function parseParams(array $params)
    {
        if (empty($params)) {
            throw new \Exception("Empty params");
        }
        if (!isset($params['sign']) || empty($params['sign'])) {
            throw new \Exception('The Sign Params Can not be Empty.');
        }
        $this->formatData = array_replace($this->formatData,$params);

        if (!$this->verify()) {
            throw new \Exception('Verify Sign Failed.');
        }
    }
    /**
     * @magic method for set the request biz_data
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->formatData['biz_data'][$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->formatData['biz_data'][$name]) ? $this->formatData['biz_data'][$name] : '';
    }

    /**
     * @used for isset(),empty()
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->formatData['biz_data'][$name]) ? true : false;
    }
}