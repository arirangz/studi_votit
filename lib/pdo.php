<?php

try {
    $pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8', DB_USER, DB_PASSWORD);
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}