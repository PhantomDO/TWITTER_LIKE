<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 03/04/2019
 * Time: 09:00
 */


namespace app\src;

class Autoloader
{
    /**
     * Met en lace es differets autoloader de l'App php
     */
    public static function Register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function Autoload(string $class)
    {
        $namespace = explode('\\', $class);
        $class = implode('/', $namespace);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/' . $class . '.php';
    }
}