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

// NEW

// 'user_create' is used to register a new user (crazy, I know) in the register_form page, 
// taking in a username, full name, and password (which gets hashed for security purposes). 
// This function does not return anything 
function user_create(string $username, string $full_name, string $hash): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO users (username, full_name, password_hash)
            VALUES (:u, :f, :p)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$username, ':f'=>$full_name, ':p'=>$hash]);
}

// 'user_find_by_username' is used to locate the user data by creating a select statement
// that gets passed into the database with a given username. It takes in the given username
// and outputs all data attached to that username (i.e. full name, orders, etc.) within an array
function user_find_by_username(string $username): ?array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u'=>$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

// 'records_by_ids' is used to locate the record data by creating a select statement that 
// gets passed into the database with a given id. This function takes in all ids given and
// outputs the records id, title, artist, price, and name within an array
function records_by_ids(array $ids): array {
    if (empty($ids)) return [];
    $pdo = get_pdo();
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT r.id, r.title, r.artist, r.price, f.name
            FROM records r
            JOIN formats f ON r.format_id = f.id
            WHERE r.id IN ($ph)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 'purchase_create' is used to add new purchase details to an existing user. It takes the given
// user id and record id(s), then adds them to the user's table within the database using an INSERT
// INTO statement
function purchase_create(int $user_id, int $record_id): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO purchases (user_id, record_id, purchase_date)
            VALUES (:u, :r, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$user_id, ':r'=>$record_id]);
}
// END NEW