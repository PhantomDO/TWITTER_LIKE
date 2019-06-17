<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/03/2019
 * Time: 11:19
 */

namespace controller;

use app\src\App;

abstract class ControllerBase
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}