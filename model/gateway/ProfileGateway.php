<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/05/2019
 * Time: 15:05
 */

namespace model\gateway;

use app\src\App;
use database\Database;

class ProfileGateway
{
    /**
     * @var \PDO
     */
    private $conn;
    private $id;
    private $name;

    public function __construct(App $app)
    {
        $this->conn = $app->getService('database')->getConnection();
    }
    /**
     * @return mixed
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function SetName($name): void
    {
        $this->name = $name;
    }

    public function Insert() : void
    {
        $query = $this->conn->prepare('INSERT INTO twitter (name) VALUES (:name)');
        $executed = $query->execute([
            ':name' => $this->name,
        ]);

        if (!$executed) throw new \Error('Insert failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function Update() : void
    {
        if (!$this->id) throw new \Error('Intance does not exist in base');

        $query = $this->conn->prepare('UPDATE twitter SET name = :name WHERE id = :id');
        $executed = $query->execute([
            ':name' => $this->name,
            ':id' => $this->id
        ]);

        if (!$executed) throw new \Error('Update failed');
    }

    public function Delete() : void
    {
        if (!$this->id) throw new \Error('Intance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM twitter WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if (!$executed) throw new \Error('Delete failed');
    }

    public function Hydrate(Array $element)
    {
        $this->id = $element['id'] ?? null;
        $this->name = $element['name'] ?? null;
    }
}