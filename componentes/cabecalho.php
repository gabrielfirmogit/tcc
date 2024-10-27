<?php
function renderHead($titulo_cabecalho = "Gerenciamento de Eventos")
{
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($titulo_cabecalho); ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <!-- Lightbox JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
        <!-- Lightbox CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    </head>
    
    <style>
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .star {
            font-size: 2rem; /* Tamanho das estrelas */
            cursor: pointer;
            color: #ccc; /* Cor das estrelas vazias */
        }

        .star.filled {
            color: #ffcc00; /* Cor das estrelas cheias */
        }

        .star:hover,
        .star:hover ~ .star {
            color: #ffcc00; /* Cor ao passar o mouse */
        }
    </style>
    <?php
}
?>
