<?php

require_once __DIR__ . '/data/functions.php';

$view   = filter_input(INPUT_GET, 'view') ?: 'list';
$action = filter_input(INPUT_POST, 'action');

switch ($action) {
    case 'create':
        $title    = trim((string)(filter_input(INPUT_POST, 'title') ?? ''));
        $artist   = trim((string)(filter_input(INPUT_POST, 'artist') ?? ''));
        $price    = (float)(filter_input(INPUT_POST, 'price') ?? 0);
        $format_id = (int)(filter_input(INPUT_POST, 'format_id') ?? 0);

        if ($title && $artist && $format_id) {
            record_insert($title, $artist, $price, $format_id);
            $view = 'created';
        } else {
            $view = 'create';
        }
        break;

    case 'delete':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $deleted = record_delete($id);
        }
        $view = 'deleted';
        break;

    case 'edit':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $record = record_get($id);
        }
        $view = 'create';
        break;

    case 'update':
        $id        =         filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title     = (string)filter_input(INPUT_POST, 'title',  FILTER_UNSAFE_RAW);
        $artist    = (string)filter_input(INPUT_POST, 'artist', FILTER_UNSAFE_RAW);
        $price_in  =         filter_input(INPUT_POST, 'price',   FILTER_UNSAFE_RAW);
        $format_id =         filter_input(INPUT_POST, 'format_id', FILTER_VALIDATE_INT);

        $price = is_numeric($price_in) ? (float)$price_in : null;

        if ($id && $title !== '' && $artist !== '' && $price !== null && $format_id) {
            record_update($id, $title, $artist, $price, (int)$format_id);
        }
        $view = 'updated';
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
    elseif ($view === 'updated') include __DIR__ . '/partials/record-updated.php';
    elseif ($view === 'deleted') include __DIR__ . '/partials/record-deleted.php';
    else                         include __DIR__ . '/partials/records-list.php';
    ?>

</body>

</html>