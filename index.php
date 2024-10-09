<?php
session_start();

function usuarioEstaLogado() {
    if (isset($_SESSION["id_usuario"]) && !empty($_SESSION["id_usuario"])) {
        return true;
    } else {
        return false;
    }
}

if (!usuarioEstaLogado()) {
    header("Location: telacadastro.php");
    exit;
}

// Conectar ao banco de dados
$conn = mysqli_connect("localhost", "seu_usuario", "123", "tcc");

// Verificar se a conexão foi bem-sucedida
if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}

// Consultar o banco de dados para obter o tipo de usuário
$query = "SELECT * FROM usuario WHERE id = '".$_SESSION['id_usuario']."'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Verificar se o usuário é empreendedor
$query = "SELECT * FROM empreendedor WHERE id_usuario = '".$_SESSION['id_usuario']."'";
$result = mysqli_query($conn, $query);
$empreendedor = mysqli_fetch_assoc($result);

if ($empreendedor) {
    $_SESSION['tipo_usuario'] = 'empreendedor';
} else {
    $_SESSION['tipo_usuario'] = 'usuario';
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);

// Verificar se o tipo de usuário está definido
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario'] == 'usuario') {
        // Redirecionar para atualizarcadastro.php
        // header('Location: atualizarcadastro.php');
        // exit;
    } elseif ($_SESSION['tipo_usuario'] == 'empreendedor') {
        // Redirecionar para a tela de anuncio de espaço
        // header('Location: anuncio_espaco.php');
        // exit;
    } else {
        // Tratar erro ou redirecionar para uma página de erro
        echo 'Erro: Tipo de usuário não encontrado';
        exit;
    }
} else {
    // Tratar erro ou redirecionar para uma página de erro
    echo 'Erro: Tipo de usuário não encontrado';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet">
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <style>
        body {
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            height: 800px; /* Ajuste a altura do container conforme necessário */
            overflow-y: auto; /* Para adicionar barra de rolagem caso o conteúdo ultrapasse a altura */
        }

        footer {
            background-color: #4a0072;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            color: #a855f7;
            text-decoration: underline;
        }

        /* Atualização de cores */
        .price-slider input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            background: #c4c4c4; /* cinza claro */
            outline: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .price-slider input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: #8a2be2; /* roxo mais claro */
            border-radius: 50%;
            cursor: pointer;
        }

        .price-slider input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #8a2be2;
            border-radius: 50%;
            cursor: pointer;
        }

        .property-card {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-10px);
        }

        .property-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .property-card .p-4 {
            padding: 1.25rem;
        }

        .property-card h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #4a0072; /* roxo escuro */
        }

        .property-card .text-sm {
            color: #555;
        }

        .property-card .text-red-500 {
            color: #e63946;
            font-weight: bold;
        }

        .property-card .text-yellow-500 {
            color: #ffbf00;
        }

        .property-card .hover\:bg-purple-100:hover {
            background-color: #f3e5f5; /* roxo claro */
        }

        .property-card a {
            background-color: #8a2be2; /* roxo claro */
            color: white;
            padding: 10px;
            text-align: center;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .property-card a:hover {
            background-color: #4a0072; /* roxo escuro */
        }

        .search-bar {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .search-bar input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-right: 8px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #8a2be2; /* roxo claro */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #4a0072; /* roxo escuro */
        }

        .filter-menu {
            display: none;
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 10px;
            padding: 10px;
            width: 200px;
            z-index: 100;
        }

        .filter-menu ul {
            list-style: none;
            padding: 0;
        }

        .filter-menu li {
            padding: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .filter-menu li:hover {
            background-color: #f3e5f5; /* roxo claro */
        }

        .accommodations-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
        }

        .accommodations-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .accommodations-modal-content ul {
            list-style: none;
            padding: 0;
        }

        .accommodations-modal-content li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .accommodations-modal-content li:last-child {
            border-bottom: none;
        }

        .accommodations-modal-content input[type="checkbox"] {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    
    <!-- Navbar -->
<nav class="bg-white shadow-md py-2 mb-1">
    
    <div class="flex items-center justify-between px-">
        <img src="logofestiva.png" alt="Imagem do Local" class="h-52">
        <ul class="flex space-x-6 text-sm">
            <li>
            <a href="#" class="hover:text-red-500" id="accommodationsButton">Acomodações</a>
            </li>
            <?php if (usuarioEstaLogado()) { ?>
            <li><a href="avaliacoes.php" class="hover:text-red-500" id="experiencias-button">Feedback</a></li>
            <?php } else { ?>
            <li><a href="telacadastro.php" class="hover:text-red-500" id="experiencias-button">Feedback</a></li>
            <?php } ?>
            <li>
                <?php if (usuarioEstaLogado()) { ?>
                    <?php if (isset($_SESSION['tipo_usuario'])) { ?>
                        <?php if ($_SESSION['tipo_usuario'] == 'usuario') { ?>
                            <a href="atualizarcadastro.php" class="hover:text-red-500">Anuncie seu espaço</a> <?php } 
                            elseif ($_SESSION['tipo_usuario'] == 'empreendedor')
                             { ?> <a href="anuncioespaco.php" class="hover:text-red-500">Anuncie seu espaço</a> <?php } 
                             else { ?> <a href="#" class="hover:text-red-500">Anuncie seu espaço</a> <?php } ?> <?php } 
                             else { ?> <a href="#" class="hover:text-red-500">Anuncie seu espaço</a> <?php } ?> <?php } 
                             else { ?> <a href="telacadastro.php" class="hover:text-red-500">Anuncie seu espaço</a> <?php } ?> </li> </ul> </div>

</nav>

    <!-- Modal com as acomodações -->
    <div id="accommodationsModal" class="accommodations-modal">
        <div class="accommodations-modal-content">
            <h2 class="text-lg font-bold mb-2">Acomodações</h2>
            <ul>
                <li>
                    <input type="checkbox" id="wifi" name="wifi">
                    <label for="wifi">Wi-Fi</label>
                </li>
                <li>
                    <input type="checkbox" id="piscina" name="piscina">
                    <label for="piscina">Piscina</label>
                </li>
                <li>
                    <input type="checkbox" id="palco" name="palco">
                    <label for="palco">Palco</label>
                </li>
                <li>
                    <input type="checkbox" id="espaco-dj" name="espaco-dj">
                    <label for="espaco-dj">Espaço para DJ</label>
                </li>
                <li>
                    <input type="checkbox" id="churrasqueira" name="churrasqueira">
                    <label for="churrasqueira">Churrasqueira</label>
                </li>
                <li>
                    <input type="checkbox" id="quadra-esportes" name="quadra-esportes">
                    <label for="quadra-esportes">Quadra de esportes</label>
                </li>
                <li>
                    <input type="checkbox" id="sala-jogos" name="sala-jogos">
                    <label for="sala-jogos">Salão de jogos</label>
                </li>
                <li>
                    <input
                    </li>
                <li>
                    <input type="checkbox" id="cafe" name="cafe">
                    <label for="cafe">Café</label>
                </li>
                <li>
                    <input type="checkbox" id="restaurante" name="restaurante">
                    <label for="restaurante">Restaurante</label>
                </li>
                <li>
                    <input type="checkbox" id="bar" name="bar">
                    <label for="bar">Bar</label>
                </li>
            </ul>
            <button id="closeModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Fechar</button>
        </div>
    </div>

    <!-- Barra de Pesquisa com Filtros -->
    <div class="container py-6 px-4 max-w-4xl mx-auto mt-4">   
    
        <div class="search-bar">
            <input type="text" placeholder="Buscar destinos" id="searchInput" aria-label="Buscar destinos">
            <button id="filterButton">Filtros</button>
        </div>

        <!-- Menu suspenso de tipos de locais -->
        <div id="filterMenu" class="filter-menu">
            
            <ul>
                <li class="filter-option" data-filter="chacara">Chácara</li>
                <li class="filter-option" data-filter="edicula">Edícula</li>
                <li class="filter-option" data-filter="salao">Salão de Festas</li>
                <li class="filter-option" data-filter="espaco-gourmet">Espaço Gourmet</li>
                <li class="filter-option" data-filter="hotel">Hotel</li>
                <li class="filter-option" data-filter="apartamento">Apartamento</li>
            </ul>
        </div>

        <!-- Filtro de Preço -->
        <label for="priceRange" class="block font-bold text-purple-700">Faixa de Preço</label>
        <div class="price-slider">
            <input type="range" id="priceRange" min="1" max="2000" value="1000">
            <span id="priceValue">R$ 1000</span>
        </div>

        <!-- Grid de Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="propertyGrid">
            <!-- Local 1 -->
            <div class="property-card" data-type="edicula" data-price="300">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSA4Qk8h6LwiHXkirxnRPLr2XS2rt7yegfHdg&s" alt="Propriedade">
                <div class="p-4">
                    <h2>Edícula da Ana Rita</h2>
                    <p class="text-sm">Leme RJ</p>
                    <p class="text-sm">Data disponível</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-red-500 font-semibold">R$ 300/dia</span>
                        <span class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.604-.921 1.904 0l2.519 7.766h8.145c.968 0 1.367 1.238.638 1.822l-6.596 5.322 2.52 7.765c.299.919-.755 1.688-1.52 1.137l-6.597-5.322-6.596 5.322c-.765.551-1.818-.218-1.519-1.137l2.52-7.765-6.596-5.322c-.73-.584-.33-1.822.638-1.822h8.145l2.52-7.766z" />
                            </svg>
                            4.5
                        </span>
                    </div>
                    <a href="#" class="hover:bg-purple-100">Ver mais</a>
                </div>
            </div>

        </div>
    </div>

    <!-- Rodapé -->
    <footer>
    <p>&copy; 2024 Festiva | <a href="termos.php">Termos de Uso & Política de Privacidade</a>
        </footer>

<script>
    // Script para a funcionalidade de filtros
    const filterButton = document.getElementById('filterButton');
    const filterMenu = document.getElementById('filterMenu');
    const filterOptions = document.querySelectorAll('.filter-option');
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    const searchInput = document.getElementById('searchInput');
    const propertyGrid = document.getElementById('propertyGrid');
    const accommodationsButton = document.getElementById('accommodationsButton');
    const accommodationsModal = document.getElementById('accommodationsModal');
    const closeModal = document.getElementById('closeModal');

    // Mostrar/ocultar menu de filtros
    filterButton.addEventListener('click', () => {
        filterMenu.style.display = filterMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Filtrar as propriedades com base no tipo e preço
    filterOptions.forEach(option => {
        option.addEventListener('click', () => {
            const filterValue = option.getAttribute('data-filter');
            filterProperties(filterValue, priceRange.value);
            filterMenu.style.display = 'none'; // Fechar menu após filtro
        });
    });

    // Atualizar valor do preço
    priceRange.addEventListener('input', () => {
        priceValue.textContent = 'R$ ' + priceRange.value;
        filterProperties(null, priceRange.value); // Atualizar filtragem com base no preço
    });

    // Função de filtragem
    function filterProperties(type, maxPrice) {
        const properties = document.querySelectorAll('.property-card');
        properties.forEach(property => {
            const propertyType = property.getAttribute('data-type');
            const propertyPrice = parseInt(property.getAttribute('data-price'));

            // Exibir ou ocultar propriedade com base nos filtros
            if ((type === null || propertyType === type) && propertyPrice <= maxPrice) {
                property.style.display = 'block';
            } else {
                property.style.display = 'none';
            }
        });
    }

    // Função para buscar por texto
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const properties = document.querySelectorAll('.property-card');

        properties.forEach(property => {
            const title = property.querySelector('h2').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                property.style.display = 'block';
            } else {
                property.style.display = 'none';
            }
        });
    });

    // Mostrar/ocultar modal com acomodações
    accommodationsButton.addEventListener('click', () => {
        accommodationsModal.style.display = 'block';
    });

    // Fechar modal com acomodações
    closeModal.addEventListener('click', () => {
        accommodationsModal.style.display = 'none';
    });
</script>
</body>

</html>