<?php
$host = 'localhost';
$db   = 'kiluxo';  // ← coloque o nome do seu banco
$user = 'root';        // ← coloque o nome do seu usuário
$pass = '';          // ← coloque sua senha
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}
