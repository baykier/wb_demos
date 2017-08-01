<?php
namespace Wb;

use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\PrivateKey;
use ParagonIE\EasyRSA\PublicKey;
use phpseclib\Crypt\RSA as BaseRsa;

class Rsa extends EasyRSA
{
    /**
     * @Use pkcs1 signature
     * @param string $message
     * @param PrivateKey $rsaPrivateKey
     * @return string
     */
    public static function sign($message, PrivateKey $rsaPrivateKey)
    {
        static $rsa = null;
        if (!$rsa) {
            $rsa = new BaseRsa();
            $rsa->setSignatureMode(BaseRsa::SIGNATURE_PKCS1);
            $rsa->setMGFHash('sha256');
        }

        $rsa->loadKey($rsaPrivateKey->getKey());
        return $rsa->sign($message);
    }

    /**
     * @Use pkcs1 signature
     * @param string $message
     * @param string $signature
     * @param PublicKey $rsaPublicKey
     * @return bool
     */
    public static function verify($message, $signature, PublicKey $rsaPublicKey)
    {
        static $rsa = null;
        if (!$rsa) {
            $rsa = new BaseRsa();
            $rsa->setSignatureMode(BaseRsa::SIGNATURE_PKCS1);
            $rsa->setMGFHash('sha256');
        }

        $rsa->loadKey($rsaPublicKey->getKey());
        return $rsa->verify($message, $signature);
    }
}