<?php 

require_once __DIR__ . '/db.php';



function formats_all(): array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * 
        FROM formats
        ORDER BY name DESC
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function records_all(): array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT r.title, r.artist, r.price, f.name
        FROM records AS r
        JOIN formats AS f ON f.id = r.format_id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function record_insert(string $title, string $artist, float $price, int $format_id): void {
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
    $rowCount = $stmt->rowCount();

    if ($rowCount >= 1) {
        echo("Insert success: true, rows: $rowCount <br>");
        echo("Newest: $title -- $format_id");
    }
    else {
        echo("Insert success: false, rows: $rowCount");
    }
}


?>