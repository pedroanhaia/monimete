<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DataMetereological> $dataMetereological
 */
?>
<div class="dataMetereological index content">
    <?= $this->Html->link(__('New Data Metereological'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Data Metereological') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('date_time') ?></th>
                    <th><?= $this->Paginator->sort('temperature') ?></th>
                    <th><?= $this->Paginator->sort('humidity') ?></th>
                    <th><?= $this->Paginator->sort('precipitation') ?></th>
                    <th><?= $this->Paginator->sort('wind_direction') ?></th>
                    <th><?= $this->Paginator->sort('wind_speed') ?></th>
                    <th><?= $this->Paginator->sort('latitude') ?></th>
                    <th><?= $this->Paginator->sort('longitude') ?></th>
                    <th><?= $this->Paginator->sort('location_id') ?></th>
                    <th><?= $this->Paginator->sort('service_id') ?></th>
                    <th><?= $this->Paginator->sort('device_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataMetereological as $dataMetereological): ?>
                <tr>
                    <td><?= $this->Number->format($dataMetereological->id) ?></td>
                    <td><?= h($dataMetereological->date_time) ?></td>
                    <td><?= $dataMetereological->temperature === null ? '' : $this->Number->format($dataMetereological->temperature) ?></td>
                    <td><?= $dataMetereological->humidity === null ? '' : $this->Number->format($dataMetereological->humidity) ?></td>
                    <td><?= $dataMetereological->precipitation === null ? '' : $this->Number->format($dataMetereological->precipitation) ?></td>
                    <td><?= h($dataMetereological->wind_direction) ?></td>
                    <td><?= $dataMetereological->wind_speed === null ? '' : $this->Number->format($dataMetereological->wind_speed) ?></td>
                    <td><?= $dataMetereological->latitude === null ? '' : $this->Number->format($dataMetereological->latitude) ?></td>
                    <td><?= $dataMetereological->longitude === null ? '' : $this->Number->format($dataMetereological->longitude) ?></td>
                    <td><?= $dataMetereological->hasValue('location') ? $this->Html->link($dataMetereological->location->name, ['controller' => 'Locations', 'action' => 'view', $dataMetereological->location->id]) : '' ?></td>
                    <td><?= $dataMetereological->hasValue('service') ? $this->Html->link($dataMetereological->service->name, ['controller' => 'Services', 'action' => 'view', $dataMetereological->service->id]) : '' ?></td>
                    <td><?= $dataMetereological->hasValue('device') ? $this->Html->link($dataMetereological->device->name, ['controller' => 'Devices', 'action' => 'view', $dataMetereological->device->id]) : '' ?></td>
                    <td><?= h($dataMetereological->created) ?></td>
                    <td><?= h($dataMetereological->modified) ?></td>
                    <td><?= $dataMetereological->role === null ? '' : $this->Number->format($dataMetereological->role) ?></td>
                    <td><?= $dataMetereological->type === null ? '' : $this->Number->format($dataMetereological->type) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dataMetereological->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dataMetereological->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dataMetereological->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataMetereological->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>