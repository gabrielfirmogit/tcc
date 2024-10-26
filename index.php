<?php
session_start();
require 'conexao.php';
require 'componentes/cabecalho.php';
require 'componentes/navbar.php';
require 'componentes/footer.php';
if (!isset($_SESSION['usuario_id']))
{
    header('Location: login.php');
    exit();
}
// Define título da página
$titulo_cabecalho = "Locais Disponíveis";
renderHead($titulo_cabecalho);
renderNavbar();

// Filtragem
$nomeFiltro = isset($_POST['nome']) ? $_POST['nome'] : '';
$precoMinimo = isset($_POST['preco_min']) ? $_POST['preco_min'] : 0;
$precoMaximo = isset($_POST['preco_max']) ? $_POST['preco_max'] : 1000000;

// Consulta os locais com base nos filtros
$query = "SELECT * FROM locais WHERE preco BETWEEN :preco_min AND :preco_max";
$params = [
    ':preco_min' => $precoMinimo,
    ':preco_max' => $precoMaximo,
];

if (!empty($nomeFiltro))
{
    $query .= " AND nome LIKE :nome";
    $params[':nome'] = '%' . $nomeFiltro . '%';
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$locais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Locais Disponíveis</h1>
    <!-- Filtros -->
    <form method="POST" class="mb-6">
        <div class="flex space-x-4 mb-4">
            <div class="flex-1">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Local</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nomeFiltro); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div class="flex-1">
                <label for="preco_min" class="block text-sm font-medium text-gray-700">Preço Mínimo</label>
                <input type="number" step="0.01" min="0" id="preco_min" name="preco_min"
                    value="<?php echo htmlspecialchars($precoMinimo); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div class="flex-1">
                <label for="preco_max" class="block text-sm font-medium text-gray-700">Preço Máximo</label>
                <input type="number" step="0.01" min="0" id="preco_max" name="preco_max"
                    value="<?php echo htmlspecialchars($precoMaximo); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
        </div>
        <button type="submit"
            class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            Filtrar </button>
    </form>
    <!-- Exibe os locais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> <?php if (!empty($locais)): ?>
            <?php foreach ($locais as $local): ?>
                <div class="bg-white rounded-lg shadow-md p-4"> <?php
                // Obter imagem do local
                $stmtImg = $pdo->prepare("SELECT url FROM imagens_local WHERE id_local = :id_local LIMIT 1");
                $stmtImg->execute([':id_local' => $local['id']]);
                $imagem = $stmtImg->fetchColumn();
                ?> <img src="<?php echo htmlspecialchars($imagem); ?>"
                        alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-40 object-cover rounded-md mb-4">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($local['nome']); ?></h2>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($local['descricao']); ?></p>
                    <p class="text-lg font-bold">R$ <?php echo number_format($local['preco'], 2, ',', '.'); ?></p>
                    <!-- Exibindo média de avaliações como estrelas -->
                    <p class="font-semibold">Média de Avaliações: <?php
                    $media_avaliacoes = number_format($local['media_avaliacoes'], 1);
                    for ($i = 1; $i <= 5; $i++)
                    {
                        echo $i <= $media_avaliacoes ? '★' : '☆';
                    }
                    ?> </p>
                    <!-- Botão para detalhes -->
                    <a href="detalhes_local.php?id=<?php echo $local['id']; ?>"
                        class="mt-4 inline-block bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">Saber
                        mais</a>
                </div> <?php endforeach; ?> <?php else: ?>
            <p class="text-center text-gray-500">Nenhum local encontrado.</p>
        <?php endif; ?>
    </div>
</div>
<?php
renderFooter();
?>