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

    public function Create()
    {
        $render = $this->app->getService('render');
        $render('login');
    }

    public function CheckExistingLogin()
    {
        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'],
                'password' => $_POST['password']
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            $result = $profile->Login();

            if ($result === null)
                $this->CreateMember();

        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
        }

        print_r("Conexion etabli");
    }

    public function CreateMember()
    {
        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'],
                'password' => $_POST['password']
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            $result = $profile->Insert();
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
        }

        print_r("New profile has been sucessfully created");
    }
}