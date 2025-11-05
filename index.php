<?php

require_once __DIR__ . '/data/functions.php';

$view   = filter_input(INPUT_GET, 'view') ?: 'list';
$action = filter_input(INPUT_POST, 'action');

switch ($action) {
    // ========== CREATE ==========
    case 'create':
        // Get data from form input
        $title    = trim((string)(filter_input(INPUT_POST, 'title') ?? ''));
        $artist   = trim((string)(filter_input(INPUT_POST, 'artist') ?? ''));
        $price    = (float)(filter_input(INPUT_POST, 'price') ?? 0);
        $format_id = (int)(filter_input(INPUT_POST, 'format_id') ?? 0);

        if ($title && $artist && $format_id) {
            record_insert($title, $artist, $price, $format_id);
            $view = 'created';
        } else {
            $view = 'create'; // missing fields
        }
        break;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W. Viktor Gray - Record Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body class="container py-4">
    <?php include __DIR__ . '/components/nav.php'; ?>
    <br>
    <?php 
    if ($view === 'list')        include __DIR__ . '/partials/records-list.php';
    elseif ($view === 'create')  include __DIR__ . '/partials/record-form.php';
    elseif ($view === 'created') include __DIR__ . '/partials/record-created.php';
    else                         include __DIR__ . '/partials/records-list.php';
    ?>

</body>

</html>