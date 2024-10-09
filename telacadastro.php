<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Festiva </title>
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
            top: 0px;
            left: 0px;
        }
        .logo {
            width: 250px;
            height: auto;
        }
        /* Opções de Entrada */
        .options {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }
        .options button {
            flex: 1;
            padding: 10px 20px;
            background-color: #4a0072;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        .options button.active {
            background-color: #5e0c8f;
        }
        /* Formulário de Cadastro */
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
        /* Links */
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links p {
            margin: 5px 0;
            font-size: 1em;
            color: #555;
        }
        .links a {
            color: #6a0dad;
            text-decoration: none;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showForm(role) {
            const cnpjGroup = document.getElementById('cpfCnpjGroup');
            const title = document.getElementById('formTitle');
            const clienteBtn = document.getElementById('clienteBtn');
            const empreendedorBtn = document.getElementById('empreendedorBtn');
            const form = document.getElementById('registrationForm');
            const tipoUsuario = document.getElementById('tipoUsuario');

            if (role === 'Cliente') {
                title.textContent = 'Entrar como Cliente';
                cnpjGroup.style.display = 'none';
                clienteBtn.classList.add('active');
                empreendedorBtn.classList.remove('active');
                tipoUsuario.value = 'Cliente'; // Atualiza o tipo de usuário
                form.action = 'registro.php';
                form.reset();
            } else if (role === 'Empreendedor') {
                title.textContent = 'Entrar como Empreendedor';
                cnpjGroup.style.display = 'block';
                empreendedorBtn.classList.add('active');
                clienteBtn.classList.remove('active');
                tipoUsuario.value = 'Empreendedor'; // Atualiza o tipo de usuário
                form.action = 'registro.php';
                form.reset();
            }
        }

        // Define o botão "Cliente" como ativo ao carregar a página
        window.addEventListener('DOMContentLoaded', () => {
            showForm('Cliente');
        });

        // Função para enviar o formulário via AJAX
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registrationForm');
            
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Impede o comportamento padrão de envio do formulário

                const formData = new FormData(form);
                const actionUrl = form.action;

                fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result); // Mostra o resultado do processamento do formulário
                    window.location.href = 'index.php'; // Redireciona para a tela index.php
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            });
        });
    </script>
</head>
<body>
    <!-- Logo -->
    <div class="logo-container">
        <img src="logofestiva.png" alt="Festiva Logo" class="logo">
    </div>

    <!-- Opções de Entrada -->
    <div class="options">
        <button id="clienteBtn" onclick="showForm('Cliente')">Entrar como Cliente</button>
        <button id="empreendedorBtn" onclick="showForm('Empreendedor')">Entrar como Empreendedor</button>
    </div>

    <!-- Formulário de Cadastro -->
    <div class="login-container">
        <h1 id="formTitle">Cadastro</h1>
        <form id="registrationForm" method="post">
            <input type="hidden" id="tipoUsuario" name="tipo_usuario" value="Cliente"> <!-- Campo oculto -->
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com">
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="********">
            </div>
            <div class="input-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" required placeholder="(99) 99999-9999">
            </div>
            <div class="input-group" id="cpfCnpjGroup" style="display: none;">
                <label for="cnpj">CNPJ</label>
                <input type="text" id="cnpj" name="cpf_cnpj" placeholder="Digite seu CNPJ">
            </div>
            <button type="submit" class="button-login">Cadastrar</button>
        </form>
        <!-- Links -->
        <div class="links">
            <p>Já tem uma conta? <a href="login.html">Entrar</a></p>
        </div>
    </div>
</body>
</html>
