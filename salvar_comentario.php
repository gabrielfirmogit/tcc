<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nome = $data['nome'];
    $feedback = $data['feedback'];
    $estrelas = $data['estrelas'];

    // Conectar ao banco de dados
    $host = '127.0.0.1';
    $db = 'tcc';
    $user = 'root'; // Seu usuário do MySQL
    $pass = ''; // Sua senha do MySQL

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Inserir no banco de dados
        $stmt = $pdo->prepare("INSERT INTO comentarios (nome, feedback, estrelas) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $feedback, $estrelas]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
