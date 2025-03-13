<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Location> $locations
 */
?>
<div class="locations index content">
    <?= $this->Html->link(__('New Location'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Locations') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('latitude') ?></th>
                    <th><?= $this->Paginator->sort('longitude') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th><?= $this->Paginator->sort('city_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                <tr>
                    <td><?= $this->Number->format($location->id) ?></td>
                    <td><?= h($location->name) ?></td>
                    <td><?= $location->latitude === null ? '' : $this->Number->format($location->latitude) ?></td>
                    <td><?= $location->longitude === null ? '' : $this->Number->format($location->longitude) ?></td>
                    <td><?= h($location->created) ?></td>
                    <td><?= h($location->modified) ?></td>
                    <td><?= $location->role === null ? '' : $this->Number->format($location->role) ?></td>
                    <td><?= $location->hasValue('city') ? $this->Html->link($location->city->name, ['controller' => 'Cities', 'action' => 'view', $location->city->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $location->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $location->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id)]) ?>
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