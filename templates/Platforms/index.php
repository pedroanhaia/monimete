<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Platform> $platforms
 */
?>
<div class="platforms index content">
    <?= $this->Html->link(__('New Platform'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Platforms') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th><?= $this->Paginator->sort('url') ?></th>
                    <th><?= $this->Paginator->sort('last_update') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th><?= $this->Paginator->sort('powered') ?></th>
                    <th><?= $this->Paginator->sort('darkmode') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($platforms as $platform): ?>
                <tr>
                    <td><?= $this->Number->format($platform->id) ?></td>
                    <td><?= h($platform->name) ?></td>
                    <td><?= $platform->type === null ? '' : $this->Number->format($platform->type) ?></td>
                    <td><?= h($platform->url) ?></td>
                    <td><?= h($platform->last_update) ?></td>
                    <td><?= h($platform->created) ?></td>
                    <td><?= h($platform->modified) ?></td>
                    <td><?= $platform->role === null ? '' : $this->Number->format($platform->role) ?></td>
                    <td><?= h($platform->powered) ?></td>
                    <td><?= $this->Number->format($platform->darkmode) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $platform->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $platform->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $platform->id], ['confirm' => __('Are you sure you want to delete # {0}?', $platform->id)]) ?>
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