<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Service $service
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Service'), ['action' => 'edit', $service->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Service'), ['action' => 'delete', $service->id], ['confirm' => __('Are you sure you want to delete # {0}?', $service->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Services'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Service'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="services view content">
            <h3><?= h($service->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($service->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Endpoint') ?></th>
                    <td><?= h($service->endpoint) ?></td>
                </tr>
                <tr>
                    <th><?= __('Platform') ?></th>
                    <td><?= $service->hasValue('platform') ? $this->Html->link($service->platform->name, ['controller' => 'Platforms', 'action' => 'view', $service->platform->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($service->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $service->type === null ? '' : $this->Number->format($service->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $service->role === null ? '' : $this->Number->format($service->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($service->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($service->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Data Metereological') ?></h4>
                <?php if (!empty($service->data_metereological)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Date Time') ?></th>
                            <th><?= __('Temperature') ?></th>
                            <th><?= __('Humidity') ?></th>
                            <th><?= __('Precipitation') ?></th>
                            <th><?= __('Wind Direction') ?></th>
                            <th><?= __('Wind Speed') ?></th>
                            <th><?= __('Latitude') ?></th>
                            <th><?= __('Longitude') ?></th>
                            <th><?= __('Location Id') ?></th>
                            <th><?= __('Service Id') ?></th>
                            <th><?= __('Device Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('Type') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($service->data_metereological as $dataMetereological) : ?>
                        <tr>
                            <td><?= h($dataMetereological->id) ?></td>
                            <td><?= h($dataMetereological->date_time) ?></td>
                            <td><?= h($dataMetereological->temperature) ?></td>
                            <td><?= h($dataMetereological->humidity) ?></td>
                            <td><?= h($dataMetereological->precipitation) ?></td>
                            <td><?= h($dataMetereological->wind_direction) ?></td>
                            <td><?= h($dataMetereological->wind_speed) ?></td>
                            <td><?= h($dataMetereological->latitude) ?></td>
                            <td><?= h($dataMetereological->longitude) ?></td>
                            <td><?= h($dataMetereological->location_id) ?></td>
                            <td><?= h($dataMetereological->service_id) ?></td>
                            <td><?= h($dataMetereological->device_id) ?></td>
                            <td><?= h($dataMetereological->created) ?></td>
                            <td><?= h($dataMetereological->modified) ?></td>
                            <td><?= h($dataMetereological->role) ?></td>
                            <td><?= h($dataMetereological->type) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'DataMetereological', 'action' => 'view', $dataMetereological->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'DataMetereological', 'action' => 'edit', $dataMetereological->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'DataMetereological', 'action' => 'delete', $dataMetereological->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataMetereological->id)]) ?>
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