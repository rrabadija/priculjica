<?php header('Content-Type: text/html; charset=UTF-8');
    $host = 'localhost'; //Database login credentials
    $dbname = 'priculjica';
    $username = 'root';
    $password = 'kekkadakeda2556';

    try { //Set PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }