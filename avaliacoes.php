<?php
session_start();

// Função para verificar se o usuário está logado
function usuarioEstaLogado() {
    return isset($_SESSION["id_usuario"]) && !empty($_SESSION["id_usuario"]);
}

if (!usuarioEstaLogado()) {
    header("Location: telacadastro.php");
    exit;
}

// Conectar ao banco de dados
$host = '127.0.0.1';
$db = 'tcc'; // Nome do seu banco de dados
$user = 'root'; // Seu usuário do MySQL
$pass = ''; // Sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Recuperar comentários existentes
$stmt = $pdo->query("SELECT * FROM comentarios ORDER BY created_at DESC");
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet">
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
            max-width: 800px;
            margin: 0 auto;
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

        .star {
            font-size: 2rem;
            color: gray; /* Estrelas inativas começam cinzas */
            cursor: pointer;
            transition: color 0.2s;
        }

        .star.selected {
            color: yellow; /* Estrelas ativas ficam amarelas */
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-md py-2 mb-1">
        <div class="flex items-center justify-between px-4">
            <img src="logofestiva.png" alt="Logo" class="h-52">
            <ul class="flex space-x-6 text-sm">
                <li><a href="#" class="hover:text-red-500" id="accommodationsButton">Acomodações</a></li>
                <li><a href="avaliacoes.php" class="hover:text-red-500">Feedback</a></li>
                <li><a href="atualizarcadastro.php" class="hover:text-red-500">Anuncie seu espaço</a></li>
            </ul>
        </div>
    </nav>  

    <div class="container py-6">
        <h1 class="text-2xl font-bold mb-4">Avaliações e Comentários</h1>

        <form id="feedbackForm">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome:</label>
                <input type="text" id="nome" name="nome" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>

            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700">Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Avalie o site:</label>
                <div class="flex space-x-1" id="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                        <input type="radio" name="estrelas" value="<?php echo $i; ?>" id="estrela<?php echo $i; ?>" class="hidden" required>
                        <label for="estrela<?php echo $i; ?>" class="star" data-value="<?php echo $i; ?>">&#9733;</label>
                    <?php } ?>
                </div>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enviar</button>
        </form>

        <div id="comentarios" class="mt-6">
            <h2 class="text-xl font-bold mb-2">Comentários Recentes:</h2>
            <div id="feedbackList">
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="border-b border-gray-300 py-2">
                        <strong><?php echo htmlspecialchars($comentario['nome']); ?></strong> 
                        <span class="star selected"><?php echo str_repeat('★', $comentario['estrelas']) . str_repeat('☆', 5 - $comentario['estrelas']); ?></span>
                        <p><?php echo htmlspecialchars($comentario['feedback']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2024 Festiva | <a href="termos.php">Termos de Uso & Política de Privacidade</a></p>
    </footer>

    <script>
        const stars = document.querySelectorAll('.star');
        const feedbackForm = document.getElementById('feedbackForm');
        const feedbackList = document.getElementById('feedbackList');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                stars.forEach(s => s.classList.remove('selected'));
                star.classList.add('selected');
                let currentStar = star.dataset.value;
                for (let i = 0; i < currentStar; i++) {
                    stars[i].classList.add('selected');
                }
                document.getElementById(`estrela${currentStar}`).checked = true;
            });
        });

        feedbackForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const nome = document.getElementById('nome').value;
            const feedback = document.getElementById('feedback').value;
            const estrelas = document.querySelector('input[name="estrelas"]:checked').value;

            // Salvar no banco de dados via AJAX
            fetch('salvar_comentario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ nome, feedback, estrelas })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newFeedback = document.createElement('div');
                    newFeedback.classList.add('border-b', 'border-gray-300', 'py-2');
                    newFeedback.innerHTML = `<strong>${nome}</strong> <span class="star selected">${'★'.repeat(estrelas)}${'☆'.repeat(5 - estrelas)}</span><p>${feedback}</p>`;
                    feedbackList.prepend(newFeedback);
                    feedbackForm.reset();
                    stars.forEach(s => s.classList.remove('selected')); // Limpa as estrelas
                } else {
                    alert('Erro ao salvar comentário.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
    </script>
</body>

</html>
