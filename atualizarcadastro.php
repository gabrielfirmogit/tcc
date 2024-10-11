<?php 
session_start();
require 'conexao.php'; // Inclui o arquivo de conexão

// Obter ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_usuario) {
    echo "<script>alert('Usuário não encontrado.'); window.location.href='index.php';</script>";
    exit;
}

// Obter dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id=?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "<script>alert('Usuário não encontrado.'); window.location.href='index.php';</script>";
    exit;
}

// Processar o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cnpj = $_POST['cnpj'];
    $senha = $_POST['senha'];

    // Atualizar senha, se fornecida
    if (!empty($senha)) {
        $stmt = $pdo->prepare("UPDATE usuario SET senha=? WHERE id=?");
        $stmt->execute([password_hash($senha, PASSWORD_DEFAULT), $id_usuario]);
    }

    // Inserir dados de empreendedor
    $stmt = $pdo->prepare("INSERT INTO empreendedor (id_usuario, email, telefone, cnpj) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$id_usuario, $email, $telefone, $cnpj])) {
        echo "<script>alert('Cadastro atualizado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "Erro ao atualizar cadastro.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro - Festiva</title>
    <link rel="icon" href="logofestiva.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-900 flex justify-center items-center min-h-screen">
    <!-- Logo -->
    <div class="absolute top-0 left-0 bg-white w-full">
        <img src="logofestiva.png" alt="Festiva Logo" class="w-32">
    </div>

    <!-- Formulário de Atualização -->
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold text-purple-800 text-center mb-6">Atualizar Cadastro</h1>
        <form method="POST" action="atualizarcadastro.php">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="mb-4">
                <label for="telefone" class="block text-gray-700 font-semibold mb-2">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="mb-4">
                <label for="cnpj" class="block text-gray-700 font-semibold mb-2">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>

            <div class="mb-4">
                <label for="senha" class="block text-gray-700 font-semibold mb-2">Nova Senha:</label>
                <input type="password" id="senha" name="senha" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
                <small class="text-gray-500">Deixe em branco se não quiser mudar.</small>
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">Atualizar Cadastro</button>
        </form>
    </div>
</body>
</html>
