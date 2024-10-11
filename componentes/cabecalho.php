<?php
function renderHead($titulo_cabecalho = "Gerenciamento de Eventos") {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($titulo_cabecalho); ?></title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    
    </head>
    
    <?php
}
?>
