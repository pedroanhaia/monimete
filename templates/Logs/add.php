<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Log $log
 * @var \Cake\Collection\CollectionInterface|string[] $devices
 * @var \Cake\Collection\CollectionInterface|string[] $platforms
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Logs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="logs form content">
            <?= $this->Form->create($log) ?>
            <fieldset>
                <legend><?= __('Add Log') ?></legend>
                <?php
                    echo $this->Form->control('date_time', ['empty' => true]);
                    echo $this->Form->control('message');
                    echo $this->Form->control('status');
                    echo $this->Form->control('type');
                    echo $this->Form->control('device_id', ['options' => $devices, 'empty' => true]);
                    echo $this->Form->control('role');
                    echo $this->Form->control('platform_id', ['options' => $platforms, 'empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
