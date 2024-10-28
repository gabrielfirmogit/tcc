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

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id_local = $_GET['id'];

// Consulta os detalhes do local
$query = "SELECT l.*, GROUP_CONCAT(DISTINCT i.url) AS imagens 
          FROM locais l
          LEFT JOIN imagens_local i ON l.id = i.id_local 
          WHERE l.id = :id_local
          GROUP BY l.id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_local', $id_local);
$stmt->execute();
$local = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$local) {
    header('Location: index.php');
    exit;
}

// Processa atualização dos dados básicos
if (isset($_POST['update_dados'])) {
    $query = "UPDATE locais SET 
                nome = :nome, 
                descricao = :descricao, 
                preco = :preco, 
                endereco = :endereco, 
                tipo_local = :tipo_local 
              WHERE id = :id_local";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'nome' => $_POST['nome'],
        'descricao' => $_POST['descricao'],
        'preco' => $_POST['preco'],
        'endereco' => $_POST['endereco'],
        'tipo_local' => $_POST['tipo_local'],
        'id_local' => $id_local
    ]);

    header("Location: editar_local.php?id=$id_local&status=dados_atualizados");
    exit();
}

// Processa upload de novas imagens
if (isset($_POST['upload_imagens']) && isset($_FILES['imagens'])) {
    $uploadDir = 'uploads/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['imagens']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['imagens']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = uniqid() . '_' . basename($_FILES['imagens']['name'][$key]);
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($tmpName, $filePath)) {
                $query = "INSERT INTO imagens_local (id_local, url) VALUES (:id_local, :url)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'id_local' => $id_local,
                    'url' => $filePath
                ]);
            }
        }
    }

    header("Location: editar_local.php?id=$id_local&status=imagens_adicionadas");
    exit();
}

// Processa exclusão de imagem
if (isset($_POST['delete_image'])) {
    $url = $_POST['delete_image'];
    
    $query = "DELETE FROM imagens_local WHERE id_local = :id_local AND url = :url";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id_local' => $id_local,
        'url' => $url
    ]);

    if (file_exists($url)) {
        unlink($url);
    }

    header("Location: editar_local.php?id=$id_local&status=imagem_deletada");
    exit();
}

$titulo_cabecalho = "Editar Local - " . htmlspecialchars($local['nome']);
renderHead($titulo_cabecalho);
renderNavbar();
?> <div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-6 text-center">Editar Local</h1> <?php if (isset($_GET['status'])): ?> <div
        class="mb-4 p-4 rounded <?php echo $_GET['status'] === 'dados_atualizados' ? 'bg-green-100' : 'bg-blue-100'; ?>"> <?php 
            switch($_GET['status']) {
                case 'dados_atualizados':
                    echo 'Dados atualizados com sucesso!';
                    break;
                case 'imagens_adicionadas':
                    echo 'Novas imagens adicionadas com sucesso!';
                    break;
                case 'imagem_deletada':
                    echo 'Imagem removida com sucesso!';
                    break;
            }
            ?> </div> <?php endif; ?>
    <!-- Formulário para dados básicos -->
    <div class="bg-white  rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Dados Básicos</h2>
        <form method="post" class="space-y-4">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($local['nome']); ?>"
                    required class="mt-1 block w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700">Descrição:</label>
                <textarea id="descricao" name="descricao" required
                    class="mt-1 block w-full p-2 border rounded"><?php echo htmlspecialchars($local['descricao']); ?></textarea>
            </div>
            <div class="mb-4">
    <label for="tipo_local" class="block text-gray-700">Tipo de Local:</label>
    <select id="tipo_local" name="tipo_local" required class="mt-1 block w-full p-2 border rounded">
        <option value="Edicula" <?php echo $local['tipo_local'] === 'Edicula' ? 'selected' : ''; ?>>Edícula</option>
        <option value="Salao" <?php echo $local['tipo_local'] === 'Salao' ? 'selected' : ''; ?>>Salão</option>
        <option value="Chacara" <?php echo $local['tipo_local'] === 'Chacara' ? 'selected' : ''; ?>>Chácara</option>
    </select>
</div>

            <div class="mb-4">
                <label for="preco" class="block text-gray-700">Preço:</label>
                <input type="number" step="0.01" id="preco" name="preco"
                    value="<?php echo htmlspecialchars($local['preco']); ?>" required
                    class="mt-1 block w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="endereco" class="block text-gray-700">Endereço:</label>
                <input type="text" id="endereco" name="endereco"
                    value="<?php echo htmlspecialchars($local['endereco']); ?>" required
                    class="mt-1 block w-full p-2 border rounded">
            </div>
            <input type="hidden" name="update_dados" value="1">
            <button type="submit" class="bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700"> Salvar
                Dados </button>
        </form>
    </div>
    <!-- Formulário para upload de novas imagens -->
    <div class="bg-white  rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold mb-4">Adicionar Novas Imagens</h2>
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div class="mb-4">
                <label for="imagens" class="block text-gray-700">Selecione as imagens:</label>
                <input type="file" id="imagens" name="imagens[]" multiple required
                    class="mt-1 block w-full p-2 border rounded">
            </div>
            <input type="hidden" name="upload_imagens" value="1">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700"> Fazer Upload
            </button>
        </form>
    </div>
    <!-- Exibição das imagens existentes -->
    <div class="bg-white border-b border-x  rounded-t-none mshadow-d rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Imagens Atuais</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"> <?php if ($local['imagens']): ?>
            <?php foreach (explode(',', $local['imagens']) as $imagem): ?> <div class="relative">
                <img src="<?php echo htmlspecialchars($imagem); ?>"
                    alt="<?php echo htmlspecialchars($local['nome']); ?>" class="w-full h-60 object-cover rounded-md">
                <form method="post" class="absolute top-2 right-2">
                    <input type="hidden" name="delete_image" value="<?php echo htmlspecialchars($imagem); ?>">
                    <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded-md hover:bg-red-600"> Excluir
                    </button>
                </form>
            </div> <?php endforeach; ?> <?php else: ?> <p class="text-gray-500">Nenhuma imagem cadastrada.</p>
            <?php endif; ?> </div>
    </div>
</div> <?php renderFooter(); ?>