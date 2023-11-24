<?php

function getPolls(PDO $pdo, int $limit = null): array
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

function getPollById(PDO $pdo, int $id): array|bool
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

function getPollTotalUsersByPollId(PDO $pdo, int $id): int
{
    $query = $pdo->prepare('SELECT COUNT(DISTINCT upi.user_id) as total_users 
                            FROM poll_item pi 
                            LEFT JOIN user_poll_item upi ON upi.poll_item_id = pi.id 
                            WHERE pi.poll_id = :id');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $res = $query->fetch(PDO::FETCH_ASSOC);
    if ($res) {
        return (int)$res['total_users'];
    } else {
        return 0;
    }
}

function getPollItems(PDO $pdo, int $id): array
{
    $query = $pdo->prepare('SELECT *
    FROM poll_item
    WHERE poll_id = :id
    ORDER BY name ASC');

    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function addVote(PDO $pdo, int $user_id, array $items)
{
    $query = $pdo->prepare("INSERT INTO user_poll_item(user_id, poll_item_id) 
                            VALUES(:user_id, :poll_item_id)");
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    $res = true;
    foreach ($items as $key => $itemId) {
        $query->bindValue(':poll_item_id', (int)$itemId, PDO::PARAM_INT);
        if (!$query->execute()) {
            $res = false;
        }
    }
    return $res;
}

function removeVotesByPollIdAndUserId(PDO $pdo, int $poll_id, int $user_id)
{
    $query = $pdo->prepare('DELETE upi
    FROM user_poll_item upi
    JOIN poll_item pi ON pi.id = upi.poll_item_id
    WHERE pi.poll_id = :poll_id AND upi.user_id = :user_id');

    $query->bindValue(':poll_id', $poll_id, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

function savePoll(PDO $pdo, string $title, string $description, 
                    int $category_id, int $user_id):bool|int
{
    $query = $pdo->prepare('INSERT INTO poll(title, description, category_id, user_id) 
    VALUES(:title, :description, :category_id, :user_id)');

    $query->bindValue(':title', $title, PDO::PARAM_STR);
    $query->bindValue(':description', $description, PDO::PARAM_STR);
    $query->bindValue(':category_id', $category_id, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    if ($query->execute()) {
        return $pdo->lastInsertId();
    } else {
        return false;
    }

}

function savePollItem(PDO $pdo, int $poll_id, string $name, int $id = null): bool
{
    if ($id) {
        $query = $pdo->prepare("UPDATE poll_item SET poll_id = :poll_id, name = :name
                                        WHERE id = :id");
        $query->bindParam(':id', $id);

    } else {
        $query = $pdo->prepare("INSERT INTO poll_item (poll_id, name) VALUES (:poll_id, :name)");
    }
    $query->bindParam(':poll_id', $poll_id);
    $query->bindParam(':name', $name);
    return $query->execute();
}

/*
    Récupère toutes les propositions
*/
function getPollItemById(PDO $pdo, int $item_id):array
{

    $query = $pdo->prepare("SELECT * FROM poll_item WHERE id = :item_id");

    $query->bindParam(':item_id', $item_id, PDO::PARAM_INT);

    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

/*
    Supprimer une proposition
*/
function deletePollItemById(PDO $pdo, int $item_id):bool
{
    $query = $pdo->prepare("DELETE FROM poll_item
                            WHERE id = :item_id");
    $query->bindParam(':item_id', $item_id);

    return $query->execute();
}
