<?php
session_start();
require 'conexao.php';
require 'componentes/cabecalho.php';
require 'componentes/navbar.php';
require 'componentes/footer.php';

// Verifica se o ID foi passado na URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Obtém o ID do local
$id_local = $_GET['id'];

// Consulta os detalhes do local e do proprietário
$query = "SELECT l.*, u.email AS email_proprietario, u.telefone AS telefone_proprietario, 
                 GROUP_CONCAT(DISTINCT i.url) AS imagens 
          FROM locais l
          JOIN usuario u ON l.id_usuario = u.id
          LEFT JOIN imagens_local i ON l.id = i.id_local 
          WHERE l.id = :id_local
          GROUP BY l.id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_local', $id_local);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o local existe
if (!$local) {
    header('Location: index.php');
    exit;
}

// Renderiza o cabeçalho
$titulo_cabecalho = htmlspecialchars($local['nome']);
renderHead($titulo_cabecalho);
renderNavbar();
?> <div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-6 text-center"><?php echo htmlspecialchars($local['nome']); ?></h1>
    <?php if ($local['imagens']): ?> <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <?php foreach (explode(',', $local['imagens']) as $imagem): ?> <a
            href="<?php echo htmlspecialchars($imagem); ?>" data-lightbox="local-images"
            data-title="<?php echo htmlspecialchars($local['nome']); ?>">
            <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>"
                class="w-full h-60 object-cover rounded-md">
        </a> <?php endforeach; ?> </div> <?php endif; ?> <p class="text-lg font-semibold">Descrição:</p>
    <p class="mb-4"><?php echo nl2br(htmlspecialchars($local['descricao'])); ?></p>
    <p class="text-lg font-semibold">Preço: R$ <?php echo number_format($local['preco'], 2, ',', '.'); ?></p>
    <p class="text-lg font-semibold">Endereço:</p>
    <p class="mb-4"><?php echo htmlspecialchars($local['endereco']); ?></p>
    <p class="text-lg font-semibold">Média de Avaliações: <?php
    $media_avaliacoes = number_format($local['media_avaliacoes'], 1);
    for ($i = 1; $i <= 5; $i++) {
        echo $i <= $media_avaliacoes ? '★' : '☆';
    }
    ?></p>
    <h2 class="text-3xl font-semibold mt-8">Informações do Proprietário</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($local['email_proprietario']); ?></p>
    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($local['telefone_proprietario']); ?></p>
    <a href="https://wa.me/<?php echo preg_replace('/[^\d]/', '', $local['telefone_proprietario']); ?>"
        class="mt-4 inline-block bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
        Contato via WhatsApp </a>
    <h2 class="text-3xl font-semibold mt-8">Comentários</h2> <?php
    // Consulta para obter comentários e avaliações
    $queryComentarios = "SELECT c.feedback, c.estrelas, u.email AS email_usuario 
                         FROM comentarios c 
                         JOIN usuario u ON c.id_usuario = u.id 
                         WHERE c.id_local = :id_local 
                         ORDER BY c.created_at DESC";

    $stmtComentarios = $pdo->prepare($queryComentarios);
    $stmtComentarios->bindParam(':id_local', $id_local);
    $stmtComentarios->execute();
    $comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

    if ($comentarios): ?> <ul class="list-disc pl-5"> <?php foreach ($comentarios as $comentario): ?> <li class="mb-4">
            <p><strong><?php echo htmlspecialchars($comentario['email_usuario']); ?>:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($comentario['feedback'])); ?></p>
            <p class="text-sm text-gray-600">Avaliação:
                <?php echo str_repeat('★', $comentario['estrelas']) . str_repeat('☆', 5 - $comentario['estrelas']); ?>
            </p>
        </li> <?php endforeach; ?> </ul> <?php else: ?> <p>Não há comentários para este local.</p> <?php endif; ?> <a
        href="index.php"
        class="mt-4 inline-block bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
        Voltar </a>
</div> <?php
renderFooter();
?>