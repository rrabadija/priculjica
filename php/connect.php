<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'config.php';

    $DBHost = DB_HOST; //Database login credentials
    $DBName = DB_NAME;
    $DBUser = DB_USER;
    $DBPass = DB_PASS;

    $PDO = new PDO("mysql:host=$DBHost;dbname=$DBName;charset=utf8mb4", $DBUser, $DBPass); //Set PDO connection
    $PDO -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);