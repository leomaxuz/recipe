<?php
session_start();
require 'vendor/autoload.php';
include 'config.php';
$app = new Slim\App(["settings" => $config]);
//Обработка
$container = $app->getContainer();

$container['db'] = function ($c) {
   try{
       $db = $c['settings']['db'];
       $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       );
       $pdo = new PDO("mysql:host=" . $db['servername'] . ";dbname=" . $db['dbname'], $db['username'], $db['password'],$options);
       return $pdo;
   }
   catch(\Exception $ex){
       return $ex->getMessage();
   }
   
};

include 'api/user.php';
include 'api/recipe.php';

$app->run();
