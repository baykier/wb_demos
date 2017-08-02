<?php
namespace Wb;
use JsonRPC\Server as JsonRpcServer;
use JsonRPC\Response\ResponseBuilder;
use JsonRPC\Request\RequestParser;
use JsonRPC\Request\BatchRequestParser;
use JsonRPC\MiddlewareInterface;
use JsonRPC\MiddlewareHandler;
use JsonRPC\Exception\ServerErrorException;

class Server extends JsonRpcServer
{
    protected $request = null;

    protected $rsaPublicKey = <<<PUBLICKEY
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


    public function __construct(
        $request = '',
        array $server = array(),
        ResponseBuilder $responseBuilder = null,
        RequestParser $requestParser = null,
        BatchRequestParser $batchRequestParser = null,
        ProcedureHandler $procedureHandler = null,
        MiddlewareHandler $middlewareHandler = null
    )
    {
        parent::__construct($request, $server, $responseBuilder, $requestParser, $batchRequestParser, $procedureHandler, $middlewareHandler);

        $this->getProcedureHandler()->withObject($this);
    }

    /**
     * @parse the request data and verify the params
     * @param $method
     * @throws \Exception
     */
    protected function parseParams($params)
    {
        try  {

            $request = new Request();
            $request->setRsaPublicKey($this->rsaPublicKey);
            $request->parseParams($params);
            $this->request = $request;
        }catch (\Exception $e) {
            echo $this->handleExceptions(new ServerErrorException($e->getMessage(),$e->getCode()));
            exit(0);
        }
    }


    public function queryOrderStatus($params)
    {
        $this->parseParams($params);
        return array();
    }
}
