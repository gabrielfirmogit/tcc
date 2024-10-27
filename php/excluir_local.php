<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'empreendedor')
{
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['id_usuario'];
$local_id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM locais WHERE id = :id AND id_usuario = :id_usuario");
$stmt->execute(['id' => $local_id, 'id_usuario' => $usuario_id]);

header('Location: dashboard_empreendedor.php');
exit();
?>