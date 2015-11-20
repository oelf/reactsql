<?php
include 'class/mvc/Autoloader.php';

$autoloader = new Autoloader();
spl_autoload_register(array($autoloader, 'load'));

$route = isset($_GET["r"]) ? $_GET["r"] : "";
$router = new Router($route);
$router->route();
?>