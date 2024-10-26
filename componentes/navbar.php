<?php
function renderNavbar()
{
    ?> <nav class="bg-white shadow-md py-2 mb-1">
    <div class="flex items-center justify-between px-4"> <a href="./index.php"> <img src="logofestiva.png"
                alt="Imagem do Local" class="h-16"></a>
        <ul class="flex space-x-6 text-sm">
            <li>
                <a href="<?php echo isset($_SESSION['id_usuario']) ? 'termos.php' : 'termos.php'; ?>"
                    class="hover:text-purple-800 hover:underline" id="experiencias-button"> sobre nós </a>
            </li> <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo_usuario'] == 'empreendedor'): ?> <li>
                <a href="cadastro_local.php" class="hover:text-purple-800 hover:underline"> Cadastrar Espaço </a>
            </li> <?php endif; ?>
        </ul>
    </div>
</nav> <?php
}
?>