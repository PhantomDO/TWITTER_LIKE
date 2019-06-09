<?php

namespace app;

use app\src\App;
use controller\ProfileController;
use controller\TimelineController;

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

        $this->app->Get('/timeline', function () use ($app)
        {
            $controller = new TimelineController($app);
            $controller->TimelineHandler();
        });

        $this->app->Get('/profile/(\w+)', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler($name, false, false);
        });

        $this->app->Get('/profile/(\w+)/settings', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler($name, true, false);
        });

        $this->app->Put('/profile/(\w+)/settings/save', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileSettingsHandlerUpdate($name);
        });

        $this->app->Put('/profile/(\w+)/follow', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileFollowHandlerUpdate($name);
        });

        $this->app->Delete('/profile/(\w+)/unfollow', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileFollowHandlerUpdate($name);
        });

        $this->app->Get('/profile/(\w+)/tweet', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileHandler($name, false, true);
        });

        $this->app->Put('/profile/(\w+)/tweet/post', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileTweetHandlerUpdate($name, false);
        });

        $this->app->Delete('/profile/(\w+)/tweet/delete', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileTweetHandlerUpdate($name, true);
        });

        $this->app->Put('/profile/(\w+)/tweet/update/rt', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileTweetSocialHandlerUpdate($name, true, false);
        });

        $this->app->Put('/profile/(\w+)/tweet/update/like', function ($name) use ($app)
        {
            $controller = new ProfileController($app);
            $controller->ProfileTweetSocialHandlerUpdate($name, false, true);
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