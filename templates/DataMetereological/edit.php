<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DataMetereological $dataMetereological
 * @var string[]|\Cake\Collection\CollectionInterface $locations
 * @var string[]|\Cake\Collection\CollectionInterface $services
 * @var string[]|\Cake\Collection\CollectionInterface $devices
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $dataMetereological->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $dataMetereological->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Data Metereological'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="dataMetereological form content">
            <?= $this->Form->create($dataMetereological) ?>
            <fieldset>
                <legend><?= __('Edit Data Metereological') ?></legend>
                <?php
                    echo $this->Form->control('date_time', ['empty' => true]);
                    echo $this->Form->control('temperature');
                    echo $this->Form->control('humidity');
                    echo $this->Form->control('precipitation');
                    echo $this->Form->control('wind_direction');
                    echo $this->Form->control('wind_speed');
                    echo $this->Form->control('latitude');
                    echo $this->Form->control('longitude');
                    echo $this->Form->control('location_id', ['options' => $locations, 'empty' => true]);
                    echo $this->Form->control('service_id', ['options' => $services, 'empty' => true]);
                    echo $this->Form->control('device_id', ['options' => $devices, 'empty' => true]);
                    echo $this->Form->control('role');
                    echo $this->Form->control('type');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
