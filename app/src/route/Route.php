<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 21/03/2019
 * Time: 16:30
 */

namespace app\src\route;

class Route
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $arguments;

    /**
     * Root constructor.
     * @param string $method
     * @param string $pattern
     * @param callable $callable
     */
    public function __construct(string $method, string $pattern, callable $callable)
    {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->arguments = array();
    }

    public function Match($method, $uri)
    {
        if ($this->method != $method)
        {
            return false;
        }

        if (preg_match($this->CompilePattern(), $uri, $this->arguments))
        {
            array_shift($this->arguments);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function GetMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function GetPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return callable
     */
    public function GetCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @return array
     */
    public function GetArguments(): array
    {
        return $this->arguments;
    }

    private function CompilePattern()
    {
        return sprintf('#^%s$#', $this->pattern);
    }
}