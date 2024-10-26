<?php
require 'componentes/cabecalho.php'; // Inclui o componente do cabeçalho
renderHead('Termos de Uso e Política de Privacidade'); // Renderiza o cabeçalho com o título

require 'componentes/navbar.php'; // Inclui o componente da navbar
renderNavbar(); // Renderiza a navbar
?>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="w-full max-w-4xl mx-auto my-10 px-6">
        <!-- Sobre Nós -->
        <h1 class="text-4xl font-bold text-[#9333EA] mb-6 text-center">SOBRE NÓS</h1>
        <p class="text-lg text-gray-600 mb-12 text-center">Nós somos um grupo de estudantes dedicados a facilitar a busca por edículas e salões de festas, criando uma plataforma que une clientes e proprietários de maneira eficiente. Nosso site foi desenvolvido para atender a uma demanda crescente por uma solução prática e organizada na busca por locais para eventos, eliminando a necessidade de pesquisas manuais em marketplaces ou ligações telefônicas.</p>
        
        <!-- Objetivo do Nosso Site -->
        <div class="flex items-start mb-12">
            <div class="w-1/2 pr-6">
                <h2 class="text-3xl font-bold text-[#9333EA] mb-4 uppercase">OBJETIVO DO NOSSO SITE</h2>
                <p class="text-lg text-gray-600">O principal objetivo do nosso site é otimizar a busca por espaços para eventos, oferecendo uma plataforma que integra diversos recursos para simplificar a experiência do usuário. Através de filtros de preço, tipo de local e uma barra de busca por endereço, nossos usuários podem encontrar rapidamente o espaço ideal para suas necessidades.</p>
            </div>
            <div class="w-1/2">
                <img src="uploads/locais/image1.jpg" alt="Objetivo do Nosso Site" class="w-full h-auto rounded-lg shadow">
            </div>
        </div>
        
        <!-- Benefícios -->
        <div class="flex items-start mb-12">
            <div class="w-1/2">
                <img src="uploads/beneficios/image1.jpg" alt="Benefícios" class="w-full h-auto rounded-lg shadow">
            </div>
            <div class="w-1/2 pl-6">
                <h2 class="text-3xl font-bold text-[#9333EA] mb-4 uppercase">BENEFÍCIOS</h2>
                <p class="text-lg text-gray-600 mb-6"><strong>1. Busca Eficiente:</strong> Com nossa interface intuitiva, os usuários podem encontrar locais para eventos de forma rápida e prática, economizando tempo e evitando a frustração de buscas desorganizadas.</p>
                <p class="text-lg text-gray-600 mb-6"><strong>2. Visibilidade para Proprietários:</strong> Proprietários de edículas e salões de festas podem anunciar seus locais de forma clara e organizada, aumentando sua visibilidade e a chance de fechar negócios.</p>
                <p class="text-lg text-gray-600"><strong>3. Informações Abrangentes:</strong> O site oferece informações essenciais como fotos, endereços, preços estimados e avaliações de outros usuários, proporcionando uma visão completa dos espaços disponíveis e ajudando na tomada de decisão.</p>
            </div>
        </div>
    </div>

    <?php require 'componentes/footer.php'; // Inclui o componente do rodapé ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</html>
