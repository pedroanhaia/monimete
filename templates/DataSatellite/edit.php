<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DataSatellite $dataSatellite
 * @var string[]|\Cake\Collection\CollectionInterface $devices
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $dataSatellite->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $dataSatellite->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Data Satellite'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="dataSatellite form content">
            <?= $this->Form->create($dataSatellite) ?>
            <fieldset>
                <legend><?= __('Edit Data Satellite') ?></legend>
                <?php
                    echo $this->Form->control('date_time', ['empty' => true]);
                    echo $this->Form->control('quality_signal');
                    echo $this->Form->control('latitude');
                    echo $this->Form->control('longitude');
                    echo $this->Form->control('type');
                    echo $this->Form->control('device_id', ['options' => $devices, 'empty' => true]);
                    echo $this->Form->control('role');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
