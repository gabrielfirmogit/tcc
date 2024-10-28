<?php
ini_set('display_errors', 1);
session_start();

$titulo_cabecalho = "Cadastrar Local";
require 'conexao.php';
require 'componentes/cabecalho.php';
require 'componentes/navbar.php'; 
require 'php/processar_cadastro_local.php';

// Verifica se o usuário está logado e é empreendedor
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'empreendedor') {
    header('Location: login.php');
    exit();
}

renderHead($titulo_cabecalho);
renderNavbar();
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Cadastrar Novo Local</h1>
    <form method="POST" action="" enctype="multipart/form-data" class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <?php if (isset($erro)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>
        <div class="space-y-4">
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Local</label>
                <input type="text" id="nome" name="nome" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div>
                <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
                <input type="text" id="endereco" name="endereco" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
            </div>
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
            </div>
            <div>
    <label for="tipo_local" class="block text-sm font-medium text-gray-700">Tipo de Local</label>
    <select id="tipo_local" name="tipo_local" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
        <option value="" disabled selected>Selecione um tipo</option>
        <option value="Edicula">Edícula</option>
        <option value="Salao">Salão</option>
        <option value="Chacara">Chácara</option>
    </select>
</div>

            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço da Diária</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">R$</span>
                    </div>
                    <input type="number" step="0.01" min="0" id="preco" name="preco" required
                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                </div>
            </div>
            <div>
                <label for="imagens" class="block text-sm font-medium text-gray-700">Imagens do Local</label>
                <input type="file" id="imagens" name="imagens[]" multiple accept="image/*" required
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                <p class="mt-1 text-sm text-gray-500">Selecione uma ou mais imagens do local</p>
            </div>
            <div class="pt-4">
                <button type="submit" name="cadastrar"
                    class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    Cadastrar Local
                </button>
            </div>
        </div>
    </form>
</div>
<?php
?>
