<?php

require_once __DIR__ . '/db.php';



function formats_all(): array
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * 
        FROM formats
        ORDER BY name DESC
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function records_all(): array
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT r.title, r.artist, r.price, f.name, r.id
        FROM records AS r
        JOIN formats AS f ON f.id = r.format_id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function record_insert(string $title, string $artist, float $price, int $format_id): void
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        INSERT INTO records (title, artist, price, format_id)
        VALUES (:title, :artist, :price, :format_id)
    ");
    $stmt->execute([
        ':title'     => $title,
        ':artist'    => $artist,
        ':price'     => $price,
        ':format_id' => $format_id
    ]);
}

function formats_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name FROM formats ORDER BY name")->fetchAll();
}

function record_delete(int $id): int
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("DELETE FROM records WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount();
}

function record_get(int $id): ?array
{
    $pdo = get_pdo();
    $sql = "SELECT r.id, r.title, r.artist, r.price, r.format_id, f.name AS format_name
                FROM records r
                JOIN formats f ON f.id = r.format_id
                WHERE r.id = :id
                LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}


function record_update(int $id, string $title, string $artist, float $price, int $format_id): int
{
    $pdo = get_pdo();
    $sql = "UPDATE records
                SET title = :title,
                artist = :artist,
                price = :price,
                format_id = :format_id
                WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title'     => $title,
        ':artist'    => $artist,
        ':price'     => $price,
        ':format_id' => $format_id,
        ':id'        => $id
    ]);
    return $stmt->rowCount();
}
