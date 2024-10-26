<?php
// Incluir conexão com o banco
include 'conexao.php';

// Criar uma nova listagem de propriedade
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $titulo = $_POST["titulo"];
    $descricao = $_POST["descricao"];
    $localizacao = $_POST["localizacao"];
    $preco = $_POST["preco"];
    $id_agente = $_POST["id_agente"];
    $id_bairro = $_POST["id_bairro"];

    // Inserir na tabela propriedades
    $sql = "INSERT INTO propriedades (titulo, descricao, localizacao, preco, id_agente) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssd", $titulo, $descricao, $localizacao, $preco, $id_agente);
    $stmt->execute();

    $id_propriedade = $conn->insert_id;

    // Inserir na tabela propriedades_bairros
    $sql = "INSERT INTO propriedades_bairros (id_propriedade, id_bairro) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_propriedade, $id_bairro);
    $stmt->execute();

    echo "Listagem de propriedade criada com sucesso!";
}

// Exibir todas as listagens de propriedades
$sql = "SELECT * FROM propriedades";
$result = $conn->query($sql);

if ($result->num_rows > 0)
{
    while ($row = $result->fetch_assoc())
    {
        echo "Título: " . $row["titulo"] . "<br>";
        echo "Descrição: " . $row["descricao"] . "<br>";
        echo "Localização: " . $row["localizacao"] . "<br>";
        echo "Preço: " . $row["preco"] . "<br>";
        echo "Agente: " . $row["id_agente"] . "<br>";

        $sql = "SELECT * FROM propriedades_bairros WHERE id_propriedade = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $row["id"]);
        $stmt->execute();
        $bairro_result = $stmt->get_result();

        if ($bairro_result->num_rows > 0)
        {
            while ($bairro_row = $bairro_result->fetch_assoc())
            {
                $sql = "SELECT * FROM bairros WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $bairro_row["id_bairro"]);
                $stmt->execute();
                $bairro_nome = $stmt->get_result()->fetch_assoc()["nome"];

                echo "Bairro: " . $bairro_nome . "<br><br>";
            }
        }
    }
}
else
{
    echo "0 resultados";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Festiva</title>
    <link rel="icon" href="logofestiva.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <div class="text-center mb-6">
            <img src="logofestiva.png" alt="Logo" class="w-24 mx-auto">
        </div>
        <form action="" method="post" class="space-y-4">
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título:</label>
                <input type="text" id="titulo" name="titulo"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição:</label>
                <textarea id="descricao" name="descricao"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <div>
                <label for="localizacao" class="block text-sm font-medium text-gray-700">Localização:</label>
                <input type="text" id="localizacao" name="localizacao"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço:</label>
                <input type="number" id="preco" name="preco"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="id_agente" class="block text-sm font-medium text-gray-700">ID do Agente:</label>
                <input type="number" id="id_agente" name="id_agente"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="id_bairro" class="block text-sm font-medium text-gray-700">ID do Bairro:</label>
                <input type="number" id="id_bairro" name="id_bairro"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="text-center">
                <input type="submit" value="Inserir"
                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
            </div>
        </form>
    </div> <?php
    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>
</body>

</html>