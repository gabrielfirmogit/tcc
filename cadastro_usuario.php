<?php
session_start();
require 'conexao.php'; // Importa a conexão com o banco de dados
require 'componentes/cabecalho.php'; // Inclui o arquivo do cabeçalho
require 'componentes/footer.php';
$titulo_cabecalho = "Cadastro de Usuário"; // Defina o título específico para esta página
renderHead($titulo_cabecalho); // Chama a função para renderizar o cabeçalho
?>

<body class="bg-gray-100 h-full min-h-screen flex flex-col">
    <div class="flex-grow flex items-center justify-center">
        <div class="container w-full max-w-md p-6 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-center mb-4">Cadastro de Usuário</h1>
            <div class="tabs flex justify-center mb-4">
                <button id="tabCliente"
                    class="tab-button border-b-2 border-transparent hover:border-purple-500 focus:outline-none p-2">Cliente</button>
                <button id="tabEmpreendedor"
                    class="tab-button border-b-2 border-transparent hover:border-purple-500 focus:outline-none p-2">Empreendedor</button>
            </div>
            <form id="formCliente" method="POST" action="php/cadUsuario.php">
                <input type="hidden" name="tipo_usuario" value="usuario">
                <div class="mb-4">
                    <label for="emailCliente" class="block text-gray-700">Email</label>
                    <input type="email" id="emailCliente" name="email" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="Seu email">
                </div>
                <div class="mb-4">
                    <label for="senhaCliente" class="block text-gray-700">Senha</label>
                    <input type="password" id="senhaCliente" name="senha" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="Sua senha">
                </div>
                <div class="mb-4">
                    <label for="telefoneCliente" class="block text-gray-700">Telefone</label>
                    <input type="tel" id="telefoneCliente" name="telefone" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="(99) 99999-9999" maxlength="15"
                        oninput="mascaraTelefone(this)">
                </div>
                <button type="submit" class="w-full bg-purple-500 text-white py-2 rounded">Cadastrar Cliente</button>
            </form>
            <form id="formEmpreendedor" method="POST" action="php/cadUsuario.php">
                <input type="hidden" name="tipo_usuario" value="empreendedor">
                <div class="mb-4">
                    <label for="emailEmpreendedor" class="block text-gray-700">Email</label>
                    <input type="email" id="emailEmpreendedor" name="email" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="Seu email">
                </div>
                <div class="mb-4">
                    <label for="senhaEmpreendedor" class="block text-gray-700">Senha</label>
                    <input type="password" id="senhaEmpreendedor" name="senha" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="Sua senha">
                </div>
                <div class="mb-4">
                    <label for="telefoneEmpreendedor" class="block text-gray-700">Telefone</label>
                    <input type="tel" id="telefoneEmpreendedor" name="telefone" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="(99) 99999-9999" maxlength="15"
                        oninput="mascaraTelefone(this)">
                </div>
                <div class="mb-4">
                    <label for="cnpjEmpreendedor" class="block text-gray-700">CNPJ</label>
                    <input type="text" id="cnpjEmpreendedor" name="cnpj" required
                        class="mt-1 block w-full p-2 border rounded" placeholder="99.999.999/9999-99" maxlength="18"
                        oninput="mascaraCNPJ(this)">
                </div>
                <button type="submit" class="w-full bg-purple-500 text-white py-2 rounded">Cadastrar
                    Empreendedor</button>
            </form>
        </div>
    </div> <?php renderFooter(); // Inclui o arquivo do rodapé ?> <script>
    const tabCliente = document.getElementById("tabCliente");
    const tabEmpreendedor = document.getElementById("tabEmpreendedor");
    const formCliente = document.getElementById("formCliente");
    const formEmpreendedor = document.getElementById("formEmpreendedor");
    tabCliente.onclick = function() {
        formCliente.classList.remove("hidden");
        formEmpreendedor.classList.add("hidden");
        tabCliente.classList.add("border-purple-500");
        tabEmpreendedor.classList.remove("border-purple-500");
    };
    tabEmpreendedor.onclick = function() {
        formEmpreendedor.classList.remove("hidden");
        formCliente.classList.add("hidden");
        tabEmpreendedor.classList.add("border-purple-500");
        tabCliente.classList.remove("border-purple-500");
    };
    // Ativar o tab de Cliente por padrão
    tabCliente.click();
    // Máscaras para telefone e CNPJ
    function mascaraTelefone(telefone) {
        telefone.value = telefone.value.replace(/\D/g, '') // Remove caracteres não numéricos
            .replace(/^(\d{2})(\d)/g, '($1) $2') // Adiciona parênteses
            .replace(/(\d)(\d{4})$/, '$1-$2') // Adiciona o traço
            .replace(/(\d{5})(\d)/, '$1-$2') // Adiciona o traço para telefone com 9 dígitos
    }

    function mascaraCNPJ(cnpj) {
        cnpj.value = cnpj.value.replace(/\D/g, '') // Remove caracteres não numéricos
            .replace(/^(\d{2})(\d)/, '$1.$2') // Adiciona ponto
            .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3') // Adiciona ponto
            .replace(/\.(\d{3})(\d)/, '.$1/$2') // Adiciona barra
            .replace(/(\d{4})(\d)/, '$1-$2') // Adiciona o traço
    }
    </script>
</body>