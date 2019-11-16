<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, x-requested-with");

/*
$host = "mealcheccjprod.mysql.db";
$user = "mealcheccjprod";
$password = "Laisset85";
$dbname = "mealcheccjprod";*/

$host = "mealcheck-instance.cdsban5bzpws.eu-west-3.rds.amazonaws.com";
$user = "admin_mealcheck";
$password = "gz77Z2pqHfUEcZ577J6y";
$dbname = "mealcheck_db";

/*
$host = "localhost";
$user = "root";
$password = "root";
$dbname = "newmealcheck";*/

$pdo = null;
try{
$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname,
        $user,
        $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
  echo "Erreur!: " . $e->getMessage() . "<br/>";
  die();
}

