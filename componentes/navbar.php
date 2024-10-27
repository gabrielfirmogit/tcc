<?php

function renderNavbar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    // Mensagens de depuração


    ?> <nav class="bg-white shadow-md py-2 mb-1">
    <div class="flex items-center justify-between px-4">
        <a href="./index.php">
            <img src="logofestiva.png" alt="Imagem do Local" class="h-16">
        </a>
        <ul class="flex space-x-6 text-sm">
            <li>
                <a href="<?php echo isset($_SESSION['id_usuario']) ? 'termos.php' : 'termos.php'; ?>"
                    class="hover:text-purple-800 hover:underline" id="experiencias-button">Sobre nós</a>
                <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo_usuario'] == 'empreendedor'): ?>
            <li>
                <a href="cadastro_local.php" class="hover:text-purple-800 hover:underline">Cadastrar Espaço</a>
            </li>
            <li>
                <a href="dashboard.php" class="hover:text-purple-800 hover:underline">Dashboard</a>
            </li> <?php endif; ?> <li>
                <form action="" method="POST" style="display:inline;">
                    <button type="submit" name="logout" class="hover:text-purple-800 hover:underline">Sair</button>
                </form>
            </li>
        </ul>
    </div>
    </div>
</nav> <?php
}
?>