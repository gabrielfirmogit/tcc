<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "tcc");

// Verificar conexão
if ($conn->connect_error) {
  die("Conexão falhou: " . $conn->connect_error);
}

// Criar uma nova listagem de propriedade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $titulo = $_POST["titulo"];
  $descricao = $_POST["descricao"];
  $localizacao = $_POST["localizacao"];
  $preco = $_POST["preco"];
  $id_agente = $_POST["id_agente"];
  $id_bairro = $_POST["id_bairro"];

  $sql = "INSERT INTO propriedades (titulo, descricao, localizacao, preco, id_agente) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssd", $titulo, $descricao, $localizacao, $preco, $id_agente);
  $stmt->execute();

  $id_propriedade = $conn->insert_id;

  $sql = "INSERT INTO propriedades_bairros (id_propriedade, id_bairro) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $id_propriedade, $id_bairro);
  $stmt->execute();

  echo "Listagem de propriedade criada com sucesso!";
}

// Exibir todas as listagens de propriedades
$sql = "SELECT * FROM propriedades";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "Título: " . $row["titulo"]. "<br>";
    echo "Descrição: " . $row["descricao"]. "<br>";
    echo "Localização: " . $row["localizacao"]. "<br>";
    echo "Preço: " . $row["preco"]. "<br>";
    echo "Agente: " . $row["id_agente"]. "<br>";

    $sql = "SELECT * FROM propriedades_bairros WHERE id_propriedade = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $row["id"]);
    $stmt->execute();
    $bairro_result = $stmt->get_result();

    if ($bairro_result->num_rows > 0) {
      while($bairro_row = $bairro_result->fetch_assoc()) {
        $sql = "SELECT * FROM bairros WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bairro_row["id_bairro"]);
        $stmt->execute();
        $bairro_nome = $stmt->get_result()->fetch_assoc()["nome"];

        echo "Bairro: " . $bairro_nome . "<br><br>";
      }
    }
  }
} else {
  echo "0 resultados";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Festiva </title>
    <link rel="icon" href="logofestiva.png" type="image/x-icon">
    <style>
        body {
          font-family: Arial, sans-serif;
          background-color: #f0f0f0;
        }

        form {
          width: 50%;
          margin: 40px auto;
          padding: 20px;
          background-color: #fff;
          border: 1px solid #ddd;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
          display: block;
          margin-bottom: 10px;
        }

        input, textarea {
          width: 100%;
          padding: 10px;
          margin-bottom: 20px;
          border: 1px solid #ccc;
          border-radius: 5px;
        }

        input[type="submit"] {
          background-color: #4CAF50;
          color: #fff;
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }

        input[type="submit"]:hover {
          background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <form action="" method="post">
      <label for="titulo">Título:</label>
      <input type="text" id="titulo" name="titulo"><br><br <label for="descricao">Descrição:</label>
      <textarea id="descricao" name="descricao"></textarea><br><br>
      <label for="localizacao">Localização:</label>
      <input type="text" id="localizacao" name="localizacao"><br><br>
      <label for="preco">Preço:</label>
      <input type="number" id="preco" name="preco"><br><br>
      <label for="id_agente">ID do Agente:</label>
      <input type="number" id="id_agente" name="id_agente"><br><br>
      <label for="id_bairro">ID do Bairro:</label>
      <input type="number" id="id_bairro" name="id_bairro"><br><br>
      <input type="submit" value="Inserir">
    </form>

    <?php
    $conn->close();
    ?>
</body>
</html> ⬤