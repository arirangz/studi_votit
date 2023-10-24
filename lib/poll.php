<?php

function getPolls(PDO $pdo)
{

    $query = $pdo->prepare("SELECT * FROM poll");
    //$query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}