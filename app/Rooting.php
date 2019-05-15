<?php

namespace app;

use app\src\App;

class Rooting
{
    private $app;

    /**
     * Rooting constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function Setup()
    {
        $app = $this->app;
    }
}