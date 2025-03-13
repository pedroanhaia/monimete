<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $users
 */
<<<<<<< HEAD
?><style>
.sidebar{
position : absolute ;
top 50;
left 0;
}
</style>

<div class="row">
     <aside class="column">
        <div class="side-nav"> 
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('App Controller'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Cities Controler'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Data Metereological'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Data Satellite'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Devices'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Errors'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Locations'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Logs'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Pages'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Plataforms'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Services'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Settings'), ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('Users'), ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-90">
        <div class="users index content">
        <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
        <h3><?= __('Users') ?></h3>
        <div>
            <div class="table-responsive">    
                <table>
                    <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('id') ?></th>
                        <th><?= $this->Paginator->sort('name') ?></th>
                        <th><?= $this->Paginator->sort('email') ?></th>
                        <th><?= $this->Paginator->sort('type') ?></th>
                        <th><?= $this->Paginator->sort('created') ?></th>
                        <th><?= $this->Paginator->sort('modified') ?></th>
                        <th><?= $this->Paginator->sort('role') ?></th>
                        <th><?= $this->Paginator->sort('city_id') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $this->Number->format($user->id) ?></td>
                        <td><?= h($user->name) ?></td>
                        <td><?= h($user->email) ?></td>
                        <td><?= $user->type === null ? '' : $this->Number->format($user->type) ?></td>
                        <td><?= h($user->created) ?></td>
                        <td><?= h($user->modified) ?></td>
                        <td><?= $user->role === null ? '' : $this->Number->format($user->role) ?></td>
                        <td><?= $user->city_id === null ? '' : $this->Number->format($user->city_id) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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
</div>
=======
?>
<div class="users index content">
    <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('type') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th><?= $this->Paginator->sort('city_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $this->Number->format($user->id) ?></td>
                    <td><?= h($user->name) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= $user->type === null ? '' : $this->Number->format($user->type) ?></td>
                    <td><?= h($user->created) ?></td>
                    <td><?= h($user->modified) ?></td>
                    <td><?= $user->role === null ? '' : $this->Number->format($user->role) ?></td>
                    <td><?= $user->city_id === null ? '' : $this->Number->format($user->city_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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
>>>>>>> main
