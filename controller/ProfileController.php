<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/05/2019
 * Time: 15:01
 */

namespace controller;
use controller\ControllerBase;

use app\src\App;
use model\gateway\ProfileGateway;

class ProfileController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function ProfileHandler($id)
    {
        if ($id === null) {
            $this->Render('404');
            return;
        }

        $profile = $this->app->getService('profileFinder')->FindOneById($id);
        $render = $this->app->getService('render');
        $render('profile', ['profile' => $profile]);
    }
}