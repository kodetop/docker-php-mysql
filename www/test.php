<?php
$host = 'mysql';  // Nombre del servicio en docker-compose
$db   = getenv('MYSQL_DATABASE') ?: 'app_database';
$user = getenv('MYSQL_USER') ?: 'developer';
$pass = getenv('MYSQL_PASSWORD') ?: 'developer_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa!";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
