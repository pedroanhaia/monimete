<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Projeto de Monitoramento Meteorológico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
            color: #333;
        }
        header {
            background-color: #004080;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #0059b3;
            overflow: hidden;
        }
        nav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #0073e6;
            color: white;
        }
        main {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        h2, h3 {
            color: #004080;
        }
        h1{
            color:#FFFFFF;
        }
        section {
            margin-bottom: 40px;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #cccccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        footer {
            background-color: #004080;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .stakeholder {
            background-color: #e6f0ff;
            padding: 10px;
            margin-bottom: 10px;
        }
        .code-block {
            background-color: #e9ecef;
            padding: 10px;
            overflow-x: auto;
        }
        .code-block pre {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Projeto de Monitoramento Meteorológico</h1>
    </header>

    <nav>
        <a href="#introducao">Introdução</a>
        <a href="#objetivo">Objetivo</a>
        <a href="#esforcos">Esforços Necessários</a>
        <a href="#materiais">Materiais Necessários</a>
        <a href="#software">Ferramentas de Software</a>
        <a href="#cronograma">Cronograma</a>
        <a href="#stakeholders">Stakeholders</a>
        <a href="#requisitos">Requisitos do Sistema</a>
        <a href="#user-stories">Histórias de Usuário</a>
        <a href="#mysql">Comandos MySQL</a>
    </nav>

    <main>
        <section id="introducao">
            <h2>Introdução</h2>
            <p>O seguinte projeto tem como objetivo criar um sistema de acompanhamento meteorológico com comunicação direta a satélites existentes com essa finalidade e APIs de fontes confiáveis. Seu objetivo principal é permitir a independência do Agrocity quanto ao monitoramento climático.</p>
        </section>

        <section id="objetivo">
            <h2>Objetivo</h2>
            <p>Criar plataforma centralizada de dados meteorológicos, sendo capaz de receber dados oriundos de APIs e satélites de monitoramento. Sendo a entrega proposta uma plataforma web com recepção de dados via satélite por meio de antena APT e comunicação com outras plataformas via internet. Espera-se que a plataforma permita visualização histórica dos dados por região e independência nas análises.</p>
        </section>

        <section id="esforcos">
            <h2>Esforços Necessários</h2>
            <ul>
                <li>Levantamento de requisitos;</li>
                <li>Prototipação do sistema;</li>
                <li>Modelagem do banco de dados utilizando <a href="https://www.lucidchart.com/" target="_blank">LucidChart</a>;</li>
                <li>Criação de ambiente colaborativo de desenvolvimento no <a href="https://github.com/" target="_blank">GitHub</a>;</li>
                <li>Configuração de ambiente de desenvolvimento (<a href="https://www.apachefriends.org/pt_br/index.html" target="_blank">XAMPP</a>, <a href="https://code.visualstudio.com/download" target="_blank">VS Code</a>);</li>
                <li>Criação de server-side e backoffice com o framework <a href="https://book.cakephp.org/4/en/installation.html" target="_blank">CakePHP</a>;</li>
                <li>Desenvolvimento de integração com API de dados meteorológicos (<a href="http://servicos.cptec.inpe.br/XML/" target="_blank">CPTEC</a>);</li>
                <li>Desenvolvimento de integração do sistema com a Raspberry Pi;</li>
                <li>Desenvolvimento de interfaces para visualização dos dados (recomenda-se o uso de JavaScript com comunicação via AJAX);</li>
                <li>Aquisição de materiais necessários;</li>
                <li>Desenvolvimento de antena de acordo com o manual (<a href="https://www.raspberrypi.com/tutorials/build-your-own-weather-satellite-receiving-station/" target="_blank">Tutorial</a>);</li>
                <li>Configuração da Raspberry Pi de acordo com o manual;</li>
                <li>Posicionamento da antena;</li>
                <li>Testes de verificação de integridade.</li>
            </ul>
        </section>

        <section id="materiais">
            <h2>Materiais Necessários</h2>
            <ul>
                <li>Trena;</li>
                <li>Paquímetro;</li>
                <li>Raspberry Pi 4 ou superior;</li>
                <li>Furadeira;</li>
                <li>Brocas de 8 mm e XX mm;</li>
                <li>Receptor USB SDR (<a href="https://www.amazon.co.uk/NooElec-NESDR-Mini-Previously-Compatible/dp/B009U7WZCA/" target="_blank">Modelo</a>);</li>
                <li>Gabinete Raspberry Pi;</li>
                <li>5 metros de tubo de cobre 8mm (<a href="https://www.amazon.co.uk/Metre-Coil-Table-Microbore-Copper/dp/B00KJCTIAW/" target="_blank">Modelo</a>);</li>
                <li>Adaptador rabo de porco MCX para BNC (<a href="https://www.amazon.co.uk/sourcingmap%C2%AE-Female-Pigtail-Adapter-23-5cm-Black/dp/B00K85HHTE" target="_blank">Modelo</a>);</li>
                <li>Cabo coaxial BNC macho para BNC fêmea (<a href="https://www.amazon.co.uk/BOOBRIE-Female-Extension-Broadcast-Security/dp/B09F2KHF8B/" target="_blank">Modelo</a>);</li>
                <li>Tubo de resíduo de plástico branco 40mm, 1,5 metros de comprimento (<a href="https://www.screwfix.com/p/floplast-solvent-weld-waste-pipe-white-40mm-x-3m/44310" target="_blank">Modelo</a>);</li>
                <li>Lixa;</li>
                <li>Estanho;</li>
                <li>Parafusos auto-roscantes de 4 x 2 mm por 8 mm;</li>
                <li>Computador para uso;</li>
                <li>Impressora 3D para criação de peças componentes da antena;</li>
                <li>Rede de internet;</li>
                <li>Serra de cortar cano;</li>
                <li>Cola para plástico e madeira;</li>
                <li>Chave Phillips de tamanho médio;</li>
                <li>Lápis.</li>
            </ul>
        </section>

        <section id="software">
            <h2>Ferramentas de Software Utilizadas</h2>
            <ul>
                <li>XAMPP V5.2 (PHP VXX, MySQL VXX);</li>
                <li>Composer VXX;</li>
                <li>LucidChart;</li>
                <li>GitHub;</li>
                <li>Visual Studio Code;</li>
                <li>CakePHP Framework.</li>
            </ul>
        </section>

        <section id="cronograma">
            <h2>Cronograma Previsto</h2>
            <table>
                <tr>
                    <th>Data</th>
                    <th>Tarefa</th>
                </tr>
                <tr>
                    <td>24/10/2024</td>
                    <td>Levantamento de requisitos, arquitetar infraestrutura.</td>
                </tr>
                <tr>
                    <td>31/10/2024</td>
                    <td>Modelar banco de dados.</td>
                </tr>
                <tr>
                    <td>07/11/2024</td>
                    <td>Criação de ambiente de desenvolvimento.</td>
                </tr>
                <tr>
                    <td>14/11/2024</td>
                    <td>Geração de banco de dados e esqueleto do backoffice.</td>
                </tr>
                <tr>
                    <td>21/11/2024</td>
                    <td>Ajustes server-side e desenvolvimento de API.</td>
                </tr>
                <tr>
                    <td>28/11/2024</td>
                    <td>Desenvolvimento de antena.</td>
                </tr>
                <tr>
                    <td>05/12/2024</td>
                    <td>Continuação do desenvolvimento de antena.</td>
                </tr>
                <tr>
                    <td>12/12/2024</td>
                    <td>Posicionamento de antena.</td>
                </tr>
                <tr>
                    <td>19/12/2024</td>
                    <td>Teste do sistema e ajustes.</td>
                </tr>
                <tr>
                    <td>26/12/2024</td>
                    <td>Teste do sistema e ajustes finais.</td>
                </tr>
            </table>
        </section>

        <section id="stakeholders">
            <h2>Stakeholders</h2>
            <div class="stakeholder">
                <h3>Produtores Rurais</h3>
                <p><strong>Objetivo:</strong> Garantir que os produtores rurais tenham acesso a dados meteorológicos precisos e históricos para facilitar a tomada de decisões sobre plantio e colheita.</p>
                <p><strong>Métrica:</strong> Aumento na produtividade agrícola devido à previsibilidade climática mais acurada.</p>
            </div>
            <div class="stakeholder">
                <h3>Equipe de Pesquisa Agrocity</h3>
                <p><strong>Objetivo:</strong> Independência do Agrocity em relação a fontes externas de dados climáticos, utilizando dados próprios e APIs integradas.</p>
                <p><strong>Métrica:</strong> Redução de dependência de serviços externos de monitoramento meteorológico.</p>
            </div>
        </section>

        <section id="requisitos">
            <h2>Requisitos do Sistema</h2>
            <ul>
                <li>Receber dados via satélite;</li>
                <li>Integrar com API de dados meteorológicos;</li>
                <li>Integrar com sistema de mapas;</li>
                <li>Permitir acesso não autenticado aos dashboards;</li>
                <li>Armazenar dados históricos;</li>
                <li>Saídas dos dados com dashboards e mapas de calor.</li>
            </ul>
        </section>

        <section id="user-stories">
            <h2>Histórias de Usuário</h2>
            <h3>Cadastro e Integração de APIs</h3>
            <p><strong>Título:</strong> Integração de APIs Meteorológicas</p>
            <p><strong>Descrição:</strong> O sistema deve ser capaz de integrar e realizar requisições a APIs confiáveis de dados meteorológicos, como o CPTEC.</p>
            <p><strong>Critérios de Aceitação:</strong> As requisições são realizadas com sucesso, e os dados meteorológicos são armazenados na plataforma.</p>

            <h3>Desenvolvimento de Antena</h3>
            <p><strong>Título:</strong> Montagem e Configuração de Antena</p>
            <p><strong>Descrição:</strong> O sistema deve permitir a configuração de uma antena meteorológica baseada em Raspberry Pi, que se comunica com satélites para receber dados meteorológicos.</p>
            <p><strong>Critérios de Aceitação:</strong> A antena deve estar corretamente posicionada, capturando dados e enviando-os para o sistema.</p>

            <h3>Visualização de Dados Meteorológicos</h3>
            <p><strong>Título:</strong> Exibição de Dados Meteorológicos</p>
            <p><strong>Descrição:</strong> O usuário pode visualizar dados climáticos em tempo real e históricos por região, exibidos através de gráficos e mapas interativos.</p>
            <p><strong>Critérios de Aceitação:</strong> Os dados são exibidos de forma clara e filtrável, permitindo análises por data e região.</p>

            <h3>Criação de Ambiente Colaborativo</h3>
            <p><strong>Título:</strong> Criação de Ambiente Colaborativo de Desenvolvimento</p>
            <p><strong>Descrição:</strong> Criação e configuração de um ambiente colaborativo utilizando GitHub para gerenciar o código e progresso do projeto.</p>
            <p><strong>Critérios de Aceitação:</strong> O ambiente está funcionando corretamente se todos os desenvolvedores têm acesso ao repositório.</p>
        </section>

        <section id="mysql">
            <h2>Comandos MySQL</h2>
            <div class="code-block">
                <pre>
CREATE TABLE `platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `url` varchar(255),
  `last_update` datetime,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `powered` varchar(255),
  PRIMARY KEY (`id`)
);

CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `obs` text,
  `cod_ibge` varchar(255),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`)
);

CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `latitude` float,
  `longitude` float,
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `city_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`)
);

CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `model` varchar(100),
  `producer` varchar(100),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `location_id` int(11),
  `img` varchar(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`)
);

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11),
  `name` varchar(100),
  `value` varchar(200),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `type` int(2),
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `email` varchar(200),
  `password` varchar(255),
  `type` int(2),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `city_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`)
);

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `message` text,
  `status` varchar(50),
  `type` int(2),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `platform_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`),
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`)
);

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `endpoint` varchar(255),
  `platform_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`)
);

CREATE TABLE `data_metereological` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `temperature` float,
  `humidity` float,
  `precipitation` float,
  `wind_direction` varchar(50),
  `wind_speed` float,
  `latitude` float,
  `longitude` float,
  `location_id` int(11),
  `service_id` int(11),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `type` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`)
);

CREATE TABLE `data_satellite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `quality_signal` float,
  `latitude` float,
  `longitude` float,
  `type` int(2),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`)
);
                </pre>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2024 Projeto MoniMete - Todos os direitos reservados.
    </footer>
</body>
</html>
