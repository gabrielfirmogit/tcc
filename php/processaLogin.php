<?php
session_start();
require '../conexao.php'; // Importa a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a consulta para verificar o usuário
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha']))
    {
        $_SESSION['usuario_id'] = $usuario['id']; // Armazena o ID do usuário na sessão
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; // Armazena o tipo de usuário na sessão
        header("Location: ../index.php"); // Redireciona para a página inicial
        exit();
    }
    else
    {
        // Se as credenciais estiverem incorretas
        echo "Email ou senha incorretos.";
        // Você pode redirecionar de volta ao login ou exibir uma mensagem
    }
}
?>