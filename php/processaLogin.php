<?php
session_start();
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha']))
    {
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo']; // Certifique-se de que esta linha está correta
       

        // Mensagem de depuração
        echo "Sessão iniciada: id_usuario = " . $_SESSION['id_usuario'] . ", tipo_usuario = " . $_SESSION['tipo_usuario'];

        header("Location: ../index.php");
        exit();
    }
    else
    {
        echo "Email ou senha incorretos.";
    }
}
?>