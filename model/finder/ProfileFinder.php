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
use model\gateway\ProfileGateway;

//use model\finder\FinderInterface;

class ProfileFinder
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
        $this->conn = $this->app->getService('database')->getConnection();
    }

    public function FindOneByName($name)
    {
        $query = $this->conn->prepare('
        SELECT t.login, t.password, t.adress, t.id
        FROM users t WHERE t.login like :login ORDER BY t.login'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':login' => '%' . $name .  '%']); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === 0) return null;

        $profile = new ProfileGateway($this->app);
        $profile->Hydrate($element);

        //var_dump($profile);
        return $profile;
    }

    public function FindOneById($id)
    {
        $query = $this->conn->prepare('
        SELECT t.login, t.password, t.adress, t.id
        FROM users t WHERE t.id like :id ORDER BY t.login'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === 0) return null;

        $profile = new ProfileGateway($this->app);
        $profile->Hydrate($element);

        //var_dump($profile);
        return $profile;
    }

    public function AllUserFollowed($id)
    {
        $query = $this->conn->prepare('
        SELECT followers.follower_id FROM followers
        WHERE followers.user_id = :user_id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':user_id' => $id ]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);

        //var_dump($element);
        if (count($element) === 0) return null;

        $followers = [];
        $follower = null;

        $followers[] = $this->FindOneById($_SESSION['ProfileGateway']['id']);
        foreach ($element as $e)
        {
            $follower = $this->FindOneById($e['follower_id']);
            $followers[] = $follower;
        }

        //var_dump($followers);
        return $followers;
    }
}