<?php header('Content-Type: application/json; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['user_role'] === 'admin') {
        if (isset($FETCH['toolbarControl'])) {
            echo json_encode(file_get_contents(__DIR__ . '/templates/' . sanitize($FETCH['toolbarControl'] ?? '') . '.html'));
        }

        exit;
    }
