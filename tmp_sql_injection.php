<?php

require_once 'lib/config.php';
require_once 'lib/pdo.php';

/* A NE PAS FAIRE */


$id = $_GET['id'];



$query = $pdo->query("SELECT * FROM user2 WHERE id = $id");
$result = $query->fetch(PDO::FETCH_ASSOC);

/* 
// Version avec requête préparée sécurisée contre les injections sql

$id = (int)$_GET['id'];
$query = $pdo->prepare("SELECT * FROM user2 WHERE id = :id");
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
//fetch() nous permet de récupérer une seule ligne
$result = $query->fetch(PDO::FETCH_ASSOC);

var_dump($result);

*/