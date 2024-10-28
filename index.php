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

// Define título da página
$titulo_cabecalho = "Locais Disponíveis";
renderHead($titulo_cabecalho);
renderNavbar();

// Captura dos filtros enviados pelo formulário
$nomeFiltro = $_POST['nome'] ?? '';
$precoMinimo = $_POST['preco_min'] ?? 0;
$precoMaximo = $_POST['preco_max'] ?? 1000000;
$tipo_local = $_POST['tipo_local'] ?? '';
$avaliacaoMinima = $_POST['avaliacao_min'] ?? 0;

// Consulta com filtros aplicados
$query = "SELECT * FROM locais WHERE preco BETWEEN :preco_min AND :preco_max";
$params = [
    ':preco_min' => $precoMinimo,
    ':preco_max' => $precoMaximo,
];

if (!empty($nomeFiltro)) {
    $query .= " AND nome LIKE :nome";
    $params[':nome'] = '%' . $nomeFiltro . '%';
}

if (!empty($tipo_local)) {
    $query .= " AND tipo_local = :tipo_local";
    $params[':tipo_local'] = $tipo_local;
}

if ($avaliacaoMinima > 0) {
    $query .= " AND media_avaliacoes >= :avaliacao_min";
    $params[':avaliacao_min'] = $avaliacaoMinima;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$locais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mx-auto px-4 py-8">

    <!-- Filtros -->
    <form method="POST" class="mb-6 text-center flex justify-center items-center space-x-4">
        <div class="relative w-2/3">
            <input type="text" id="nome" name="nome" placeholder="Buscar"
                value="<?php echo htmlspecialchars($nomeFiltro); ?>"
                class="mt-1 block w-full rounded-full border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 pl-4 pr-10">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <button type="submit" class="text-white bg-[#7E22CE] rounded-full p-2 hover:bg-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l5 5m-5-5a7 7 0 1 0-10 0 7 7 0 0 0 10 0z" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Botão para abrir o pop-up de filtros de preço -->
        <button type="button" onclick="toggleFilterPopup()"
            class="bg-gray-100 text-gray-700 py-2 px-3 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4zm0 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2zm0 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2z" />
            </svg>
            Filtros
        </button>
    </form>

    <!-- Pop-up para os filtros de preço -->
    <div id="filterPopup" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-1/3">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Filtros</h2>

        <form method="POST">
            <!-- Filtro por Preço -->
            <div class="mb-4">
                <label for="preco_min" class="block text-sm font-medium text-gray-700">Preço Mínimo</label>
                <input type="number" step="0.01" min="0" id="preco_min" name="preco_min"
                    value="<?php echo htmlspecialchars($precoMinimo ?? ''); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div class="mb-4">
                <label for="preco_max" class="block text-sm font-medium text-gray-700">Preço Máximo</label>
                <input type="number" step="0.01" min="0" id="preco_max" name="preco_max"
                    value="<?php echo htmlspecialchars($precoMaximo ?? ''); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- Dropdown de Tipo de Local -->
            <div class="mb-4">
                <label for="tipo_local" class="block text-sm font-medium text-gray-700">Tipo de Local</label>
                <select id="tipo_local" name="tipo_local"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    <option value="">Selecione...</option>
                    <option value="Edicula" <?php echo isset($tipo_local) && $tipo_local == 'Edicula' ? 'selected' : ''; ?>>
                        Edícula
                    </option>
                    <option value="Salão" <?php echo isset($tipo_local) && $tipo_local == 'Salão' ? 'selected' : ''; ?>>
                        Salão
                    </option>
                    <option value="Chácara" <?php echo isset($tipo_local) && $tipo_local == 'Chácara' ? 'selected' : ''; ?>>
                        Chácara
                    </option>
                </select>
            </div>

            <!-- Filtro por Avaliação -->
            <div class="mb-4">
                <label for="avaliacao" class="block text-sm font-medium text-gray-700">Avaliação Mínima</label>
                <div class="flex">
                    <input type="hidden" name="avaliacao_min" id="avaliacao_min" value="0">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="cursor-pointer text-2xl star" data-value="<?php echo $i; ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                Aplicar Filtros
            </button>
        </form>

        <button onclick="toggleFilterPopup()"
            class="mt-4 w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            Fechar
        </button>
    </div>
</div>

    <!-- Exibe os locais -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 p-4">
        <?php if (!empty($locais)): ?>
            <?php foreach ($locais as $local): ?>
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <?php
                    // Obter imagem do local
                    $stmtImg = $pdo->prepare("SELECT url FROM imagens_local WHERE id_local = :id_local LIMIT 1");
                    $stmtImg->execute([':id_local' => $local['id']]);
                    $imagem = $stmtImg->fetchColumn();
                    ?>
                    <img src="<?php echo htmlspecialchars($imagem); ?>" 
                        alt="<?php echo htmlspecialchars($local['nome']); ?>" 
                        class="w-full h-48 object-cover rounded-t-lg">

                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-1 truncate"><?php echo htmlspecialchars($local['nome']); ?></h2>
                        <p class="text-purple-600 font-bold mb-2">R$ <?php echo number_format($local['preco'], 2, ',', '.'); ?></p>

                        <!-- Exibindo média de avaliações como estrelas -->
                        <div class="flex items-center mb-4">
                            <?php
                            $media_avaliacoes = number_format($local['media_avaliacoes'], 1);
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $media_avaliacoes ? '<span class="text-yellow-500">★</span>' : '<span class="text-gray-300">☆</span>';
                            }
                            ?>
                            <span class="ml-2 text-sm text-gray-500">(<?php echo $media_avaliacoes; ?>)</span>
                        </div>

                        <!-- Botão para detalhes -->
                        <a href="detalhes_local.php?id=<?php echo $local['id']; ?>" 
                            class="block w-full bg-purple-600 text-white text-center py-2 rounded-md hover:bg-purple-700 transition-colors">
                            Saber mais
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500">Nenhum local encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Script para exibir/ocultar o pop-up -->
<script>
function toggleFilterPopup() {
    const popup = document.getElementById('filterPopup');
    popup.classList.toggle('hidden');
}
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function () {
        let rating = this.getAttribute('data-value');
        document.getElementById('avaliacao_min').value = rating;

        // Destacar as estrelas selecionadas
        document.querySelectorAll('.star').forEach(s => s.classList.remove('text-yellow-400'));
        for (let i = 0; i < rating; i++) {
            document.querySelectorAll('.star')[i].classList.add('text-yellow-400');
        }
    });
});
</script>

<?php
renderFooter();
?>
