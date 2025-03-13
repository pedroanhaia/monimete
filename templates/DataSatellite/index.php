<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DataSatellite> $dataSatellite
 */
?>
<div class="dataSatellite index content">
    <?= $this->Html->link(__('New Data Satellite'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Data Satellite') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('date_time') ?></th>
                    <th><?= $this->Paginator->sort('quality_signal') ?></th>
                    <th><?= $this->Paginator->sort('latitude') ?></th>
                    <th><?= $this->Paginator->sort('longitude') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th><?= $this->Paginator->sort('device_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataSatellite as $dataSatellite): ?>
                <tr>
                    <td><?= $this->Number->format($dataSatellite->id) ?></td>
                    <td><?= h($dataSatellite->date_time) ?></td>
                    <td><?= $dataSatellite->quality_signal === null ? '' : $this->Number->format($dataSatellite->quality_signal) ?></td>
                    <td><?= $dataSatellite->latitude === null ? '' : $this->Number->format($dataSatellite->latitude) ?></td>
                    <td><?= $dataSatellite->longitude === null ? '' : $this->Number->format($dataSatellite->longitude) ?></td>
                    <td><?= $dataSatellite->type === null ? '' : $this->Number->format($dataSatellite->type) ?></td>
                    <td><?= $dataSatellite->hasValue('device') ? $this->Html->link($dataSatellite->device->name, ['controller' => 'Devices', 'action' => 'view', $dataSatellite->device->id]) : '' ?></td>
                    <td><?= h($dataSatellite->created) ?></td>
                    <td><?= h($dataSatellite->modified) ?></td>
                    <td><?= $dataSatellite->role === null ? '' : $this->Number->format($dataSatellite->role) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dataSatellite->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dataSatellite->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dataSatellite->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataSatellite->id)]) ?>
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