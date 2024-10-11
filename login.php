<?php
session_start();
require 'conexao.php'; // Importa a conexão com o banco de dados
require 'componentes/cabecalho.php'; // Inclui o cabeçalho
require 'componentes/footer.php'; // Inclui o rodapé
// Redireciona para o index se o usuário já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$titulo_cabecalho = "Login"; // Define o título específico para esta página
renderHead($titulo_cabecalho); // Chama a função para renderizar o cabeçalho
?>

<body class="bg-gray-100  min-h-screen flex flex-col">
    <div class="flex flex-1 justify-center items-center">
        <div class="container h-fit  w-full max-w-sm p-6 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-center mb-4">Login</h1>
            <form method="POST" action="php/processaLogin.php">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border rounded"
                        placeholder="Seu email">
                </div>
                <div class="mb-4">
                    <label for="senha" class="block text-gray-700">Senha</label>
                    <input type="password" id="senha" name="senha" required class="mt-1 block w-full p-2 border rounded"
                        placeholder="Sua senha">
                </div>
                <button type="submit" class="w-full bg-purple-500 text-white py-2 rounded">Entrar</button>
            </form>
        </div>
    </div> <?php renderFooter(); // Inclui o rodapé ?>
</body>

</html>