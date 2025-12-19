<?php
$server = "localhost";
$user   = "root";
$pass   = "";
$db     = "community";
$conn = null;

try {
    $conn = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connessione fallita: " . $e->getMessage();
    exit;
}