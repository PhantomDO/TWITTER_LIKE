<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 03/04/2019
 * Time: 09:34
 */

namespace app\src\ServiceContainer;

class ServiceContainer
{
    /**
     * COntains all the services of the php app
     * @var array
     */
    private $container = array();

    /**
     * @param string $serviceName name of the service to create in the container
     * @return mixed
     */
    public function Get(string $serviceName)
    {
        return $this->container[$serviceName];
    }

    /**
     * @param string $name
     * @param mixed $assigned value associated to the service (can be any type)
     */
    public function Set(string $name, $assigned)
    {
        $this->container[$name] = $assigned;
    }

    /**
     * @param string $name
     */
    public function Unset(string $name)
    {
        unset($this->container[$name]);
    }
}