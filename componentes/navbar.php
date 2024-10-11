<?php
function renderNavbar() {
    ?> <nav class="bg-white shadow-md py-2 mb-1">
    <div class="flex items-center justify-between px-4">
        <img src="logofestiva.png" alt="Imagem do Local" class="h-16">
        <ul class="flex space-x-6 text-sm">
            <li>
                <a href="<?php echo isset($_SESSION['id_usuario']) ? 'avaliacoes.php' : 'telacadastro.php'; ?>"
                    class="hover:text-purple-800 hover:underline" id="experiencias-button">Feedback</a>
            </li>
            <li>
                <a href="<?php echo isset($_SESSION['id_usuario']) ? ($_SESSION['tipo_usuario'] == 'usuario' ? 'atualizarcadastro.php' : 'anuncio_espaco.php') : 'telacadastro.php'; ?>"
                    class="hover:text-purple-800 hover:underline">Anuncie seu espaço</a>
            </li>
        </ul>
    </div>
</nav> <?php
}
?>