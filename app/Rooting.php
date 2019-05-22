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
            $controller->Home();
        });

        $this->app->Get('/profile/(\w+)', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler($name);
        });

        $this->app->Get('/profile/(\w+)/settings', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileSettingsHandler($name);
        });

        $this->app->Put('/profile/(\w+)/settings/save', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileSettingsHandlerUpdate($name);
        });

        $this->app->Get('/login', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->Login();
        });

        $this->app->Post('/handleLogin', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->CheckExistingLogin();
        });

        $this->app->Get('/register', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->Register();
        });

        $this->app->Post('/handleRegister', function () use ($app)
        {
            $controller = new ProfileController($app);
            $controller->RegisterMember();
        });
    }
}