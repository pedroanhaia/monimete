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
    <nav class="top-nav">
        <div class="top-nav-title">
            <img src="/img/AGROCITY_LIVING_LABicon.jpg" alt="Agrocity Logo" width="100em">
            <?=$this->Html->image('AGROCITY_LIVING_LABicon.jpg', ['alt' => 'Agrocity Logo', 'width'=>'100em'])?>
            
            <a  href="<?= $this->Url->build('/') ?>"><span>Moni</span>mete</a>
        </div>
        <div class="top-nav-links">
            <a target="_blank" rel="noopener" href="https://book.cakephp.org/5/">Documentação</a>
            <a target="_blank" rel="noopener" href="https://api.cakephp.org/">API</a>
            <?= $this->Html->link(__('Acesso restrito'), ['controller' => 'users'],['action' => 'login'], ['class' => 'button float-right']) ?>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
