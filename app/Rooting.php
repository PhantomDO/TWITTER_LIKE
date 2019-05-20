<?php

namespace app;

use app\src\App;
use controller\ProfileController;

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

        $this->app->Get('/', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler(1);
        });

        $this->app->Get('/profile/(\d+)', function ($id) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler($id);
        });

        $this->app->Get('/login', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->Create();
        });

        $this->app->Post('/handleLogin', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->CreateMember();
        });
    }
}