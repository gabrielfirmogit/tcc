<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar']))
{
    try
    {
        // Validação dos dados
        if (
        empty($_POST['nome']) || empty($_POST['endereco']) ||
        empty($_POST['descricao']) || empty($_POST['preco'])
        )
        {
            throw new Exception('Todos os campos são obrigatórios.');
        }

        // Validação do preço
        if (!is_numeric($_POST['preco']) || $_POST['preco'] < 0)
        {
            throw new Exception('Preço inválido.');
        }

        // Validação das imagens
        if (empty($_FILES['imagens']['name'][0]))
        {
            throw new Exception('É necessário enviar pelo menos uma imagem.');
        }

        // Inicia a transação
        $pdo->beginTransaction();

        // Insere o local
        $stmt = $pdo->prepare("
            INSERT INTO locais (id_usuario, nome, endereco, descricao, preco) 
            VALUES (:id_usuario, :nome, :endereco, :descricao, :preco)
        ");

        $stmt->execute([
            ':id_usuario' => $_SESSION['id_usuario'],
            ':nome' => $_POST['nome'],
            ':endereco' => $_POST['endereco'],
            ':descricao' => $_POST['descricao'],
            ':preco' => $_POST['preco']
        ]);

        $id_local = $pdo->lastInsertId();

        // Processa e salva as imagens
        $diretorio_upload = 'uploads/locais/';
        if (!file_exists($diretorio_upload))
        {
            mkdir($diretorio_upload, 0777, true);
        }

        // Prepara statement para inserção das imagens
        $stmt_imagem = $pdo->prepare("
            INSERT INTO imagens_local (id_local, url) 
            VALUES (:id_local, :url)
        ");

        foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name)
        {
            $nome_arquivo = uniqid() . '_' . $_FILES['imagens']['name'][$key];
            $caminho_arquivo = $diretorio_upload . $nome_arquivo;

            if (move_uploaded_file($tmp_name, $caminho_arquivo))
            {
                $stmt_imagem->execute([
                    ':id_local' => $id_local,
                    ':url' => $caminho_arquivo
                ]);
            }
            else
            {
                throw new Exception('Erro ao fazer upload da imagem.');
            }
        }

        // Confirma a transação
        $pdo->commit();
        $sucesso = "Local cadastrado com sucesso!";

    }
    catch (Exception $e)
    {
        // Desfaz a transação em caso de erro
        if (isset($pdo))
        {
            $pdo->rollBack();
        }
        $erro = $e->getMessage();
    }
}
?>