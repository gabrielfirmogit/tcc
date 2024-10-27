<?php

function renderNavbar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
    ?> 
    <nav class="bg-white shadow-md py-2 mb-1">
        <div class="flex items-center justify-between px-4">
            <a href="./index.php">
                <img src="logofestiva.png" alt="Imagem do Local" class="h-16">
            </a>
            <ul class="flex space-x-6 text-lg items-center justify-center"> <!-- Aumentei o tamanho da fonte -->
                <li>
                    <a href="<?php echo isset($_SESSION['id_usuario']) ? 'termos.php' : 'termos.php'; ?>"
                       class="hover:text-purple-800 hover:underline"></a>
                    <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo_usuario'] == 'empreendedor'): ?>
                    <li>
                        <a href="cadastro_local.php" class="hover:text-purple-800 hover:underline flex items-center">
                            <!-- Ícone de Adição -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M18 20v-3h-3v-2h3v-3h2v3h3v2h-3v3zM3 21q-.825 0-1.412-.587T1 19V5q0-.825.588-1.412T3 3h14q.825 0 1.413.588T19 5v5h-2V8H3v11h13v2z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="dashboard.php" class="hover:text-purple-800 hover:underline flex items-center">
                            <!-- Ícone de Editar -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M9 15v-4.25l9.175-9.175q.3-.3.675-.45t.75-.15q.4 0 .763.15t.662.45L22.425 3q.275.3.425.663T23 4.4t-.137.738t-.438.662L13.25 15zm10.6-9.2l1.425-1.4l-1.4-1.4L18.2 4.4zM5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h8.925L7 9.925V17h7.05L21 10.05V19q0 .825-.587 1.413T19 21z"/>
                            </svg>
                        </a>
                    </li> 
                    <?php endif; ?>
                    <li>
                        <form action="" method="POST" style="display:inline;">
                            <button type="submit" style="text_decoration:none;" name="logout" class="hover:text-purple-800 hover:underline text-lg">Sair</button> <!-- Aumentei o tamanho do botão -->
                        </form>
                    </li>
                </li>
            </ul>
        </div>
    </nav> 
    <?php
}
?>