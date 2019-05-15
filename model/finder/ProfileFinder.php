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

    public function FindOneById($id)
    {
        $query = $this->conn->prepare('SELECT t.name, t.id FROM twitter t WHERE t.id like :id ORDER BY t.name'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => '%' . $id .  '%']); // Exécution de la requête
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === 0) return null;

        $city = new ProfileGateway($this->app);
        $city->Hydrate($element);

        return $city;
    }
}