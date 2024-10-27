<?php
require 'componentes/cabecalho.php'; // Inclui o componente do cabeçalho
renderHead('Termos de Uso e Política de Privacidade'); // Renderiza o cabeçalho com o título

require 'componentes/navbar.php'; // Inclui o componente da navbar
renderNavbar(); // Renderiza a navbar
?>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-10 max-w-3xl flex-grow-0 flex-shrink-0"> <!-- Alterado para flex-grow-0 e flex-shrink-0 -->
        <h1 class="text-3xl font-bold text-purple-700 mb-4">Termos de Uso</h1>
        <p class="text-gray-700 mb-6">Estes Termos de Uso ("Termos") estabelecem as condições sob as quais você pode acessar e usar o site Festiva e os serviços oferecidos por nós. Ao utilizar este site, você concorda em cumprir e estar vinculado a estes Termos. Se você não concorda com alguma parte destes Termos, você não deve acessar ou utilizar o site.</p>
        
        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Modificações</h2>
        <p class="text-gray-700 mb-6">Reservamo-nos o direito de alterar estes Termos a qualquer momento. Quaisquer modificações serão publicadas nesta página e, ao continuar a usar o site, você concorda em estar sujeito aos Termos revisados. Recomendamos que você reveja esta seção periodicamente para se manter atualizado sobre quaisquer mudanças.</p>

        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Política de Privacidade</h2>
        <p class="text-gray-700 mb-6">Estamos comprometidos em proteger a sua privacidade. Coletamos informações pessoais que você nos fornece ao se registrar, fazer uma reserva ou entrar em contato conosco. Isso pode incluir seu nome, endereço de e-mail, número de telefone e outras informações necessárias para fornecer nossos serviços.</p>

        <h3 class="text-xl font-semibold text-purple-700 mb-2">Compartilhamento de Informações</h3>
        <p class="text-gray-700 mb-6">Não compartilhamos suas informações pessoais com terceiros sem o seu consentimento, exceto quando necessário para cumprir a lei ou proteger nossos direitos. Podemos usar prestadores de serviços terceirizados para processar pagamentos e fornecer suporte ao cliente, que também estão obrigados a proteger suas informações.</p>

        <h2 class="text-2xl font-semibold text-purple-700 mb-2">Contato</h2>
        <p class="text-gray-700 mb-6">Se você tiver dúvidas, comentários ou sugestões sobre nossos Termos de Uso ou Política de Privacidade, entre em contato conosco através do e-mail: tccfestiva@gmail.com Estamos aqui para ajudar!</p>
    </div>

    <?php require 'componentes/footer.php'; // Inclui o componente do rodapé ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</html>
