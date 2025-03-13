<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DataSatellite $dataSatellite
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Data Satellite'), ['action' => 'edit', $dataSatellite->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Data Satellite'), ['action' => 'delete', $dataSatellite->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataSatellite->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Data Satellite'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Data Satellite'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="dataSatellite view content">
            <h3><?= h($dataSatellite->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Device') ?></th>
                    <td><?= $dataSatellite->hasValue('device') ? $this->Html->link($dataSatellite->device->name, ['controller' => 'Devices', 'action' => 'view', $dataSatellite->device->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dataSatellite->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Quality Signal') ?></th>
                    <td><?= $dataSatellite->quality_signal === null ? '' : $this->Number->format($dataSatellite->quality_signal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Latitude') ?></th>
                    <td><?= $dataSatellite->latitude === null ? '' : $this->Number->format($dataSatellite->latitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Longitude') ?></th>
                    <td><?= $dataSatellite->longitude === null ? '' : $this->Number->format($dataSatellite->longitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $dataSatellite->type === null ? '' : $this->Number->format($dataSatellite->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $dataSatellite->role === null ? '' : $this->Number->format($dataSatellite->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Time') ?></th>
                    <td><?= h($dataSatellite->date_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($dataSatellite->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($dataSatellite->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>