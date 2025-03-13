<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DataMetereological $dataMetereological
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Data Metereological'), ['action' => 'edit', $dataMetereological->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Data Metereological'), ['action' => 'delete', $dataMetereological->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataMetereological->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Data Metereological'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Data Metereological'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="dataMetereological view content">
            <h3><?= h($dataMetereological->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Wind Direction') ?></th>
                    <td><?= h($dataMetereological->wind_direction) ?></td>
                </tr>
                <tr>
                    <th><?= __('Location') ?></th>
                    <td><?= $dataMetereological->hasValue('location') ? $this->Html->link($dataMetereological->location->name, ['controller' => 'Locations', 'action' => 'view', $dataMetereological->location->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Service') ?></th>
                    <td><?= $dataMetereological->hasValue('service') ? $this->Html->link($dataMetereological->service->name, ['controller' => 'Services', 'action' => 'view', $dataMetereological->service->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Device') ?></th>
                    <td><?= $dataMetereological->hasValue('device') ? $this->Html->link($dataMetereological->device->name, ['controller' => 'Devices', 'action' => 'view', $dataMetereological->device->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dataMetereological->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Temperature') ?></th>
                    <td><?= $dataMetereological->temperature === null ? '' : $this->Number->format($dataMetereological->temperature) ?></td>
                </tr>
                <tr>
                    <th><?= __('Humidity') ?></th>
                    <td><?= $dataMetereological->humidity === null ? '' : $this->Number->format($dataMetereological->humidity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Precipitation') ?></th>
                    <td><?= $dataMetereological->precipitation === null ? '' : $this->Number->format($dataMetereological->precipitation) ?></td>
                </tr>
                <tr>
                    <th><?= __('Wind Speed') ?></th>
                    <td><?= $dataMetereological->wind_speed === null ? '' : $this->Number->format($dataMetereological->wind_speed) ?></td>
                </tr>
                <tr>
                    <th><?= __('Latitude') ?></th>
                    <td><?= $dataMetereological->latitude === null ? '' : $this->Number->format($dataMetereological->latitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Longitude') ?></th>
                    <td><?= $dataMetereological->longitude === null ? '' : $this->Number->format($dataMetereological->longitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $dataMetereological->role === null ? '' : $this->Number->format($dataMetereological->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $dataMetereological->type === null ? '' : $this->Number->format($dataMetereological->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Time') ?></th>
                    <td><?= h($dataMetereological->date_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($dataMetereological->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($dataMetereological->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>