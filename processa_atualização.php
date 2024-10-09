<?php
// Conexão com o banco de dados (substitua com as suas credenciais)
$conn = new mysqli('localhost', 'usuario', 'senha', 'banco');

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

// Obtém os dados enviados pelo formulário
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografa a senha
$cpf_cnpj = $_POST['cpf_cnpj'];

// Atualiza os dados no banco de dados
$sql = "UPDATE usuarios SET email='$email', telefone='$telefone', senha='$senha', cpf_cnpj='$cpf_cnpj' WHERE id_usuario = [ID DO USUÁRIO]";

// Executa a query e verifica se foi bem-sucedida
if ($conn->query($sql) === TRUE) {
    echo 'Cadastro atualizado com sucesso!';
} else {
    echo 'Erro ao atualizar cadastro: ' . $conn->error;
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
