<?php
session_start();
require '../conexao.php'; // Importa a conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografa a senha
    $telefone = $_POST['telefone'];
    $tipo_usuario = $_POST['tipo_usuario']; // Recebe o tipo de usuário: 'cliente' ou 'empreendedor'
    $cnpj = $_POST['cnpj'] ?? null; // Recebe o CNPJ se for empreendedor

    // Insere os dados na tabela 'usuario'
    try
    {
        $pdo->beginTransaction();

        // Insere na tabela 'usuario'
        $query = "INSERT INTO usuario (email, senha, telefone, tipo, cnpj) VALUES (:email, :senha, :telefone, :tipo, :cnpj)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'email' => $email,
            'senha' => $senha,
            'telefone' => $telefone,
            'tipo' => $tipo_usuario,
            'cnpj' => $cnpj // Adiciona o CNPJ aqui
        ]);

        // Pega o ID do usuário recém-cadastrado
        $id_usuario = $pdo->lastInsertId();

        $pdo->commit();

        // Define a sessão e redireciona
        $_SESSION['id_usuario'] = $id_usuario;
        $_SESSION['tipo_usuario'] = $tipo_usuario;

        header('Location: ../login.php'); // Redireciona para a página de login
        exit;

    }
    catch (PDOException $e)
    {
        $pdo->rollBack(); // Desfaz a transação em caso de erro
        die("Erro ao cadastrar usuário: " . $e->getMessage());
    }
}
else
{
    // Se a página foi acessada diretamente, redireciona para o formulário de cadastro
    header('Location: ../cadastro_usuario.php');
    exit;
}
?>