<?php
namespace Wb;
use JsonRPC\Server as JsonRpcServer;
use JsonRPC\Response\ResponseBuilder;
use JsonRPC\Request\RequestParser;
use JsonRPC\Request\BatchRequestParser;
use JsonRPC\MiddlewareInterface;
use JsonRPC\MiddlewareHandler;
use JsonRPC\Exception\ServerErrorException;
use JsonRPC\ProcedureHandler;

class Server extends JsonRpcServer
{
    protected $request;
    /**
     * @rsa public key
     * @var
     */
    protected $rsaPublicKey;


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

        //register current object
        $this->getProcedureHandler()->withObject($this);
    }

    /**
     * @Set the server rsa public key
     * @param $key
     */
    public function setRsaPublicKey($key)
    {
        $this->rsaPublicKey = $key;
    }

    /**
     * @parse the request data and verify the params
     * @param $method
     * @throws \Exception
     */
    protected function parseParams($params)
    {
        if (empty($this->rsaPublicKey)) {
            throw new ServerErrorException('The Rsa Public Key Can not be Empty');
        }
        try  {

            $request = new Request();
            $request->setRsaPublicKey($this->rsaPublicKey);
            $request->parseParams($params);
            $this->request = $request;
        }catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage(),$e->getCode());
        }
    }

    /**
     * @JsonRpcMethod query order status
     * @param $params
     * @return array
     * @throws ServerErrorException
     */
    public function queryOrderStatus($params)
    {
        $this->parseParams($params);
        return array();
    }
}
