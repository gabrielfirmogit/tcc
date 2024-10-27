<?php
session_start();
require 'conexao.php';
require 'componentes/cabecalho.php';
require 'componentes/navbar.php';
require 'componentes/footer.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Processa exclusão de local se solicitado
if (isset($_POST['excluir_local'])) {
    $id_local = filter_var($_POST['id_local'], FILTER_VALIDATE_INT);

    if ($id_local) {
        try {
            $pdo->beginTransaction();

            // Verifica se o local pertence ao usuário
            $stmt = $pdo->prepare("SELECT id FROM locais WHERE id = :id_local AND id_usuario = :id_usuario");
            $stmt->execute(['id_local' => $id_local, 'id_usuario' => $id_usuario]);

            if ($stmt->fetch()) {
                // Recupera todas as imagens do local para exclusão
                $stmt = $pdo->prepare("SELECT url FROM imagens_local WHERE id_local = :id_local");
                $stmt->execute(['id_local' => $id_local]);
                $imagens = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Remove as imagens do sistema de arquivos
                foreach ($imagens as $imagem) {
                    if ($imagem && file_exists($imagem)) {
                        unlink($imagem);
                    }
                }

                // Remove o local e as imagens do banco de dados
                $stmt = $pdo->prepare("DELETE FROM locais WHERE id = :id_local");
                $stmt->execute(['id_local' => $id_local]);

                $stmt = $pdo->prepare("DELETE FROM imagens_local WHERE id_local = :id_local");
                $stmt->execute(['id_local' => $id_local]);

                $pdo->commit();
            } else {
                $pdo->rollBack();
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Erro ao excluir o local: " . $e->getMessage();
        }
    }
}

// Consulta os locais do usuário
$stmt = $pdo->prepare("SELECT l.*, i.url AS imagem_principal 
                       FROM locais l
                       LEFT JOIN imagens_local i ON l.id = i.id_local
                       WHERE l.id_usuario = :id_usuario
                       GROUP BY l.id");
$stmt->execute(['id_usuario' => $id_usuario]);
$locais = $stmt->fetchAll(PDO::FETCH_ASSOC);

renderHead('Dashboard');
renderNavbar();
?> <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Seus Locais</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> <?php foreach ($locais as $local): ?> <div
            class="bg-white shadow-md rounded-lg p-6"> <?php if ($local['imagem_principal']): ?> <img
                src="<?php echo htmlspecialchars($local['imagem_principal']); ?>"
                alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-60 object-cover rounded-md mb-4">
            <?php endif; ?> <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($local['nome']); ?></h2>
            <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($local['descricao']); ?></p>
            <div class="flex justify-between">
                <a href="editar_local.php?id=<?php echo $local['id']; ?>"
                    class="text-blue-500 hover:underline">Editar</a>
                <form method="post" onsubmit="return confirm('Deseja realmente excluir este local?');">
                    <input type="hidden" name="id_local" value="<?php echo $local['id']; ?>">
                    <button type="submit" name="excluir_local" class="text-red-500 hover:underline">Excluir</button>
                </form>
            </div>
        </div> <?php endforeach; ?> </div>
</div> <?php renderFooter(); ?>