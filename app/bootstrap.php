<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 21/03/2019
 * Time: 17:35
 */
namespace app;

use app\src\App;
use app\Rooting;
use app\src\ServiceContainer\ServiceContainer;
use database\Database;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('database', new Database(
    '127.0.0.1', // Addresse de la base de données
    'twitter', // Nom de la base de donnée
    'root', // Utilisateur de la base de données
    '', // Mot de passe de la base de données
    '3306'
));


$app->setService('render', function (string $template, Array $params = [])
{
    if ($template === '404')
    {
        header("HTTP/1.0 404 Not Found");
    }

    ob_start();
    include __DIR__ . '/../view/' . $template . '.php';
    ob_end_flush();
    die();
});

$routing = new Rooting($app);
$routing->Setup();

//36MIN DE VIDEO
return $app;