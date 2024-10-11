<?php
require 'componentes/cabecalho.php'; // Inclui o componente do cabeçalho
renderHead('Termos de Uso e Política de Privacidade'); // Renderiza o cabeçalho com o título

require 'componentes/navbar.php'; // Inclui o componente da navbar
renderNavbar(); // Renderiza a navbar
?>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-10 max-w-3xl flex-grow">
        <h1 class="text-3xl font-bold text-purple-700 mb-4">Termos de Uso</h1>
        <p class="text-gray-700 mb-6">Estes termos de uso regulam o uso do site [Seu Site]. Ao acessar ou usar o site, você concorda em cumprir estes termos. Se você não concordar com estes termos, não use o site.</p>
        
        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Uso do Site</h2>
        <p class="text-gray-700 mb-6">O usuário concorda em utilizar o site de forma adequada, respeitando as leis e regulamentações aplicáveis.</p>

        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Política de Privacidade</h2>
        <p class="text-gray-700 mb-6">Estamos comprometidos em proteger a sua privacidade. As informações pessoais que coletamos são utilizadas para fornecer um serviço melhor. Não compartilhamos suas informações com terceiros sem o seu consentimento.</p>

        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Alterações nos Termos</h2>
        <p class="text-gray-700 mb-6">Reservamo-nos o direito de modificar estes termos a qualquer momento. É responsabilidade do usuário verificar periodicamente os termos para estar ciente de quaisquer alterações.</p>
        
        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Contato</h2>
        <p class="text-gray-700 mb-6">Para dúvidas ou sugestões, entre em contato conosco através do e-mail: contato@exemplo.com</p>
    </div>

    <?php require 'componentes/footer.php'; // Inclui o componente do rodapé ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</html>
