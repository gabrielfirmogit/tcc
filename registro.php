<?php
session_start(); // Inicia a sessão para armazenar o ID do usuário

require 'conexao.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $cpf_cnpj = $_POST['cpf_cnpj'] ?? '';

    if ($tipo_usuario === 'Cliente') {
        // Lógica para registrar cliente no banco de dados
        $stmt = $pdo->prepare("INSERT INTO usuario (email, senha, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$email, password_hash($senha, PASSWORD_DEFAULT), $telefone]);

        // Obtém o ID do usuário recém-cadastrado
        $id_usuario = $pdo->lastInsertId();
        
        // Armazena o ID do usuário na sessão
        $_SESSION['id_usuario'] = $id_usuario;

        echo 'Cliente cadastrado com sucesso!';
    } elseif ($tipo_usuario === 'Empreendedor') {
        // Lógica para registrar empreendedor no banco de dados
        $stmt = $pdo->prepare("INSERT INTO usuario (email, senha, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$email, password_hash($senha, PASSWORD_DEFAULT), $telefone]);

        // Obtém o ID do usuário recém-cadastrado
        $id_usuario = $pdo->lastInsertId();
        
        // Armazena o ID do usuário na sessão
        $_SESSION['id_usuario'] = $id_usuario;

        // Registra o empreendedor com o ID de usuário vinculado
        $stmt = $pdo->prepare("INSERT INTO empreendedor (id_usuario, email, telefone, cnpj) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $email, $telefone, $cpf_cnpj]);

        echo 'Empreendedor cadastrado com sucesso!';
    } else {
        echo 'Tipo de usuário indefinido';
    }
}
