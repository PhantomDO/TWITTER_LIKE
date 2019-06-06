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
use model\gateway\TweetGateway;

class ProfileController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function Home()
    {
        $render = $this->app->getService('render');
        $render('home');
    }

    public function ProfileHandler($name, $settings)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        $profile = $this->app->getService('profileFinder')->FindOneByName($name);

        $follow = $profile->UserIsFollowing($profile->GetId());

        $render = $this->app->getService('render');
        $render('profile', ['profile' => $profile, 'settings' => $settings, 'follow' => $follow]);
    }

    public function ProfileSettingsHandlerUpdate($name)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'],
                'password' => $_POST['password'],
                'adress' => $_POST['adress'],
                'id' => $_POST['id']
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            $result = $profile->Update();
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $settings = false;
            $render('profile',['error' => $e, 'profile' => $profile, 'settings' => $settings]); // On renvoie la city acutelle au template
        }

        print_r("Modification réussite.");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://localhost/twitter/profile/" . $profile->GetLogin());
    }

    public function ProfileFollowHandlerUpdate($name)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'],
                'user_id' => $_SESSION['ProfileGateway']['id'],
                'id' => $_POST['id']
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            var_dump($profile);
            $follow = $profile->UserIsFollowing($profile->GetId());

            if (!$follow)
                $result = $profile->InsertFollower($profile->GetId());
            else
                $result = $profile->DeleteFollower($profile->GetId());

        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('profile',['error' => $e, 'profile' => $profile, 'follow' => $follow]); // On renvoie la city acutelle au template
        }

        if (!$follow)
            print_r("Vous suivez : ". $profile->GetLogin());
        else
            print_r("Vous ne suivez plus : ". $profile->GetLogin());

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://localhost/twitter/profile/" . $profile->GetLogin());
    }

    public function ProfileTweetHandlerUpdate($name, $delete)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'],
                'user_id' => $_SESSION['ProfileGateway']['id'],
                'id' => $_POST['id']
            ];

            $profile = new TweetGateway($this->app);
            $profile->Hydrate($element);

            if (!$delete)
                $result = $profile->InsertTweet();
            else
                $result = $profile->DeleteTweet($profile->GetTweetId());
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('profile',['error' => $e, 'profile' => $profile]); // On renvoie la city acutelle au template
        }

        if (!$delete)
            print_r("insertion");
        else
            print_r("Delete");
        //Set Refresh header using PHP.
        header( "refresh:2;url=http://localhost/twitter/profile/" . $profile->GetLogin());
    }

    public function Login()
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
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
            print_r("Impossible de se connecter.");
        } catch (\Error $e) {
            $render = $this->app->getService('render');
            $render('login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
            print_r("Impossible de se connecter.");
        }

        print_r("Conexion etabli");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://localhost/twitter/profile/" . $profile->GetLogin());
    }

    public function Register()
    {
        $render = $this->app->getService('render');
        $render('register');
    }

    public function RegisterMember()
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
            $render('register',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
        }

        print_r("New profile has been sucessfully created");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://localhost/twitter/login" );
    }
}