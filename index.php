<?php
//adicione o código de debug
ini_set('display_errors', 1);

session_start();
require 'conexao.php'; // Importa a conexão com o banco de dados
require 'componentes/cabecalho.php'; // Inclui o arquivo do cabeçalho
require 'componentes/navbar.php'; // Inclui o arquivo da navbar
require 'componentes/footer.php'; // Inclui o arquivo do rodapé

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para a página de login
    exit;
}

// Consultar o banco de dados para obter os dados do usuário
$query = "SELECT * FROM usuario WHERE id = :id_usuario";
$stmt = $pdo->prepare($query);
$stmt->execute(['id_usuario' => $_SESSION['id_usuario']]);
$row = $stmt->fetch();

// Verifica se o usuário existe
if (!$row) {
    echo 'Usuário não encontrado';
    exit;
}

// Armazena o tipo de usuário na sessão
if (isset($row['tipo'])) {
    $_SESSION['tipo_usuario'] = $row['tipo']; // Ajuste conforme o nome da coluna que contém o tipo de usuário
} else {
    echo 'Tipo de usuário não encontrado';
    exit;
}

// Defina o título específico para esta página
$titulo_cabecalho = "Página Principal"; 
renderHead($titulo_cabecalho); // Chama a função para renderizar o cabeçalho
renderNavbar(); // Chama a função para renderizar a navbar

// Redireciona com base no tipo de usuário
if (isset($_SESSION['tipo_usuario'])) {
    switch ($_SESSION['tipo_usuario']) {
        case 'usuario':
            // Se quiser redirecionar, você pode comentar a linha de redirecionamento
            // header('Location: atualizarcadastro.php');
            // exit;
            break; // Continue para o restante do código
        case 'empreendedor':
            // header('Location: anuncio_espaco.php'); 
            // exit;
            break; // Continue para o restante do código
        default:
            echo 'Erro: Tipo de usuário não encontrado';
            exit; // Aqui ainda está correto
    }
}
?> <div id="accommodationsModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-10">
    <div class="bg-white rounded-lg shadow-md max-w-lg mx-auto mt-20 p-6">
        <h2 class="text-lg font-bold mb-2">Acomodações</h2>
        <ul>
            <li><input type="checkbox" id="wifi" name="wifi"><label for="wifi">Wi-Fi</label></li>
            <li><input type="checkbox" id="piscina" name="piscina"><label for="piscina">Piscina</label></li>
            <li><input type="checkbox" id="palco" name="palco"><label for="palco">Palco</label></li>
            <li><input type="checkbox" id="espaco-dj" name="espaco-dj"><label for="espaco-dj">Espaço para DJ</label>
            </li>
            <li><input type="checkbox" id="churrasqueira" name="churrasqueira"><label
                    for="churrasqueira">Churrasqueira</label></li>
            <li><input type="checkbox" id="quadra-esportes" name="quadra-esportes"><label for="quadra-esportes">Quadra
                    de esportes</label></li>
            <li><input type="checkbox" id="sala-jogos" name="sala-jogos"><label for="sala-jogos">Salão de jogos</label>
            </li>
            <li><input type="checkbox" id="cafe" name="cafe"><label for="cafe">Café</label></li>
            <li><input type="checkbox" id="restaurante" name="restaurante"><label for="restaurante">Restaurante</label>
            </li>
            <li><input type="checkbox" id="bar" name="bar"><label for="bar">Bar</label></li>
        </ul>
        <button id="closeModal"
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-4">Fechar</button>
    </div>
</div>
<!-- Barra de Pesquisa com Filtros -->
<div class="container py-6 px-4 max-w-4xl mx-auto mt-4 min-h-screen">
    <div class="flex mb-4">
        <input type="text" placeholder="Buscar destinos" id="searchInput" aria-label="Buscar destinos"
            class="flex-grow p-2 border-2 border-gray-300 rounded-lg mr-2">
        <button id="filterButton" class="bg-purple-500 text-white py-2 px-4 rounded-lg">Filtros</button>
        <button class="ml-2 bg-purple-500 text-white py-2 px-4 rounded-lg"><a href="#"
                id="accommodationsButton">Acomodações</a></button>
    </div>
    <div id="filterMenu" class="absolute bg-white shadow-md rounded-lg hidden z-10">
        <ul class="list-none p-2">
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="chacara">Chácara</li>
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="edicula">Edícula</li>
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="salao">Salão de Festas</li>
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="espaco-gourmet">Espaço
                Gourmet</li>
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="hotel">Hotel</li>
            <li class="filter-option cursor-pointer py-1 hover:bg-purple-100" data-filter="apartamento">Apartamento</li>
        </ul>
    </div>
    <label for="priceRange" class="block font-bold text-purple-700">Faixa de Preço</label>
    <div class="mt-2">
        <input type="range" id="priceRange" min="1" max="2000" value="1000" class="w-full">
        <span id="priceValue">R$ 1000</span>
    </div>
    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4 z-0" id="propertyGrid">
        <!-- Local 1 -->
        <div
            class="property-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSA4Qk8h6LwiHXkirxnRPLr2XS2rt7yegfHdg&s"
                alt="Edícula da Ana Rita">
            <div class="p-4">
                <h3 class="text-lg font-bold">Local 1</h3>
                <p class="text-gray-700">Descrição do Local 1.</p>
                <p class="text-purple-600 font-bold">R$ 300</p>
            </div>
        </div>
        <!-- Local 2 -->
        <div
            class="property-card bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSA4Qk8h6LwiHXkirxnRPLr2XS2rt7yegfHdg&s"
                alt="Edícula da Ana Rita">
            <div class="p-4">
                <h3 class="text-lg font-bold">Local 2</h3>
                <p class="text-gray-700">Descrição do Local 2.</p>
                <p class="text-purple-600 font-bold">R$ 500</p>
            </div>
        </div>
        <!-- Adicione mais locais conforme necessário -->
    </div>
</div> <?php renderFooter(); // Chama a função para renderizar o rodapé ?> <script>
// Exibir e esconder o modal de acomodações
const accommodationsButton = document.getElementById("accommodationsButton");
const accommodationsModal = document.getElementById("accommodationsModal");
const closeModal = document.getElementById("closeModal");
accommodationsButton.onclick = function() {
    accommodationsModal.classList.remove("hidden");
};
closeModal.onclick = function() {
    accommodationsModal.classList.add("hidden");
};
// Exibir/ocultar menu de filtros
const filterButton = document.getElementById("filterButton");
const filterMenu = document.getElementById("filterMenu");
filterButton.onclick = function() {
    filterMenu.classList.toggle("hidden");
};
// Atualizar o valor do filtro de preço
const priceRange = document.getElementById("priceRange");
const priceValue = document.getElementById("priceValue");
priceRange.oninput = function() {
    priceValue.textContent = "R$ " + this.value;
};
// Adicionar lógica para os filtros
const propertyCards = document.querySelectorAll(".property-card");
const filterOptions = document.querySelectorAll