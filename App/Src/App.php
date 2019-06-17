<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 21/03/2019
 * Time: 16:45
 */

namespace App\Src;

use app\src\route\Route;
use app\src\ServiceContainer\ServiceContainer;

class App
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var statusCode
     */
    private $statusCode;

    /**
     * @var $serviceContainer
     */
    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param string $serviceName name of the service to retrieve
     * @return mixed
     */
    public function getService(string $serviceName)
    {
        return $this->serviceContainer->Get($serviceName);
    }

    /**
     * @param string $serviceName name of service to set
     * @param $assigned value of this service
     * @return mixed
     */
    public function setService(string $serviceName, $assigned)
    {
        return $this->serviceContainer->Set($serviceName, $assigned);
    }

    /**
     * Create a Route for HTTP verb GET
     * @param string $pattern
     * @param callable $callable
     * @return App $this
     */
    public function Get(string $pattern, callable $callable)
    {
        $this->RegisterRoute(self::GET, $pattern, $callable);

        return $this;
    }

    public function Post(string $pattern, callable $callable)
    {
        $this->RegisterRoute(self::POST, $pattern, $callable);

        return $this;
    }

    public function Put(string $pattern, callable $callable)
    {
        $this->RegisterRoute(self::PUT, $pattern, $callable);

        return $this;
    }

    public function Delete(string $pattern, callable $callable)
    {
        $this->RegisterRoute(self::DELETE, $pattern, $callable);

        return $this;
    }


    /**
     * Check which Route to use inside the router
     * @throws HttpException
     */
    public function Run()
    {
        /**
         * TODO: Créer un hack pour accéder au method PUT et DELETE
         * TODO: override de la var $method en lui donnant la nouvelle $method dans le champ caché du formulaire
         */
        $method = $_SERVER['REQUEST_METHOD'] ?? self::GET;
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        if(isset($_POST['_method']))
        {
            if(strtoupper($_POST['_method']) === self::PUT)
            {
                $method = self::PUT;
            }
            else if (strtoupper($_POST['_method']) === self::DELETE)
            {
                $method = self::DELETE;
            }
        }

        foreach ($this->routes as $route)
        {
            if ($route->match($method, $uri))
            {
                return $this->Process($route);
            }
        }

        throw new \Error('No Route available for this uri');
    }

    /**
     * @param Route $route
     * @throws HttpException
     */
    private function Process(Route $route)
    {
        try
        {
            http_response_code($this->statusCode);
            echo call_user_func_array($route->GetCallable(), $route->GetArguments());
        }
        catch (HttpException $e)
        {
            throw $e;
        }
        catch (\Exception $e)
        {
            throw new Error('Error during the process request');
        }
    }

    /**
     * Register a Route in the routes array
     * @param string $method
     * @param string $pattern
     * @param callable $callable
     */
    private function RegisterRoute(string $method , string $pattern, callable $callable)
    {
        $this->routes[] = new Route($method, $pattern, $callable);
    }
}