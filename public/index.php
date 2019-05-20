<?php
/**
 * Created by PhpStorm.
 * User: donno
 * Date: 21/03/2019
 * Time: 17:37
 */
namespace web;
use app\src\Autoloader;

session_start();

require_once  __DIR__ . '/../app/src/Autoloader.php';
Autoloader::Register();

$app = require_once __DIR__ . '/../app/bootstrap.php';
$app->Run();