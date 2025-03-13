<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Platform $platform
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $platform->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $platform->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Platforms'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="platforms form content">
            <?= $this->Form->create($platform) ?>
            <fieldset>
                <legend><?= __('Edit Platform') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('type');
                    echo $this->Form->control('url');
                    echo $this->Form->control('last_update', ['empty' => true]);
                    echo $this->Form->control('role');
                    echo $this->Form->control('powered');
                    echo $this->Form->control('darkmode');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
