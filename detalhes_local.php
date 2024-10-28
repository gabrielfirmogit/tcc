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

// Verifica se o ID foi passado na URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Obtém o ID do local
$id_local = $_GET['id'];

// Consulta os detalhes do local e do proprietário
$query = "SELECT l.*, 
                 l.tipo_local,  -- Incluímos aqui o campo tipo_local
                 u.email AS email_proprietario, 
                 u.telefone AS telefone_proprietario, 
                 COUNT(av.id) AS numero_avaliacoes, 
                 AVG(av.estrelas) AS media_avaliacoes,
                 GROUP_CONCAT(DISTINCT i.url) AS imagens 
          FROM locais l
          JOIN usuario u ON l.id_usuario = u.id
          LEFT JOIN imagens_local i ON l.id = i.id_local 
          LEFT JOIN avaliacoes av ON l.id = av.id_local
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

$usuario_logado = isset($_SESSION['id_usuario']);
$tipo_usuario = $_SESSION['tipo_usuario'] ?? '';

// Renderiza o cabeçalho
$titulo_cabecalho = htmlspecialchars($local['nome']);
renderHead($titulo_cabecalho);
renderNavbar();
?>
<div class="container mx-auto px-6 py-8 max-w-4xl">
    <!-- Imagens do Local (no topo) -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <?php $imagens = explode(',', $local['imagens']); ?>
        <div class="col-span-2 lg:col-span-3">
            <img src="<?php echo htmlspecialchars($imagens[0]); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-64 object-cover rounded-md cursor-pointer" onclick="openCarouselModal(0)">
        </div>
        <?php foreach (array_slice($imagens, 1) as $index => $imagem): ?>
            <?php if ($index < 4): ?>
                <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-64 object-cover rounded-md cursor-pointer" onclick="openCarouselModal(<?php echo $index + 1; ?>)">
            <?php else: ?>
                <div class="relative">
                    <img src="<?php echo htmlspecialchars($imagem); ?>" alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-64 object-cover rounded-md opacity-50" onclick="openCarouselModal(<?php echo $index + 1; ?>)">
                    <span class="absolute top-1 left-1 text-white bg-black bg-opacity-50 px-2 py-1 rounded">
                        +<?php echo count($imagens) - 4; ?>
                    </span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Seção Inferior: Descrição, Informações do Proprietário e Container de Preço -->
    <div class="flex flex-col lg:flex-row lg:space-x-8">

        <!-- Descrição e Informações do Proprietário -->
        <div class="flex-1 mb-8 lg:mb-0">
            <!-- Título e Localização -->
            <h1 class="text-3xl font-semibold mb-2"><?php echo htmlspecialchars($local['nome']); ?></h1>
            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($local['endereco']); ?></p>
            <div class="mb-4">
    <h2 class="text-xl font-semibold">Tipo de Local</h2>
    <p class="text-lg mt-2"><?php echo htmlspecialchars($local['tipo_local']); ?></p>
</div>

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

<div id="carouselModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="relative max-w-3xl w-full mx-auto">
        <span class="absolute top-4 right-4 text-white text-3xl cursor-pointer" onclick="closeCarouselModal()">×</span>

        <!-- Botão de navegação para a esquerda -->
        <span class="absolute left-0 top-1/2 transform -translate-y-1/2 text-white text-3xl cursor-pointer" onclick="changeSlide(-1)">&#10094;</span>

        <!-- Imagem do Carousel -->
        <img id="carouselImage" class="w-full h-auto rounded-md">

        <!-- Botão de navegação para a direita -->
        <span class="absolute right-0 top-1/2 transform -translate-y-1/2 text-white text-3xl cursor-pointer" onclick="changeSlide(1)">&#10095;</span>
    </div>
</div>

<!-- Formulário de Avaliação (para usuários logados) -->
<?php if ($usuario_logado && strtolower($tipo_usuario) == 'cliente'): ?>
    <div class="container mx-auto px-6 mt-8 max-w-4xl">
        <form action="php/salvar_avaliacao.php" method="POST" class="bg-gray-100 p-4 rounded-lg shadow-md">
            <input type="hidden" name="id_local" value="<?php echo htmlspecialchars($id_local); ?>">
            <label for="estrelas" class="block text-gray-700 font-semibold mb-2">Sua Avaliação:</label>
            <div class="mb-4">
                <div class="flex">
                    <input type="hidden" name="estrelas" id="estrelas" value="0" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="cursor-pointer text-3xl star" data-value="<?php echo $i; ?>">
                            ★
                        </span>
                    <?php endfor; ?>
                </div>
                <div class="text-gray-500 mt-1">Passe o mouse sobre as estrelas para avaliar.</div>
            </div>
            <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 mt-4 w-full">
                Enviar Avaliação
            </button>
        </form>
    </div>

<?php endif; ?>

<script>
    // Variáveis para o controle do carousel
    let currentSlide = 0;
    const images = <?php echo json_encode($imagens); ?>;

    // Função para abrir o modal e exibir a imagem clicada
    function openCarouselModal(index) {
        currentSlide = index;
        document.getElementById('carouselImage').src = images[currentSlide];
        document.getElementById('carouselModal').classList.remove('hidden');
    }

    // Função para fechar o modal
    function closeCarouselModal() {
        document.getElementById('carouselModal').classList.add('hidden');
    }

    // Função para navegar entre as imagens do carousel
    function changeSlide(direction) {
        currentSlide += direction;
        if (currentSlide < 0) currentSlide = images.length - 1;
        if (currentSlide >= images.length) currentSlide = 0;
        document.getElementById('carouselImage').src = images[currentSlide];
    }

    // Fechar o modal ao clicar fora da imagem
    window.onclick = function(event) {
        if (event.target == document.getElementById('carouselModal')) {
            closeCarouselModal();
        }
    }

    const stars = document.querySelectorAll('.star');
    const inputEstrelas = document.getElementById('estrelas');

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const value = this.getAttribute('data-value');
            highlightStars(value);
        });

        star.addEventListener('mouseout', function() {
            const value = inputEstrelas.value;
            highlightStars(value);
        });

        star.addEventListener('click', function() {
            inputEstrelas.value = this.getAttribute('data-value');
            highlightStars(inputEstrelas.value);
        });
    });

    function highlightStars(value) {
        stars.forEach(star => {
            if (star.getAttribute('data-value') <= value) {
                star.classList.add('filled');
            } else {
                star.classList.remove('filled');
            }
        });
    }
</script>

<?php
renderFooter();
?>
