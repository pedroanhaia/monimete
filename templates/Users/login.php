<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('login', ['class' => 'btndefault'])); ?>
    <?= $this->Form->end() ?>
    <?= $this->Html->link(
        'cadastre-se',
        ['controller' => 'Users', 'action' => 'add', 'class' => 'button btndefault'],
    )?>
</div>
