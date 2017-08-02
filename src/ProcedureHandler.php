<?php
namespace Wb;

use JsonRPC\ProcedureHandler as BaseProcedureHandler;

class ProcedureHandler extends BaseProcedureHandler
{
    /**
     * Execute a method
     *
     * @access public
     * @param  mixed     $class        Class name or instance
     * @param  string    $method       Method name
     * @param  array     $params       Procedure params
     * @return mixed
     */
    public function executeMethod($class, $method, $params)
    {
        $instance = is_string($class) ? new $class : $class;
        $reflection = new \ReflectionMethod($class, $method);



        $arguments = $this->getArguments(
            $params,
            $reflection->getParameters(),
            $reflection->getNumberOfRequiredParameters(),
            $reflection->getNumberOfParameters()
        );
        $this->executeBeforeMethod($instance, $method,$arguments);

        return $reflection->invokeArgs($instance, $arguments);
    }
}