<?php

function getCategories(PDO $pdo):array
{
    $query = $pdo->prepare("SELECT * FROM category");
    $query->execute();

    return $query->fetchAll();
}