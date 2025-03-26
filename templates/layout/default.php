<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Monimete: monitoramente metereológico Agrocity';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon','img/AGROCITY_LIVING_LABicon.jpg') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'classescssproprias']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<style>
    
        .flex-parent-element {
        display: flex;
        width: 100vw;
        position: relative;

        }
        .main{
            display: block;
            width: 100%;
            z-index:1;
            transition: width 0.3s ease-in-out, margin-left 0.3s ease-in-out;
        }
        .main.stretch{
            width: calc(300vw); 
            margin-left: -250px; 
        }
        .flex-child-element {
        flex: 1;
        border: 2px solid blueviolet;
        margin: 10px;
        }

        .flex-child-element:first-child {
        margin-right: 20px;
        z-index:2;
        }
        body {
            display: flex;
        }
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh; /* Altura da tela inteira */
            width: 250px; /* Largura fixa */
            background-color: #343a40; /* Cor de fundo */
            color: white; /* Cor do texto */
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.hidden{
            transform: translateX(-250px);
            
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #495057; /* Cor ao passar o mouse */
        }
        .top-nav{
            max-width: 100vw;
            color: white;
            padding: 10px;
            width: 100%;
            position: relative;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: width: 0.3s ease-in-out;
        }
        
        .sidebar-button{
            position: absolute;
            
            right: -45px;
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            z-index: 999;
        }

        </style>
</head>
<!-- Sidebar -->


<div class = "flex-parent-element"overflow-x= "auto">
    <div style="display: flex;z-index: 2;">
        <div class="flex_child_element"> 
            <?php
                $currenttemplate = $this->getRequest()->getParam('controller');
                $currentaction = $this->getRequest()->getParam('action');
                if(!($currenttemplate == 'Pages' && $currentaction == 'display')){

            ?>
            <div class="sidebar" id="sidebar">
                <div class="sidebar-button" >
                    <button id="sidebarCollapse">☰</button>
                </div>
                <nav>
                    <?php
                        
                        $menuItems = [
                            'Início' => ['controller' => 'Pages', 'action' => 'display', 'indexhome'],
                            'Cidades' => ['controller' => 'Cities', 'action' => 'index'],
                            'Metereologico' => ['controller' => 'DataMetereological', 'action' => 'index'],
                            'Satélite' => ['controller' => 'DataSatellite', 'action' => 'index'],   
                            'Dispositivos' => ['controller' => 'Devices', 'action' => 'index'],
                            'Locais' => ['controller' => 'Locations', 'action' => 'index'],
                            'Logs' => ['controller' => 'Logs', 'action' => 'index'],
                            'Plataformas' => ['controller' => 'Platforms', 'action' => 'index'],
                            'Serviços' => ['controller' => 'Services', 'action' => 'index'],
                            'Usuários' => ['controller' => 'Users', 'action' => 'index'],
                            'Configurações' => ['controller' => 'Settings', 'action' => 'index'],
                        ];
                        foreach ($menuItems as $label => $url) {
                    
                            echo $this->Html->link($label, $url, ['class' => 'side-nav-item']);
                        }
                    
                    
                    
                    ?>
                </nav>
            </div>
            <?php } ?>
        </div>  
        
    </div>
    
    <main class="main" id="main" overflow-x: visible;>
        <nav class="top-nav" id="top-nav">
            <div class="top-nav-links">
                <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/">Documentação</a>
                <a target="_blank" rel="noopener" href="https://api.cakephp.org/">API</a>
                <?= $this->Html->link(__('Acesso restrito'), ['controller' => 'users'],['action' => 'login'], ['class' => 'button float-right']) ?>
            </div>
            <div class="top-nav-title">
                
                <?=$this->Html->image('AGROCITY_LIVING_LABicon.jpg', ['alt' => 'Agrocity Logo', 'width'=>'100em'])?>
                
                <a  href="<?= $this->Url->build('/') ?>"><span>Moni</span>mete</a>
            </div>
        </nav>
        
        <div class="container">
            
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
</div>
</div>
</div>

</div>
<?php
  $currenttemplate = $this->getRequest()->getParam('controller');
  $currentaction = $this->getRequest()->getParam('action');
  if(!($currenttemplate == 'Pages' && $currentaction == 'display')){
 ?>
    <script> 
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
    document.getElementById('top-nav').classList.toggle('stretch');
    document.getElementById('main').classList.toggle('stretch');
    document.getElementById('sidebar').classList.toggle('hidden');
    });
    </script>
<?php
};
?>
</body>
</html>
