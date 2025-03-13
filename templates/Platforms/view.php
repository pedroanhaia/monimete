<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Platform $platform
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Platform'), ['action' => 'edit', $platform->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Platform'), ['action' => 'delete', $platform->id], ['confirm' => __('Are you sure you want to delete # {0}?', $platform->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Platforms'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Platform'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="platforms view content">
            <h3><?= h($platform->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($platform->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Url') ?></th>
                    <td><?= h($platform->url) ?></td>
                </tr>
                <tr>
                    <th><?= __('Powered') ?></th>
                    <td><?= h($platform->powered) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($platform->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $platform->type === null ? '' : $this->Number->format($platform->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $platform->role === null ? '' : $this->Number->format($platform->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Darkmode') ?></th>
                    <td><?= $this->Number->format($platform->darkmode) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Update') ?></th>
                    <td><?= h($platform->last_update) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($platform->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($platform->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Logs') ?></h4>
                <?php if (!empty($platform->logs)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Date Time') ?></th>
                            <th><?= __('Message') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Device Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('Platform Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($platform->logs as $log) : ?>
                        <tr>
                            <td><?= h($log->id) ?></td>
                            <td><?= h($log->date_time) ?></td>
                            <td><?= h($log->message) ?></td>
                            <td><?= h($log->status) ?></td>
                            <td><?= h($log->type) ?></td>
                            <td><?= h($log->device_id) ?></td>
                            <td><?= h($log->created) ?></td>
                            <td><?= h($log->modified) ?></td>
                            <td><?= h($log->role) ?></td>
                            <td><?= h($log->platform_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Logs', 'action' => 'view', $log->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Logs', 'action' => 'edit', $log->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Logs', 'action' => 'delete', $log->id], ['confirm' => __('Are you sure you want to delete # {0}?', $log->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Services') ?></h4>
                <?php if (!empty($platform->services)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Endpoint') ?></th>
                            <th><?= __('Platform Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($platform->services as $service) : ?>
                        <tr>
                            <td><?= h($service->id) ?></td>
                            <td><?= h($service->name) ?></td>
                            <td><?= h($service->type) ?></td>
                            <td><?= h($service->endpoint) ?></td>
                            <td><?= h($service->platform_id) ?></td>
                            <td><?= h($service->created) ?></td>
                            <td><?= h($service->modified) ?></td>
                            <td><?= h($service->role) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Services', 'action' => 'view', $service->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Services', 'action' => 'edit', $service->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Services', 'action' => 'delete', $service->id], ['confirm' => __('Are you sure you want to delete # {0}?', $service->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>