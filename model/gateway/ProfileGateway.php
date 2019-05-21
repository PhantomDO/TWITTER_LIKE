<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 15/05/2019
 * Time: 15:05
 */

namespace model\gateway;

use app\src\App;
use controller\ProfileController;
use database\Database;

class ProfileGateway
{
    /**
     * @var \PDO
     */
    private $conn;
    private $id;
    private $login;
    private $password;
    private $adress;

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
    public function GetLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function SetLogin($login): void
    {
        $this->login = $login;
    }

    public function GetPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function SetPassword($password): void
    {
        $this->password = $password;
    }

    public function GetAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adr
     */
    public function SetAdress($adr): void
    {
        $this->adress = $adr;
    }

    public function Insert() : void
    {
        $query = $this->conn->prepare('
        INSERT INTO users (login, password, adress, role_id) VALUES (:login, :password, :adress, :role_id)
        ');
        $executed = $query->execute([
            ':login' => $this->login,
            ':password' => $this->password,
            ':adress' => $this->adress,
            ':role_id' => 2
        ]);

        if (!$executed) throw new \Error('Insert failed');

        $this->id = $this->conn->lastInsertId();
    }

    public function Login()
    {
        $query = $this->conn->prepare('
        SELECT users.login, users.password, users.adress, roles.name, roles.slug, roles.level 
        FROM users LEFT JOIN roles ON users.role_id = roles.id
        WHERE users.login=:login AND users.password=:password');
        $executed = $query->execute([
            ':login' => $this->login,
            ':password' => $this->password
        ]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($elements) === 0) return null;
        else if (count($elements) > 0)
        {
            $_SESSION['ProfileGateway'] = $elements[0];
            return true;
        }

        if (!$executed) throw new \Error('Login failed');

        return false;
    }

    public function Update() : void
    {
        if (!$this->id) throw new \Error('Instance does not exist in base');

        $query = $this->conn->prepare('UPDATE users SET login = :login, password = :password, adress = :adress  WHERE id = :id');
        $executed = $query->execute([
            ':login' => $this->login,
            ':password' => $this->password,
            ':adress' => $this->adress,
            ':id' => $this->id
        ]);

        if (!$executed) throw new \Error('Update failed');
    }

    public function Delete() : void
    {
        if (!$this->id) throw new \Error('Intance does not exist in base');

        $query = $this->conn->prepare('DELETE FROM users WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id
        ]);

        if (!$executed) throw new \Error('Delete failed');
    }

    public function Hydrate(Array $element)
    {
        $this->id = $element['id'] ?? null;
        $this->login = $element['login'] ?? null;
        $this->password = $element['password'] ?? null;
        $this->adress = $element['adress'] ?? null;
    }
}