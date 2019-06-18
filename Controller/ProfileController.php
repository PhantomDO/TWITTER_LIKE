<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/05/2019
 * Time: 15:01
 */

namespace Controller;
use Controller\ControllerBase;

use App\Src\App;
use Model\Gateway\ProfileGateway;
use Model\Gateway\TweetGateway;

class ProfileController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function Home()
    {
        $render = $this->app->getService('render');
        $render('Home');
    }

    public function ProfileHandler($name, $settings, $tweet)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        $profile = $this->app->getService('profileFinder')->FindOneByName($name);

        $follow = $profile->UserIsFollowing($profile->GetId());

        $timeline = $this->app->getService('timelineFinder')->TimelineTweetUserId($profile);

        $delete = false;

        $render = $this->app->getService('render');
        $render('Profile',
        [
                'profile' => $profile,
                'settings' => $settings,
                'tweet' => $tweet,
                'follow' => $follow,
                'timeline' => $timeline,
                'delete' => $delete
        ]);
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
            $render('Profile',['error' => $e, 'profile' => $profile, 'settings' => $settings]); // On renvoie la city acutelle au template
        }

        print_r("Modification réussite.");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/profile/" . $profile->GetLogin());
    }

    public function SearchHandler()
    {
        $render = $this->app->getService('render');
        $render('Search');
    }

    public function ProfileSearchUser()
    {
        $result = null;
        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'login' => $_POST['login'] ?? null
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            $result = $this->app->getService('profileFinder')->UserSearch($profile->GetLogin());
            var_dump($result);
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('Search',['error' => $e, 'profile' => $profile, 'search' => $result]); // On renvoie la profile acutelle au template
            print_r("Personne ne resemble");
        }
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
                'user_id' => $_POST['user_id'],
                'id' => $_POST['id']
            ];

            $profile = new ProfileGateway($this->app);
            $profile->Hydrate($element);
            //var_dump($profile);
            $follow = $profile->UserIsFollowing($profile->GetId());

            if (!$follow)
                $result = $profile->InsertFollower($profile->GetId());
            else
                $result = $profile->DeleteFollower($profile->GetId());

        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $render('Profile',['error' => $e, 'profile' => $profile, 'follow' => $follow]); // On renvoie la city acutelle au template
        }

        if (!$follow)
            print_r("Vous suivez : ". $profile->GetLogin());
        else
            print_r("Vous ne suivez plus : ". $profile->GetLogin());

        //Set Refresh header using PHP.
        header( "refresh:1;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/profile/" . ($profile->GetLogin()));
    }

    public function ProfileTweetHandlerUpdate($name, $delete)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'tweet_user_id' => $_SESSION['ProfileGateway']['id'],
                'tweet_date' => $_POST['tweet_date'] ?? null,
                'tweet_id' => $_POST['tweet_id'] ?? null,
                'tweet_text' => $_POST['tweet_text'] ?? null,
            ];
            //var_dump($element);
            $profile = new TweetGateway($this->app);
            $profile->Hydrate($element);

            if (!$delete)
                $result = $profile->InsertTweet();
            else
                $result = $profile->DeleteTweet($profile->GetTweetId());
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $tweet = false;
            $render('Profile',['error' => $e, 'profile' => $profile, 'tweet' => $tweet, 'delete' => $delete]); // On renvoie la city acutelle au template
        }

        if (!$delete)
            print_r("Insert");
        else
            print_r("Delete");
        //Set Refresh header using PHP.
        header( "refresh:0;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/profile/" . $_SESSION['ProfileGateway']['login']);
    }

    public function ProfileTweetSocialHandlerUpdate($name, $rt, $like)
    {
        if ($name === null) {
            $this->Render('404');
            return;
        }

        try { // on utilise un try catch pour renvoyer vers une erreur si la requête n'a pas fonctionné
            $element = [
                'tweet_user_id' => $_POST['tweet_user_id'] ?? $_SESSION['ProfileGateway']['id'],
                'tweet_id' => $_POST['tweet_id'] ?? null,
                'tweet_like' => $_POST['tweet_like'] ?? null,
                'tweet_rt' => $_POST['tweet_rt'] ?? null
            ];

            $profile = new TweetGateway($this->app);
            $profile->Hydrate($element);

            if ($rt)
            {
                $isRt = $profile->IsRt($profile->GetTweetId());
                if (!$isRt)
                    $profile->InsertRt($profile->GetTweetId());
                else
                    $profile->DeleteRt($profile->GetTweetId());
            }

            if ($like)
            {
                $isLiked = $profile->IsLiked($profile->GetTweetId());
                if (!$isLiked)
                    $profile->InsertLike($profile->GetTweetId());
                else
                    $profile->DeleteLike($profile->GetTweetId());
            }

            $result =$profile->UpdateTweet($profile->GetTweetId(), $profile->GetUserId());
        } catch (\Exception $e) {
            $render = $this->app->getService('render');
            $tweet = false;
            $render('Profile',['error' => $e, 'profile' => $profile, 'tweet' => $tweet, 'rt' => $rt, 'like' => $rt]); // On renvoie la city acutelle au template
        }

        if ($rt)
        {
            if (!$isRt)
                print_r("Rt");
            else
                print_r('Undo rt');
        }

        if ($like)
        {
            if (!$isLiked)
                print_r("Like");
            else
                print_r('Undo Like');
        }
        //Set Refresh header using PHP.
        header( "refresh:0;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/timeline");
    }

    public function Login()
    {
        $render = $this->app->getService('render');
        $render('Login');
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
            $render('Login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
            print_r("Impossible de se connecter.");
        } catch (\Error $e) {
            $render = $this->app->getService('render');
            $render('Login',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
            print_r("Impossible de se connecter.");
        }

        print_r("Conexion etabli");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/timeline");
    }

    public function Register()
    {
        $render = $this->app->getService('render');
        $render('Register');
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
            $render('Register',['error' => $e, 'profile' => $profile]); // On renvoie la profile acutelle au template
        }

        print_r("New profile has been sucessfully created");

        //Set Refresh header using PHP.
        header( "refresh:2;url=http://app-28a26904-3909-4a49-9120-c242a67c0200.cleverapps.io/login" );
    }
}