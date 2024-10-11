<?php
$host = '127.0.0.1';  // Host do banco de dados
$db = 'tcc';          // Nome do banco de dados
$user = 'root';       // Usuário do banco de dados
$pass = '';           // Senha do banco de dados
$charset = 'utf8mb4'; // Charset

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}
?>