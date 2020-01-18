<?php include 'Configurations.php';
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParseSessionStorage;
use Parse\ParseGeoPoint;
//session_start();
?>
<!-- header -->
<?php include 'header.php' ?>
<body>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar fixed-top">
        <!-- navbar title -->
        <a id="navbar-brand" class="navbar-brand" href="index.php"><?php echo $WEBSITE_NAME ?></a>
        <!-- title header -->
        <div class="title-header">Termos de serviço e política de privacidade</div>
        <!-- right menu button -->
        <a href="#" id="btn-right-menu" class="btn btn-right-menu" onclick="openSidebar()">&#9776;</a>
    </nav>

    <!-- bottom navbar -->
    <div class="bottom-navbar" id="bottom-navbar">
        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"></a>
        <?php
            $currentUser = ParseUser::getCurrentUser();
            if ($currentUser) { echo '<a href="following.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_following.png" style="width: 44px; margin-left: 20px;"></a>
        <?php
            if ($currentUser) { echo '<a href="notifications.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_notifications.png" style="width: 44px; margin-left: 20px;"></a>
        <?php
            if ($currentUser) { echo '<a href="chats.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_chats.png" style="width: 44px; margin-left: 20px;"></a>
        <?php
            if ($currentUser) { echo '<a href="account.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_account.png" style="width: 44px; margin-left: 20px;"></a>
    </div><!-- ./ bottom navbar -->


    <!-- right sidebar menu -->
    <div id="right-sidebar" class="right-sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>

        <a href="index.php"><img src="assets/images/tab_home.png" style="width: 44px;"> Home</a>
        <?php
            if ($currentUser) { echo '<a href="following.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_following.png" style="width: 44px;"> Following</a>
        <?php
            if ($currentUser) { echo '<a href="notifications.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_notifications.png" style="width: 44px;"> Notifications</a>
        <?php
            if ($currentUser) { echo '<a href="chats.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_chats.png" style="width: 44px;"> Chats</a>
        <?php
            if ($currentUser) { echo '<a href="account.php">';
            } else { echo'<a href="intro.php">'; }
        ?>
        <img src="assets/images/tab_account.png" style="width: 44px;"> Account</a>
    </div>

    <!-- container -->
    <div class="container">

        <h2><center>CONDIÇÕES DE USO</center></h2>
        Bem-vindo ao nosso SFBazaar<br>
         SFBazaar e seus associados fornecem seus serviços a você, sujeito às seguintes condições. Por favor, leia-as atentamente.
        <br><br>

        <strong>COMUNICAÇÕES ELETRÔNICAS</strong><br>
        Quando você visita o SFBazaar ou envia e-mails para nós, está se comunicando eletronicamente. Você concorda em receber nossas comunicações eletronicamente. Nós nos comunicaremos com você por e-mail ou publicando avisos neste site. Você concorda que todos os acordos, avisos, divulgações e outras comunicações que lhe fornecemos satisfazem eletronicamente qualquer requisito legal de que tais comunicações sejam feitas por escrito.<br><br>

        <strong>COPYRIGHT</strong><br>
      Todo o conteúdo incluído neste site, como texto, gráficos, logotipos, ícones de botões, imagens, clipes de áudio, downloads digitais, compilações de dados e software, é de propriedade da SFBazaar ou de seus fornecedores de conteúdo e protegido pelas leis internacionais de direitos autorais. A compilação de todo o conteúdo deste site é de propriedade exclusiva da SFBazaar, com autoria de direitos autorais para esta coleção da SFBazaar e protegida pelas leis internacionais de direitos autorais.<br><br>

        <strong>MARCAS COMERCIAIS</strong><br>
        As marcas registradas e a aparência comercial da SFBazaar não podem ser usadas em conexão com qualquer produto ou serviço que não seja SFBazaar, de qualquer maneira que possa causar confusão entre os clientes ou de qualquer maneira que deprecie ou desacredite o SFBazaar. Todas as outras marcas comerciais não pertencentes à SFBazaar ou suas subsidiárias que aparecem neste site são de propriedade de seus respectivos proprietários, que podem ou não ser afiliados, conectados ou patrocinados pela SFBazaar ou suas subsidiárias.<br><br>

        <strong>LICENÇA E ACESSO AO SITE</strong><br>
        O SFBazaar concede a você uma licença limitada para acessar e fazer uso pessoal deste site e não para fazer o download (exceto o cache de páginas) ou modificá-lo ou qualquer parte dele, exceto com o consentimento expresso por escrito do SFBazaar. Esta licença não inclui qualquer revenda ou uso comercial deste site ou de seu conteúdo: qualquer coleção e uso de qualquer lista de produtos, descrições ou preços: qualquer uso derivado deste site ou de seu conteúdo: qualquer download ou cópia de informações da conta para o benefício de outro comerciante: ou qualquer uso de mineração de dados, robôs ou ferramentas semelhantes de coleta e extração de dados. Este site ou qualquer parte deste site não pode ser reproduzido, duplicado, copiado, vendido, revendido, visitado ou de outro modo explorado para qualquer finalidade comercial sem o consentimento expresso por escrito. Você não pode enquadrar ou utilizar técnicas de enquadramento para incluir qualquer marca comercial, logotipo ou outras informações proprietárias (incluindo imagens, texto, layout da página ou formulário) do SFBazaar e de nossos associados sem o consentimento expresso por escrito. Você não pode usar metatags ou qualquer outro "texto oculto" utilizando o nome ou as marcas comerciais da SFBazaar sem o consentimento expresso por escrito da SFBazaar. Qualquer uso não autorizado encerra a permissão ou licença concedida pelo SFBazaar. Você recebe um direito limitado, revogável e não exclusivo de criar um hiperlink para a página inicial do SFBazaar, desde que o link não represente o SFBazaar, seus associados ou seus produtos ou serviços de maneira falsa, enganosa, depreciativa ou ofensiva. Você não pode usar nenhum logotipo do SFBazaar ou outro gráfico ou marca registrada de propriedade como parte do link sem permissão expressa por escrito.
        <br><br>

        <strong>SUA CONTA DE MEMBRO</strong>
        Se você usa este site, é responsável por manter a confidencialidade de sua conta e senha e por restringir o acesso ao seu computador, e concorda em aceitar a responsabilidade por todas as atividades que ocorram sob sua conta ou senha. Se você tem menos de 18 anos, poderá usar nosso site apenas com o envolvimento de pais ou responsáveis. A SFBazaar e seus associados se reservam o direito de recusar serviço, encerrar contas, remover ou editar conteúdo ou cancelar pedidos a seu exclusivo critério.
        <br><br>

        <strong>REVISÕES, COMENTÁRIOS, EMAILS E OUTROS CONTEÚDOS</strong><br>
        Os visitantes podem postar críticas, comentários e outros conteúdos: e enviar sugestões, idéias, comentários, perguntas ou outras informações, desde que o conteúdo não seja ilegal, obsceno, ameaçador, difamatório, invasivo da privacidade, violador dos direitos de propriedade intelectual, ou prejudicial a terceiros ou censurável e não consiste em ou contém vírus de software, campanha política, solicitação comercial, cartas em cadeia, correspondências em massa ou qualquer forma de "spam". Você não pode usar um endereço de e-mail falso, personificar qualquer pessoa ou entidade ou induzir em erro a origem de um cartão ou outro conteúdo. O SFBazaar se reserva o direito (mas não a obrigação) de remover ou editar esse conteúdo, mas não analisa regularmente o conteúdo publicado. Se você publicar conteúdo ou enviar material, e a menos que indique o contrário, você concede ao SFBazaar e seus associados um direito não exclusivo, isento de royalties, perpétuo, irrevogável e totalmente sublicenciável de usar, reproduzir, modificar, adaptar, adaptar, publicar, traduzir, criar trabalhos derivados, distribuem e exibem esse conteúdo em todo o mundo em qualquer mídia. Você concede ao SFBazaar e seus associados e sublicenciados o direito de usar o nome que enviar em conexão com esse conteúdo, se assim o escolherem. Você declara e garante que possui ou controla todos os direitos sobre o conteúdo que publica: que o conteúdo é preciso: que o uso do conteúdo fornecido não viola esta política e não causa danos a qualquer pessoa ou entidade: e que você indenizará a SFBazaar ou seus associados por todas as reivindicações resultantes do conteúdo fornecido por você. APP_NAME tem o direito, mas não a obrigação, de monitorar e editar ou remover qualquer atividade ou conteúdo. O SFBazaar não assume nenhuma responsabilidade e não se responsabiliza por qualquer conteúdo postado por você ou por terceiros.
        <br><br>

        <strong>RISCO DE PERDA</strong><br>
        Todos os itens comprados da SFBazaar são feitos de acordo com um contrato de remessa. Isso basicamente significa que o risco de perda e título de tais itens passam para você após a entrega à transportadora.
        <br><br>

        <strong>DESCRIÇÕES DO PRODUTO</strong><br>
        O SFBazaar e seus associados tentam ser o mais preciso possível. No entanto, o SFBazaar não garante que as descrições dos produtos ou outro conteúdo deste site sejam precisos, completos, confiáveis, atuais ou livres de erros. Se um produto oferecido pelo SFBazaar em si não for o descrito, sua única opção é devolvê-lo em condições não utilizadas
        <br><br>

        AVISO LEGAL DE GARANTIAS E LIMITAÇÃO DE RESPONSABILIDADE ESTE SITE É FORNECIDO PELO SFBazar COM BASE "TAL COMO ESTÁ" E "TÃO DISPONÍVEL". A SFBazaar NÃO FAZ REPRESENTAÇÕES OU GARANTIAS DE QUALQUER TIPO, EXPRESSAS OU IMPLÍCITAS, RELATIVAS À OPERAÇÃO DESTE SITE OU ÀS INFORMAÇÕES, CONTEÚDOS, MATERIAIS OU PRODUTOS INCLUÍDOS NESTE SITE. Você concorda expressamente que o uso deste site é de seu próprio risco. ATÉ A EXTENSÃO PERMITIDA PELA LEI APLICÁVEL, A SFBazaar REJEITA TODAS AS GARANTIAS, EXPRESSAS OU IMPLÍCITAS, INCLUINDO, MAS NÃO SE LIMITANDO A, GARANTIAS IMPLÍCITAS DE COMERCIALIZAÇÃO E ADEQUAÇÃO A UM PROPÓSITO ESPECÍFICO. A SFBazaar NÃO GARANTE QUE ESTE SITE, SEUS SERVIDORES OU E-MAIL ENVIADOS DA SFBazar ESTEJAM LIVRES DE VÍRUS OU OUTROS COMPONENTES PREJUDICIAIS. A SFBazaar NÃO SERÁ RESPONSÁVEL POR QUAISQUER DANOS DE QUALQUER TIPO DECORRENTES DO USO DESTE SITE, INCLUINDO, MAS NÃO SE LIMITANDO A DANOS DIRETOS, INDIRETOS, INCIDENTAIS, PUNITIVOS E CONSEQÜENCIAIS. CERTAS LEIS DO ESTADO NÃO PERMITAM LIMITAÇÕES DE GARANTIAS IMPLÍCITAS OU EXCLUSÃO OU LIMITAÇÃO DE CERTOS DANOS. SE ESTAS LEIS APLICAR A VOCÊ, ALGUMAS OU TODAS AS ISENÇÕES, EXCLUSÕES OU LIMITAÇÕES ACIMA PODEM NÃO SE APLICAR A VOCÊ, E PODE TER DIREITOS ADICIONAIS.
        <br><br>

        <strong>LEI APLICÁVEL</strong>
        Ao visitar o SFBazaar, você concorda que as leis do estado, sem considerar os princípios de conflito de leis, regerão estas Condições de Uso e qualquer disputa de qualquer tipo que possa surgir entre você e o SFBazaar ou seus associados.
        <br><br>

        <strong>DISPUTAS</strong><br>
        Qualquer disputa relacionada de alguma forma à sua visita ao SFBazaar ou aos produtos que você compra através do SFBazaar deve ser submetida a arbitragem confidencial, exceto que, na medida em que você violou ou ameaçou violar os direitos de propriedade intelectual do SFBazaar, o SFBazaar pode procurar medidas cautelares. ou outra medida apropriada em qualquer tribunal federal do estado e você concorda com a jurisdição e o local exclusivos desses tribunais. A arbitragem nos termos deste contrato deve ser conduzida de acordo com as regras vigentes na American Arbitration Association. A decisão dos árbitros será vinculativa e poderá ser julgada em qualquer tribunal de jurisdição competente. Na extensão máxima permitida pela lei aplicável, nenhuma arbitragem nos termos deste Contrato deverá ser associada a uma arbitragem envolvendo qualquer outra parte sujeita a este Contrato, seja através de processos de arbitragem de classe ou de outra forma.
        <br><br>

        <strong>POLÍTICAS, MODIFICAÇÃO E SEVERABILIDADE DO SITE</strong><br>
        Consulte nossas outras políticas, como a política de Envios e devoluções, publicadas neste site. Essas políticas também regem sua visita ao SFBazaar. Reservamo-nos o direito de fazer alterações em nosso site, políticas e nestas Condições de Uso a qualquer momento. Se qualquer uma dessas condições for considerada inválida, nula ou por qualquer motivo inexequível, essa condição será considerada separável e não afetará a validade e a aplicabilidade de qualquer condição remanescente.
        <br><br>


        <h2><center>POLÍTICA DE PRIVACIDADE</center></h2>
        Quaisquer informações colectadas por meio dos nossos Serviços são cobertas pela Política de Privacidade em vigor no momento em que essas informações são colectadas. Podemos rever esta Política de Privacidade de tempos em tempos.<br>
        Podemos colectar suas Informações Pessoais, como endereço de e-mail, número de telefone ou endereço de correspondência quando você optar por solicitar informações sobre nossos Serviços, registrar-se no boletim de notícias SFBazaar ou em um programa que possamos oferecer periodicamente.<br>
        Em alguns casos, podemos colectar e armazenar informações sobre a sua localização, como convertendo seu endereço IP em uma geolocalização aproximada ou acessando as coordenadas GPS do seu dispositivo móvel ou uma localização aproximada, se você activar os serviços de localização no seu dispositivo. Podemos usar as informações de localização para melhorar e personalizar os serviços que lhe oferecemos.<br><br>

        <strong>Não compartilharemos nenhuma informação pessoal que colectamos de ou com relação a você com nenhuma empresa de terceiros ou parceiro comercial.</strong>
        <br><br>

        <strong>ATERAR A SUA INFORMAÇÃO</strong><br>
        Você pode acessar e modificar as informações pessoais associadas à sua conta através das configurações da sua conta ou entrando em contato conosco <a href="mailto:stely0@hotmail.com">via e-mail</a>.<br>
        Se você deseja apagar as suas informações pessoais e a sua conta, entre em contato conosco <a href="mailto:stely0@hotmail.com">Via email</a> com o seu pedido. Tomaremos as medidas necessárias para apagar as suas informações assim que possível, mas algumas informações podem permanecer em cópias arquivadas dos nossos registros ou conforme exigido por lei.
        <br><br>

        <strong>DIREITOS DE ACESSO, RECTIFICAÇÃO, APAGAMENTO E RESTRIÇÃO</strong><br>
        Você pode perguntar se SFBazaar está processando informações pessoais sobre você, solicitar acesso a informações pessoais e solicitar que corrijamos, alteremos ou apaguemos as suas informações pessoais onde elas não forem precisas. Onde permitido pela lei aplicável, você pode entrar em contacto connosco <a href="mailto:stely0@hotmail.com">via email</a> para solicitar acesso, receber, buscar rectificação ou solicitar apagamento das informações pessoais mantidas sobre você por nós. Inclua seu nome completo, endereço de e-mail associado à sua conta e uma descrição detalhada da sua solicitação. Tais pedidos serão processados de acordo com as leis locais.
        <br><br>Se você deseja apagar suas informações pessoais e sua conta, entre em contato connosco.
        Embora SFBazaar se esforce de boa-fé para fornecer aos indivíduos acesso às suas informações pessoais, pode haver circunstâncias em que não possamos fornecer acesso, incluindo, mas não se limitando a: onde as informações contenham privilégios legais, comprometam a privacidade de outras pessoas ou outros legítimos direitos, onde o ónus ou despesa de fornecer acesso seria desproporcional aos riscos à privacidade do Indivíduo no caso em questão ou em que seja comercialmente proprietário. Se determinarmos que o acesso deve ser restrito em qualquer instância específica, forneceremos uma explicação de por que essa determinação foi feita e um ponto de contacto para outras dúvidas. Para proteger sua privacidade, tomaremos medidas comercialmente razoáveis para verificar sua identidade antes de conceder acesso ou fazer alterações em suas Informações Pessoais.
        <br><br>

        <strong>SEGURANÇA DAS SUAS INFORMAÇÕES</strong><br>
        Tomamos medidas administrativas, físicas e eletrônicas razoáveis, projetadas para proteger as informações que coletamos de ou sobre você (incluindo suas Informações Pessoais) contra acesso, uso ou divulgação não autorizados. Quando você insere informações confidenciais em nossos formulários, criptografamos esses dados usando SSL ou outras tecnologias. No entanto, esteja ciente de que nenhum método de transmissão de informações pela Internet ou de armazenamento de informações é completamente seguro. Portanto, não podemos garantir a segurança absoluta de qualquer informação. Não assumimos responsabilidade por divulgação não intencional. <br>
        Ao usar o App / Site ou fornecer Informações Pessoais para nós, você concorda que podemos nos comunicar eletronicamente com você em relação a questões de segurança, privacidade e administrativas relacionadas ao uso do Site. Se soubermos da violação de um sistema de segurança, podemos tentar notificá-lo eletronicamente, publicando um aviso no Site ou enviando um e-mail para você. Você pode ter o direito legal de receber este aviso por escrito.
        <br><br>
        <strong>NOSSA POLÍTICA PARA CRIANÇAS</strong><br>
        Nossos Serviços não são direcionados a crianças menores de 13 anos e não coletamos intencionalmente Informações Pessoais de crianças menores de 13 anos. Se soubermos que coletamos Informações Pessoais de uma criança menor de 13 anos, tomaremos medidas para apagar essas informações dos nossos arquivos assim que possível. possível. Se você tem menos de 18 anos, precisa da permissão de seus pais para acessar os Serviços.<br><br>

        <strong>QUESTÕES?</strong><br>
        Contacte-nos <a href="mailto:stely0@hotmail.com">Via email</a> if you have any questions about our practices or this terms of Use and/or Privacy Policy.
        <br><br>

    </div><!-- ./ container -->

    <!-- Footer -->
    <?php include 'footer.php' ?>

    <!-- javascript functions -->
    <script>
        //---------------------------------
        // MARK - OPEN/CLOSE RIGHT SIDEBAR
        //---------------------------------
        function openSidebar() {
            document.getElementById("right-sidebar").style.width = "250px";
        }

        function closeSidebar() {
            document.getElementById("right-sidebar").style.width = "0";
        }
    </script>
</body>
</html>
