<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 21/03/2019
 * Time: 17:37
 */
namespace Web;
use App\Src\Autoloader;

session_start();

require_once __DIR__ . '/../App/Src/Autoloader.php';
Autoloader::Register();

$app = require_once __DIR__ . '/../App/Bootstrap.php';
$app->Run();