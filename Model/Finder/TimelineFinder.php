<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/05/2019
 * Time: 15:04
 */
namespace model\finder;

use app\src\App;
use database\Database;
use model\gateway\TweetGateway;

//use Model\Finder\FinderInterface;

class TimelineFinder
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->conn = $this->app->getService('Database')->getConnection();
    }

    public function TimelineTweetUserId($id)
    {
        $query = $this->conn->prepare('
        SELECT t.tweet_user_id, t.tweet_id, t.tweet_text, t.tweet_date, t.tweet_like_count, t.tweet_rt_count
        FROM tweet t WHERE t.tweet_user_id = :user_id ORDER BY t.tweet_date DESC'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':user_id' => $id->GetId()]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($element) === 0) return null;

        $tweets = [];
        $tweet = null;
        foreach ($element as $e)
        {
            //var_dump($e);
            $tweet = new TweetGateway($this->app);
            $tweet->Hydrate($e);

            $tweets[] = $tweet;
        }

        return $tweets;
    }

    public function TimelineTweetFollowers($id)
    {
        var_dump($id->GetId());
        $query = $this->conn->prepare('
        SELECT t.tweet_user_id, t.tweet_id, t.tweet_text, t.tweet_date, t.tweet_like_count, t.tweet_rt_count
        FROM tweet t WHERE t.tweet_user_id = :user_id ORDER BY t.tweet_date DESC'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':user_id' => $id->GetId()]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($element) === 0) return null;

        $tweets = [];
        $tweet = null;
        foreach ($element as $e)
        {
            $tweet = new TweetGateway($this->app);
            $tweet->Hydrate($e);

            $tweets[] = $tweet;
        }

        return $tweets;
    }
}