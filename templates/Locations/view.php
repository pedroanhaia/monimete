<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Location $location
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Location'), ['action' => 'edit', $location->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Location'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Locations'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Location'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="locations view content">
            <h3><?= h($location->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($location->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('City') ?></th>
                    <td><?= $location->hasValue('city') ? $this->Html->link($location->city->name, ['controller' => 'Cities', 'action' => 'view', $location->city->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($location->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Latitude') ?></th>
                    <td><?= $location->latitude === null ? '' : $this->Number->format($location->latitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Longitude') ?></th>
                    <td><?= $location->longitude === null ? '' : $this->Number->format($location->longitude) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $location->role === null ? '' : $this->Number->format($location->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($location->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($location->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($location->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Data Metereological') ?></h4>
                <?php if (!empty($location->data_metereological)) : ?>
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
                        <?php foreach ($location->data_metereological as $dataMetereological) : ?>
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
            <div class="related">
                <h4><?= __('Related Devices') ?></h4>
                <?php if (!empty($location->devices)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Model') ?></th>
                            <th><?= __('Producer') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('Location Id') ?></th>
                            <th><?= __('Img') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($location->devices as $device) : ?>
                        <tr>
                            <td><?= h($device->id) ?></td>
                            <td><?= h($device->name) ?></td>
                            <td><?= h($device->type) ?></td>
                            <td><?= h($device->model) ?></td>
                            <td><?= h($device->producer) ?></td>
                            <td><?= h($device->description) ?></td>
                            <td><?= h($device->created) ?></td>
                            <td><?= h($device->modified) ?></td>
                            <td><?= h($device->role) ?></td>
                            <td><?= h($device->location_id) ?></td>
                            <td><?= h($device->img) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Devices', 'action' => 'view', $device->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Devices', 'action' => 'edit', $device->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Devices', 'action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete # {0}?', $device->id)]) ?>
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