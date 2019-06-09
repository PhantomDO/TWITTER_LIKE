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

class TimelineController extends ControllerBase
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function TimelineHandler()
    {
        $services = $this->app;
        $followers = $services->getService('profileFinder')->AllUserFollowed($_SESSION['ProfileGateway']['id']);

        $timelines = [];
        $timeline = null;
        foreach ($followers as $f)
        {
            $timeline = $services->getService('timelineFinder')->TimelineTweetUserId($f);

            $timelines[] = $timeline;
        }
        //var_dump($followers);
        $render = $this->app->getService('render');
        $render('timeline',
        [
                'timeline' => $timelines,
                'services' => $services
        ]);
    }
}