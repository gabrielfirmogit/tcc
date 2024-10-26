<?php
session_start();
require 'conexao.php';
require 'componentes/cabecalho.php';
require 'componentes/navbar.php';
require 'componentes/footer.php';

if (!isset($_SESSION['usuario_id'])) {   
    header('Location: login.php');
    exit();
}

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

$usuario_logado = isset($_SESSION['usuario_id']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';

// Renderiza o cabeçalho
$titulo_cabecalho = htmlspecialchars($local['nome']);
renderHead($titulo_cabecalho);
renderNavbar();
?> 

<div class="container mx-auto px-4 py-8">
    <!-- Imagens do Local (no topo) -->
    <?php if ($local['imagens']): ?>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <?php $imagens = explode(',', $local['imagens']); ?>
            <div class="col-span-2 lg:col-span-3">
                <a href="<?php echo htmlspecialchars($imagens[0]); ?>" data-lightbox="local-images" data-title="<?php echo htmlspecialchars($local['nome']); ?>">
                    <img src="<?php echo htmlspecialchars($imagens[0]); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-64 object-cover rounded-md">
                </a>
            </div>
            <?php foreach (array_slice($imagens, 1, 4) as $imagem): ?>
                <a href="<?php echo htmlspecialchars($imagem); ?>" data-lightbox="local-images" data-title="<?php echo htmlspecialchars($local['nome']); ?>">
                    <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-64 object-cover rounded-md">
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Seção Inferior: Descrição, Informações do Proprietário e Container de Preço -->
    <div class="flex flex-col lg:flex-row lg:space-x-8">

        <!-- Descrição e Informações do Proprietário -->
        <div class="flex-1 mb-8 lg:mb-0">
            <!-- Título e Localização -->
            <h1 class="text-3xl font-semibold mb-2"><?php echo htmlspecialchars($local['nome']); ?></h1>
            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($local['endereco']); ?></p>

            <!-- Descrição -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold">Descrição</h2>
                <p class="text-lg mt-2"><?php echo nl2br(htmlspecialchars($local['descricao'])); ?></p>
            </div>

            <!-- Linha separadora -->
            <hr class="border-t border-gray-300 my-6">

            <!-- Informações do Proprietário -->
            <div>
                <h2 class="text-xl font-semibold">Informações do Proprietário</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($local['email_proprietario']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($local['telefone_proprietario']); ?></p>
            </div>
        </div>

        <!-- Container de Preço, Nota e Botão de Contato -->
        <div class="lg:w-1/3 bg-gray-100 p-6 rounded-lg shadow-md flex flex-col items-center lg:items-start h-full">
            <p class="text-2xl font-bold mb-2">R$ <?php echo number_format($local['preco'], 2, ',', '.'); ?> / noite</p>
            <div class="flex items-center mt-2 mb-4">
                <span class="text-yellow-500 font-semibold text-lg"><?php echo number_format($local['media_avaliacoes'], 1); ?> ★</span>
                <span class="ml-2 text-gray-600">(<?php echo htmlspecialchars($local['numero_avaliacoes']); ?> avaliações)</span>
            </div>
            <a href="https://wa.me/<?php echo preg_replace('/[^\d]/', '', $local['telefone_proprietario']); ?>" class="w-full bg-green-500 text-white py-2 px-4 rounded-md text-center hover:bg-green-600 mt-4">
                Contato via WhatsApp
            </a>
        </div>

    </div>
</div>

<!-- Formulário de Avaliação (para usuários logados) -->
<?php if ($usuario_logado && strtolower($tipo_usuario) == 'cliente'): ?>
    <div class="container mx-auto px-4 mt-8">
        <form action="php/salvar_avaliacao.php" method="POST" class="bg-gray-100 p-4 rounded-lg shadow-md">
            <input type="hidden" name="id_local" value="<?php echo htmlspecialchars($id_local); ?>">
            <label for="estrelas" class="block text-gray-700 font-semibold mb-2">Sua Avaliação:</label>
            <div class="mb-4">
                <label for="estrelas" class="block text-gray-700">Sua Avaliação:</label>
                <select name="estrelas" id="estrelas" required class="mt-1 block w-full p-2 border rounded">
                    <option value="">Selecione...</option>
                    <option value="1">1 Estrela</option>
                    <option value="2">2 Estrelas</option>
                    <option value="3">3 Estrelas</option>
                    <option value="4">4 Estrelas</option>
                    <option value="5">5 Estrelas</option>
                </select>
            </div>
            <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 mt-4 w-full">
                Enviar Avaliação
            </button>
        </form>
    </div>
<?php endif; ?>

</div> 

<?php
renderFooter();
?>
