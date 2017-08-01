<?php
namespace Wb;

use \ParagonIE\EasyRSA\PublicKey;
use \ParagonIE\EasyRSA\PrivateKey;
use phpseclib\Crypt\RSA;

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
        //set the rsaPrivateKey
        $this->rsaPrivateKey = <<<PRIVATEKEY
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAz4pCNC7epGRpyjkuQzVgOQ07OUXN9Xr8mbeNqEniKsZHJfbX
YU7HZ8AqHNADNAqiMY30nsz1qPAXOjFVMChrI5fmKjvZZqih57r7AHi322qby5SI
6O5HbPyw2NmAB911cHE4lpQue7juHQ2m71KXJtwFqNhxwpWrPJkOucbwOF1iOgWe
tbTR46mwKEopk+yZwY0EYKN8RtTW62J9B0HrpLYMbWQusarHL5EHP6oI8W0Pcks8
ZrwejCZ8iJ2w8DnNcn/WYsLcWgkk1jjqhedxHqRb3wqWk6y26uR9uSryEWr+7PNN
I8ON37xH8AxA5jtZVBqj2d5pLe7LVTKN+virgwIDAQABAoIBAF4TMt1KnZtw9M84
yjKm4D4cNEtKzAhJPnVDUdAF5aI0DI417P1r41GxNqWm2LzfURQbX9YX3Ac/BZhY
QmA5Ag+5TBi61loFeJZ9GEfncJfiJErMwp6rW+8YP+Wb+cAW76QPfnIrK0Lj2fOL
e68iBegUdfBKZI6qn1sxmg42Ei/JuqnWvgmvlAzzzpxj94mo7Ko8WlPS1pX6qoiq
H20ukzt7alZDVnNY3r7mJPfnfuQoDRWr2RgNog8QRquFqzHMuAcizqUD9HrmoqZK
KLxAeIKMtossY6iIkN8xjv6sVnBmIjKpATXDAyrQbA1ATVC++BaeQozSz1tdZNGQ
amPftxkCgYEA/2t0FlVQJtq0bSTJ6N2j5Oz0W6ecIx7yE5QW3OoNvEGExXZ1jRVk
9wxqNN8zkH16MG9M6eZnAIgoed/lFTAb0clvcsd/Xouv1Y/7bJFfDpEP3DVuDnBZ
ypv9chELtRDCWzIEc043lFUUYBeAbjlLOMjlSA+QjkVAPRXe3t2GRJ8CgYEA0AL1
maRugvWEqSNP0rRMSdfEdPtWw3rlXqa8Bc55cLvEI15T46zFv2MK78vpDO92u+gJ
2sDfDEZYFTA8qlX55Pr7vlMNbfoZUeusBRLzY/dVA0UbLQ08PzgfbZ5SMU1iTpmH
o+hqzErqcUfSJT1HVDn6FgFKDaFHiCnteOiRqp0CgYABVTg33Z4bdcy3PRfopS9z
xGDKEafY7xJoU7+Cy53iu5zLwwB+CfyK5X+wYvHL8TuwAQSvu8oR0KQVbrutTqD2
iUyRlsTtY2E5hhTTzjZmxw8EISs/3Ao76nB6Jeifu0SoYSxwxZm4pnECx1yeNqJT
24iGxb4FYAsjxndxRkqrFQKBgCdCN7pMt3LOBcCqYnlg//j72R8/BIwWWM35aAks
g+0L8yO9vNV+mT/a4IiLkquXUnB6hcmclzxI1n0BQqHfYi+eUv8Dy8gS6M52TVwT
zI30cz4Pv+ZL1jAUVpIozFhzw3cUMO51ghqWlRLWPEo8+4Zg/ttCWQijhM2lJCWq
tztdAoGARHi05COrPo2NamX+fdB640JO5OiSYZpPVgrAGsV/xKBAgtun3YFB/Inv
pvijeusDfpMLH1k8UGXSpVrZ8Ofl2iKBGpVS5SlhnrCmTNvZZs1c3nPwtQzmFmRx
+szNK+oodEO7WZ08159k1eM+OCn5r9Q19qDVTWQ7hETNkx+3E8A=
-----END RSA PRIVATE KEY-----
PRIVATEKEY;
        $this->rsaPublicKey = <<<PUBLICKEY
-----BEGIN CERTIFICATE-----
MIIDVzCCAj+gAwIBAgIJALS2KUzSqqeDMA0GCSqGSIb3DQEBBQUAMEIxCzAJBgNV
BAYTAlhYMRUwEwYDVQQHDAxEZWZhdWx0IENpdHkxHDAaBgNVBAoME0RlZmF1bHQg
Q29tcGFueSBMdGQwHhcNMTcwNzEwMDgxNjQ4WhcNMjAwNzA5MDgxNjQ4WjBCMQsw
CQYDVQQGEwJYWDEVMBMGA1UEBwwMRGVmYXVsdCBDaXR5MRwwGgYDVQQKDBNEZWZh
dWx0IENvbXBhbnkgTHRkMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
z4pCNC7epGRpyjkuQzVgOQ07OUXN9Xr8mbeNqEniKsZHJfbXYU7HZ8AqHNADNAqi
MY30nsz1qPAXOjFVMChrI5fmKjvZZqih57r7AHi322qby5SI6O5HbPyw2NmAB911
cHE4lpQue7juHQ2m71KXJtwFqNhxwpWrPJkOucbwOF1iOgWetbTR46mwKEopk+yZ
wY0EYKN8RtTW62J9B0HrpLYMbWQusarHL5EHP6oI8W0Pcks8ZrwejCZ8iJ2w8DnN
cn/WYsLcWgkk1jjqhedxHqRb3wqWk6y26uR9uSryEWr+7PNNI8ON37xH8AxA5jtZ
VBqj2d5pLe7LVTKN+virgwIDAQABo1AwTjAdBgNVHQ4EFgQUO6TwmonpEwCE35bL
5gKNJI6BsPYwHwYDVR0jBBgwFoAUO6TwmonpEwCE35bL5gKNJI6BsPYwDAYDVR0T
BAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAQkOKmDTpsJJ/CBWriSZQm8ibwBN1
v9jNFL2qPjRM2nuoexYiJHt2eiOKzC+9H8x7yLFZV5WlZl986z4x2JC9kw6iE6Mw
bsINHxfcV0hxrdDmvPpuEvYLfW9Mcay35/NXELtsBJrrmuRTxnZJvzgFDrnHfkfL
Fkd31f1TdBR72qVbHGc9zyx7cyu6QDrLYDeHzFpo3AwMe7WWJYxtwoc0020pAw+t
LWno53nX2HoDN6r8fcw5oLJovnEyc2Y1LRRKL2zrK9zBfzZhA85+NDwQwK6EbII1
3pur3Q1+1+k+Ts6EmFCM9YXiz7XCFskCogWHOb7B+4QXlb3kCz9/C+K+Vw==
-----END CERTIFICATE-----
PUBLICKEY;


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
        $this->formatData['app_id'];
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
     * @get current return url
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->formatData['return_url'];
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
        return \Wb\Rsa::verify($this->generateSignStr(),$this->sign,new PublicKey($this->rsaPublicKey));
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
        return \Wb\Rsa::sign($this->generateSignStr(),new PrivateKey($this->rsaPrivateKey));
    }

    /**
     * @pack the request data
     * @return array
     * @throws \Exception
     */
    public function packParams()
    {
        $this->formatData['sign'] = base64_encode($this->sign());
        return array($this->formatData);
    }

    /**
     * @parse the request data
     * @param $params
     * @throws \Exception
     */
    public function parseParams($params)
    {
        if (empty($params)) {
            throw new \Exception("Empty params");
        }
    }

    /**
     * @set the rsa private key
     * @param $key
     */
    public function setRsaPrivateKey($key)
    {
        $this->rsaPrivateKey = $key;
    }

    /**
     * @set the rsa public key
     * @param $key
     */
    public function setRsaPublicKey($key)
    {
        $this->rsaPublicKey = $key;
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
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!isset($this->formatData['biz_data'][$name])) {
            throw new \Exception(sprintf("The [%s] Param Not Exists",$name));
        }
        return $this->formatData['biz_data'][$name];
    }

    /**
     * @validate the biz_data
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public function __isset($name)
    {
        if (!isset($this->formatData['biz_data'][$name])) {
            throw new \Exception(sprintf("The [%s] Param Not Exists",$name));
        }
        if (!empty($this->formatData['biz_data'][$name])) {
            return true;
        }
        return false;
    }

}