<?php 
session_start(); // Inicie a sessão para acessar as variáveis de sessão

// Conexão com o banco de dados
$servername = "localhost"; // ou seu servidor de banco de dados
$username = "root"; // seu usuário do banco de dados
$password = ""; // sua senha do banco de dados
$dbname = "tcc"; // nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'] ?? null; // Corrigido para pegar o ID da sessão

// Verificar se o ID do usuário está disponível
if (!$id_usuario) {
    echo "<script>alert('Usuário não encontrado.'); window.location.href='index.php';</script>";
    exit; // Interrompe a execução se o ID do usuário não estiver disponível
}

// Obter dados do usuário para preencher o formulário
$sql = "SELECT * FROM usuario WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Verificar se o usuário foi encontrado
if (!$usuario) {
    echo "<script>alert('Usuário não encontrado.'); window.location.href='index.php';</script>";
    exit; // Interrompe a execução se o usuário não for encontrado
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter dados do formulário
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cnpj = $_POST['cnpj']; // Campo para CNPJ
    $senha = $_POST['senha']; // Campo para nova senha

    // Se uma nova senha foi fornecida, atualiza a tabela usuario
    if (!empty($senha)) {
        $sql = "UPDATE usuario SET senha=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", password_hash($senha, PASSWORD_DEFAULT), $id_usuario);
        $stmt->execute();
        $stmt->close();
    }

    // Inserir dados do usuário como empreendedor
    $sql = "INSERT INTO empreendedor (id_usuario, email, telefone, cnpj) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_usuario, $email, $telefone, $cnpj);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro atualizado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "Erro ao atualizar cadastro: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro - Festiva</title>
    <link rel="icon" href="logofestiva.png" type="image/x-icon">
    <style>
        /* Estilos Gerais */
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #490977; /* Roxo */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
            flex-direction: column;
        }
        /* Logo */
        .logo-container {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .logo {
            width: 250px;
            height: auto;
        }
        /* Formulário de Atualização */
        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px 20px;
            box-sizing: border-box;
        }
        .login-container h1 {
            font-size: 2em;
            margin-bottom: 25px;
            color: #4a0072;
            text-align: center;
        }
        /* Campos de Entrada */
        .input-group {
            margin-bottom: 20px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }
        /* Botão de Submissão */
        .button-login {
            width: 100%;
            padding: 15px;
            background-color: #6a0dad;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .button-login:hover {
            background-color: #5e0c8f;
        }
    </style>
</head>
<body>

    <!-- Logo -->
    <div class="logo-container">
            <img src="logofestiva.png" alt="Festiva Logo" class="logo">
        </div>
    </div>

    <!-- Formulário de Atualização -->
    <div class="login-container">
        <h1>Atualizar Cadastro</h1>
        <form method="POST" action="atualizarcadastro.php">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone']); ?>" required>
            </div>
            <div class="input-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" id="cnpj" name="cnpj" required>
            </div>
            <div class="input-group">
                <label for="senha">Nova Senha:</label>
                <input type="password" id="senha" name="senha">
                <small>Deixe em branco se não quiser mudar.</small>
            </div>
            <button type="submit" class="button-login">Atualizar Cadastro</button>
        </form>
    </div>

</body>
</html>
