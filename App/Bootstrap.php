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
use model\finder\ProfileFinder;
use model\finder\TimelineFinder;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('Database', new Database(
/*    'bmwl6cwh5kmqtvmnydhg-mysql.services.clever-cloud.com', // Addresse de la base de données
    'bmwl6cwh5kmqtvmnydhg', // Nom de la base de donnée
    'urn18higmxg4dzik', // Utilisateur de la base de données
    'ZQjPvkj23t5VjBVJbVmo', // Mot de passe de la base de données
    '3306'*/
    getenv('MYSQL_ADDON_HOST'),
    getenv('MYSQL_ADDON_DB'),
    getenv('MYSQL_ADDON_USER'),
    getenv('MYSQL_ADDON_PASSWORD'),
    getenv('MYSQL_ADDON_PORT')
));


$app->setService('render', function (string $template, Array $params = [])
{
    if ($template === '404')
    {
        header("HTTP/1.0 404 Not Found");
    }

    ob_start();
    include __DIR__ . '/../View/' . $template . '.php';
    ob_end_flush();
    die();
});

$app->setService('profileFinder', new ProfileFinder($app));
$app->setService('timelineFinder', new TimelineFinder($app));

$routing = new Rooting($app);
$routing->Setup();

//36MIN DE VIDEO
return $app;