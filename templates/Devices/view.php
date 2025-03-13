<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Device $device
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Device'), ['action' => 'edit', $device->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Device'), ['action' => 'delete', $device->id], ['confirm' => __('Are you sure you want to delete # {0}?', $device->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Devices'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Device'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="devices view content">
            <h3><?= h($device->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($device->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Model') ?></th>
                    <td><?= h($device->model) ?></td>
                </tr>
                <tr>
                    <th><?= __('Producer') ?></th>
                    <td><?= h($device->producer) ?></td>
                </tr>
                <tr>
                    <th><?= __('Location') ?></th>
                    <td><?= $device->hasValue('location') ? $this->Html->link($device->location->name, ['controller' => 'Locations', 'action' => 'view', $device->location->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Img') ?></th>
                    <td><?= h($device->img) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($device->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type') ?></th>
                    <td><?= $device->type === null ? '' : $this->Number->format($device->type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $device->role === null ? '' : $this->Number->format($device->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($device->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($device->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($device->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Data Metereological') ?></h4>
                <?php if (!empty($device->data_metereological)) : ?>
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
                        <?php foreach ($device->data_metereological as $dataMetereological) : ?>
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
                <h4><?= __('Related Data Satellite') ?></h4>
                <?php if (!empty($device->data_satellite)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Date Time') ?></th>
                            <th><?= __('Quality Signal') ?></th>
                            <th><?= __('Latitude') ?></th>
                            <th><?= __('Longitude') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Device Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($device->data_satellite as $dataSatellite) : ?>
                        <tr>
                            <td><?= h($dataSatellite->id) ?></td>
                            <td><?= h($dataSatellite->date_time) ?></td>
                            <td><?= h($dataSatellite->quality_signal) ?></td>
                            <td><?= h($dataSatellite->latitude) ?></td>
                            <td><?= h($dataSatellite->longitude) ?></td>
                            <td><?= h($dataSatellite->type) ?></td>
                            <td><?= h($dataSatellite->device_id) ?></td>
                            <td><?= h($dataSatellite->created) ?></td>
                            <td><?= h($dataSatellite->modified) ?></td>
                            <td><?= h($dataSatellite->role) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'DataSatellite', 'action' => 'view', $dataSatellite->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'DataSatellite', 'action' => 'edit', $dataSatellite->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'DataSatellite', 'action' => 'delete', $dataSatellite->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dataSatellite->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Logs') ?></h4>
                <?php if (!empty($device->logs)) : ?>
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
                        <?php foreach ($device->logs as $log) : ?>
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
                <h4><?= __('Related Settings') ?></h4>
                <?php if (!empty($device->settings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Device Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Value') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Role') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($device->settings as $setting) : ?>
                        <tr>
                            <td><?= h($setting->id) ?></td>
                            <td><?= h($setting->device_id) ?></td>
                            <td><?= h($setting->name) ?></td>
                            <td><?= h($setting->value) ?></td>
                            <td><?= h($setting->description) ?></td>
                            <td><?= h($setting->created) ?></td>
                            <td><?= h($setting->modified) ?></td>
                            <td><?= h($setting->type) ?></td>
                            <td><?= h($setting->role) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Settings', 'action' => 'view', $setting->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Settings', 'action' => 'edit', $setting->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Settings', 'action' => 'delete', $setting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $setting->id)]) ?>
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