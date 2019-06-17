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
        $this->conn = $app->getService('Database')->getConnection();
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
        VALUES (:tweet_date, :tweet_text, :tweet_user_id)
        ');
        $executed = $query->execute([
            ':tweet_user_id' => $this->user_id,
            ':tweet_date' => $this->tweet_date,
            ':tweet_text' => $this->tweet_text
        ]);

        //var_dump($executed);

        if (!$executed) throw new \Error('Insert failed');

        $this->tweet_id = $this->conn->lastInsertId();
    }

    public function DeleteTweet($id) : void
    {
        var_dump($id);
        if (!$this->tweet_id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('
        DELETE FROM tweet WHERE tweet_user_id = :tweet_user_id AND tweet_id = :tweet_id
        ');
        $executed = $query->execute([
            ':tweet_user_id' => $this->user_id,
            ':tweet_id' => $id
        ]);

        //var_dump($executed);
        if (!$executed) throw new \Error('Delete failed');
    }

    public function UpdateTweet($id, $userid) : void
    {
        if (!$this->tweet_id || !$userid) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('
        UPDATE tweet SET tweet_like_count = :tweet_like, tweet_rt_count = :tweet_rt
        WHERE tweet_user_id = :tweet_user_id AND tweet_id = :tweet_id
        ');
        $executed = $query->execute([
            ':tweet_user_id' => $this->user_id,
            ':tweet_id' => $id,

            ':tweet_like' => $this->NumberOfLike($id),
            ':tweet_rt' => $this->NumberOfRt($id)
        ]);

        //var_dump($executed);

        if (!$executed) throw new \Error('Update failed');
    }

    public function NumberOfRt($id)
    {
        $query = $this->conn->prepare('
        SELECT COUNT(rt.tweet_id) FROM rt
        WHERE rt.tweet_id like :tweet_id
        ');
        $executed = $query->execute([
            ':tweet_id' => $id
        ]);

        $element = $query->fetch(\PDO::FETCH_ASSOC);

        //var_dump($element);

        if ($element === 0 || $element === null) return 0;
        else return $element['COUNT(rt.tweet_id)'];
    }

    public function InsertRt($id) : void
    {
        $query = $this->conn->prepare('
        INSERT INTO rt (user_id, tweet_id) 
        VALUES (:user_id, :tweet_id)
        ');
        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);
        //var_dump($executed);
        if (!$executed) throw new \Error('Insert failed');

        //$this->id = $this->conn->lastInsertId();
    }

    public function DeleteRt($id) : void
    {
        if (!$this->user_id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('
        DELETE FROM rt WHERE user_id = :user_id AND tweet_id = :tweet_id
        ');
        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);

        //var_dump($executed);
        if (!$executed) throw new \Error('Delete failed');
    }

    public function IsRt($id)
    {
        //if (!$this->user_id) throw new \Error('Instance does not exist in base');
        //var_dump($this->user_id);
        $query = $this->conn->prepare('
        SELECT rt.user_id, rt.tweet_id FROM rt
        WHERE rt.tweet_id like :tweet_id
        AND rt.user_id like :user_id
        ');

        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);

        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$element) false;
        else return true;
    }

    public function NumberOfLike($id)
    {
        $query = $this->conn->prepare('
        SELECT COUNT(likes.tweet_id) FROM likes
        WHERE likes.tweet_id like :tweet_id
        ');
        $executed = $query->execute([
            ':tweet_id' => $id
        ]);

        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === 0 || $element === null) return 0;
        else return $element['COUNT(likes.tweet_id)'];
    }

    public function InsertLike($id) : void
    {
        $query = $this->conn->prepare('
        INSERT INTO likes (user_id, tweet_id) 
        VALUES (:user_id, :tweet_id)
        ');
        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);
        //var_dump($executed);
        if (!$executed) throw new \Error('Insert failed');

        //$this->id = $this->conn->lastInsertId();
    }

    public function DeleteLike($id) : void
    {
        if (!$this->user_id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('
        DELETE FROM likes WHERE user_id = :user_id AND tweet_id = :tweet_id
        ');
        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);

        //var_dump($executed);
        if (!$executed) throw new \Error('Delete failed');
    }

    public function IsLiked($id)
    {
        //if (!$this->user_id) throw new \Error('Instance does not exist in base');
        //var_dump($this->user_id);
        $query = $this->conn->prepare('
        SELECT likes.user_id, likes.tweet_id FROM likes
        WHERE likes.tweet_id like :tweet_id
        AND likes.user_id like :user_id
        ');

        $executed = $query->execute([
            ':user_id' => $_SESSION['ProfileGateway']['id'],
            ':tweet_id' => $id
        ]);

        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$element) false;
        else return true;
    }

    public function Hydrate(Array $element)
    {
        $this->tweet_id = $element['tweet_id'] ?? null;
        $this->tweet_date = $element['tweet_date'] ?? null;
        $this->tweet_text = $element['tweet_text'] ?? null;
        $this->tweet_like = $element['tweet_like'] ?? $element['tweet_like_count'] ?? null;
        $this->tweet_rt = $element['tweet_rt'] ?? $element['tweet_rt_count'] ?? null;
        $this->user_id = $element['tweet_user_id'] ?? null;
    }
}