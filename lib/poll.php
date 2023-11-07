<?php

function getPolls(PDO $pdo, int $limit = null):array
{

    $sql = "SELECT poll.*, category.name as category_name FROM poll
            JOIN category ON category.id = poll.category_id
            ORDER BY poll.id DESC";

    if ($limit) {
        $sql .= " LIMIT :limit";
    }

    $query = $pdo->prepare($sql);
    
    if ($limit) {
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getPollById(PDO $pdo, int $id):array|bool
{
    $query = $pdo->prepare('SELECT * FROM poll WHERE id = :id');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
}

function getPollResultsByPollId(PDO $pdo, int $id): array
{
    $query = $pdo->prepare('SELECT pi.id, pi.name, COUNT(upi.poll_item_id) as votes FROM poll_item pi
                            LEFT JOIN user_poll_item upi ON upi.poll_item_id = pi.id
                            WHERE poll_id = :id
                            GROUP BY pi.id
                            ORDER BY votes DESC');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}