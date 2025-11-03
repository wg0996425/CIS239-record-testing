<?php

require_once __DIR__ . '/data/functions.php'


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W. Viktor Gray - Record Store</title>
</head>

<body>

    <h2>Unit Test 1 — Formats</h2>
    <?php
    $list = formats_all();
    echo("Formats: ");

    foreach ($list as $li) {
        echo($li['name'] . ', ');
    }
    ?>

    <h2>Unit Test 2 — Records JOIN</h2>
    <?php
    $list = records_all();

    foreach ($list as $li) {
        echo($li['title'].' -- '.$li['name'].' -- '.$li['price'].'<br>');
    }
    ?>

    <h2>Unit Test 3 — Insert</h2>
    <?php
    echo("Insert success: " )
    ?>



</body>

</html>