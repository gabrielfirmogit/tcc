<?php
function renderNavbar()
{
?>
<nav class="border-gray-200 bg-white shadow-md">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="./index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
    <img src="logofestiva.png" style="max-height: 100px; width: auto;" alt="Imagem do Local" /> <!-- Aumente a altura aqui -->
    </a>
    <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
      <ul class="flex flex-col font-medium mt-4 rounded-lg bg-white md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0">
        <li>
          <a href="index.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700" aria-current="page">Home</a>
        </li>
        <li>
          <a href="<?php echo isset($_SESSION['id_usuario']) ? 'termos.php' : 'termos.php'; ?>" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Sobre Nós</a>
        </li>
        <?php if (isset($_SESSION['id_usuario']) && $_SESSION['tipo_usuario'] == 'empreendedor'): ?>
        <li>
          <a href="cadastro_local.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700">Cadastrar Espaço</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<?php
}
?>
