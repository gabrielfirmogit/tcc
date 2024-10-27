<?php
session_start(); // Certifique-se de iniciar a sessão

require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário está logado e é cliente
    if (!isset($_SESSION['id_usuario'])) {
        echo "Acesso negado.";
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];
    $id_local = $_POST['id_local'];
    $estrelas = $_POST['estrelas'];

    // Verifica se já existe uma avaliação deste usuário para este local
    $stmt = $pdo->prepare("SELECT * FROM avaliacoes WHERE id_usuario = :id_usuario AND id_local = :id_local");
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_local' => $id_local,
    ]);
    $avaliacao_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($avaliacao_existente) {
        // Atualiza a avaliação existente
        $stmt = $pdo->prepare("UPDATE avaliacoes SET estrelas = :estrelas WHERE id_usuario = :id_usuario AND id_local = :id_local");
        $stmt->execute([
            ':estrelas' => $estrelas,
            ':id_usuario' => $id_usuario,
            ':id_local' => $id_local,
        ]);
    } else {
        // Insere uma nova avaliação
        $stmt = $pdo->prepare("INSERT INTO avaliacoes (id_usuario, id_local, estrelas) VALUES (:id_usuario, :id_local, :estrelas)");
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_local' => $id_local,
            ':estrelas' => $estrelas,
        ]);
    }

    // Redireciona para a página de detalhes do local
    header("Location: ../detalhes_local.php?id=" . $id_local);
    exit;
}
?>
