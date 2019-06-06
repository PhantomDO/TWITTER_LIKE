<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 06/06/2019
 * Time: 12:01
 */

namespace model\gateway;

use app\src\App;
use controller\ProfileController;
use database\Database;

class TweetGateway
{
    private $conn;

    private $user_id;

    private $tweet_id;
    private $tweet_text;
    private $tweet_date;
    private $tweet_like;
    private $tweet_rt;

    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }

    public function GetUserId()
    {
        return $this->user_id;
    }

    public function GetTweetId()
    {
        return $this->tweet_id;
    }

    public function GetTweetDate()
    {
        return $this->tweet_date;
    }

    public function SetTweetDate($date) : void
    {
        $this->tweet_date = $date;
    }

    public function GetTweetText()
    {
        return $this->tweet_text;
    }

    public function SetTweetText($text) : void
    {
        $this->tweet_text = $text;
    }

    public function GetTweetLike()
    {
        return $this->tweet_like;
    }

    public function SetTweetLike($like) : void
    {
        $this->tweet_like += $like;
    }

    public function GetTweetRt()
    {
        return $this->tweet_rt;
    }

    public function SetTweetRt($rt) : void
    {
        $this->tweet_rt += $rt;
    }

    public function InsertTweet() : void
    {
        $query = $this->conn->prepare('
        INSERT INTO tweet (tweet_date, tweet_text, tweet_user_id) 
        VALUES (:tweet_date, :tweet_text, :user_id)
        ');
        $executed = $query->execute([
            ':user_id' => $this->user_id,
            ':tweet_date' => $this->tweet_date,
            ':tweet_text' => $this->tweet_text
        ]);

        if (!$executed) throw new \Error('Insert failed');

        $this->tweet_id = $this->conn->lastInsertId();
    }

    public function DeleteTweet() : void
    {
        if (!$this->tweet_id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('
        DELETE FROM tweet WHERE user_id = :user_id AND tweet_id = :tweet_id
        ');
        $executed = $query->execute([
            ':user_id' => $this->user_id,
            ':tweet_id' => $this->tweet_id
        ]);

        //var_dump($executed);
        if (!$executed) throw new \Error('Delete failed');
    }

    public function Hydrate(Array $element)
    {
        $this->tweet_id = $element['tweet_id'] ?? null;
        $this->tweet_date = $element['tweet_date'] ?? null;
        $this->tweet_text = $element['tweet_text'] ?? null;
        $this->tweet_like = $element['tweet_like'] ?? null;
        $this->tweet_rt = $element['tweet_rt'] ?? null;
        $this->user_id = $_SESSION['ProfileGateway']['id'] ?? null;
    }
}