<?php

// NEW 
include __DIR__ . "/data/db.php";
include __DIR__ . "/data/functions.php";

session_start();

$view   = filter_input(INPUT_GET, 'view') ?: 'list';
$action = filter_input(INPUT_POST, 'action');

// This function will only run if the action sees that a post request has been made
// (which happens immediately) and if a login has NOT been detected
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ?view=login');
        exit;
    }
}

$public_views   = ['login', 'register'];
$public_actions = ['login', 'register'];

if ($action && !in_array($action, $public_actions, true)) {
    require_login();
}

if (!$action && !in_array($view, $public_views, true)) {
    require_login();
}
// END NEW

switch ($action) {
    // NEW 
    // The login case prompts the user to enter in a username and password that
    // already exists within the database
    case 'login':
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username && $password) {
            $user = user_find_by_username($username);
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            } else {
                $login_error = "Invalid username or password.";
                $view = 'login';
            }
        } else {
            $login_error = "Enter both fields.";
            $view = 'login';
        }
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        session_start();
        $view = 'login';
        break;

    // Different from the 'login' switch case, this case will ask the user to 
    // enter information to create a user that doesn't exist within the database
    case 'register':
        $username  = trim((string)($_POST['username'] ?? ''));
        $full_name = trim((string)($_POST['full_name'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');
        $confirm   = (string)($_POST['confirm_password'] ?? '');

        if ($username && $full_name && $password && $password === $confirm) {
            $existing = user_find_by_username($username);
            if ($existing) {
                $register_error = "That username already exists.";
                $view = 'register';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                user_create($username, $full_name, $hash);

                $user = user_find_by_username($username);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            }
        } else {
            $register_error = "Complete all fields and match passwords.";
            $view = 'register';
        }
        break;

    // Within this case, the data that is stored in $_SESSION['cart'] is the id
    // of the record that the user added to the cart. This is done so that the cart
    // can 'hold' and display the information of the record when in the cart page
    case 'add_to_cart':
        require_login();
        $record_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($record_id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][] = $record_id;
        }
        $view = 'list';
        break;

    case 'checkout':
        require_login();
        $cart_ids = $_SESSION['cart'] ?? [];

        if ($cart_ids) {
            foreach ($cart_ids as $rid) {
                purchase_create((int)$_SESSION['user_id'], (int)$rid);
            }
            $_SESSION['cart'] = [];
        }
        $view = 'checkout_success';
        break;
    // END NEW

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

// NEW
if ($view === 'cart') {
    $cart_ids = $_SESSION['cart'] ?? [];
    $records_in_cart = records_by_ids($cart_ids);
}
// END NEW
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
    // NEW
    if     ($view === 'login')            include __DIR__ . '/partials/login_form.php';
    elseif ($view === 'register')         include __DIR__ . '/partials/register_form.php';
    elseif ($view === 'cart')             include __DIR__ . '/partials/cart.php';
    elseif ($view === 'checkout_success') include __DIR__ . '/partials/checkout_success.php';
    // END NEW
    elseif ($view === 'list')             include __DIR__ . '/partials/records-list.php';
    elseif ($view === 'create')           include __DIR__ . '/partials/record-form.php';
    elseif ($view === 'created')          include __DIR__ . '/partials/record-created.php';
    elseif ($view === 'updated')          include __DIR__ . '/partials/record-updated.php';
    elseif ($view === 'deleted')          include __DIR__ . '/partials/record-deleted.php';
    else                                  include __DIR__ . '/partials/records-list.php';
    ?>

</body>

</html>