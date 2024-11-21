<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Log> $logs
 */
?>
<div class="logs index content">
    <?= $this->Html->link(__('New Log'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Logs') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('date_time') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th><?= $this->Paginator->sort('device_id') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th><?= $this->Paginator->sort('platform_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $this->Number->format($log->id) ?></td>
                    <td><?= h($log->date_time) ?></td>
                    <td><?= h($log->status) ?></td>
                    <td><?= $log->type === null ? '' : $this->Number->format($log->type) ?></td>
                    <td><?= $log->hasValue('device') ? $this->Html->link($log->device->name, ['controller' => 'Devices', 'action' => 'view', $log->device->id]) : '' ?></td>
                    <td><?= h($log->created) ?></td>
                    <td><?= h($log->modified) ?></td>
                    <td><?= $log->role === null ? '' : $this->Number->format($log->role) ?></td>
                    <td><?= $log->hasValue('platform') ? $this->Html->link($log->platform->name, ['controller' => 'Platforms', 'action' => 'view', $log->platform->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $log->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $log->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $log->id], ['confirm' => __('Are you sure you want to delete # {0}?', $log->id)]) ?>
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