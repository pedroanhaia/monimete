<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\City $city
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit City'), ['action' => 'edit', $city->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete City'), ['action' => 'delete', $city->id], ['confirm' => __('Are you sure you want to delete # {0}?', $city->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Cities'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New City'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="cities view content">
            <h3><?= h($city->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($city->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cod Ibge') ?></th>
                    <td><?= h($city->cod_ibge) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($city->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Role') ?></th>
                    <td><?= $city->role === null ? '' : $this->Number->format($city->role) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($city->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($city->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Obs') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($city->obs)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($city->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Locations') ?></h4>
                <?php if (!empty($city->locations)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Latitude') ?></th>
                            <th><?= __('Longitude') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('City Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($city->locations as $location) : ?>
                        <tr>
                            <td><?= h($location->id) ?></td>
                            <td><?= h($location->name) ?></td>
                            <td><?= h($location->latitude) ?></td>
                            <td><?= h($location->longitude) ?></td>
                            <td><?= h($location->description) ?></td>
                            <td><?= h($location->created) ?></td>
                            <td><?= h($location->modified) ?></td>
                            <td><?= h($location->role) ?></td>
                            <td><?= h($location->city_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Locations', 'action' => 'view', $location->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Locations', 'action' => 'edit', $location->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Locations', 'action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Users') ?></h4>
                <?php if (!empty($city->users)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Passwotd') ?></th>
                            <th><?= __('Type') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Role') ?></th>
                            <th><?= __('City Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($city->users as $user) : ?>
                        <tr>
                            <td><?= h($user->id) ?></td>
                            <td><?= h($user->name) ?></td>
                            <td><?= h($user->email) ?></td>
                            <td><?= h($user->passwotd) ?></td>
                            <td><?= h($user->type) ?></td>
                            <td><?= h($user->created) ?></td>
                            <td><?= h($user->modified) ?></td>
                            <td><?= h($user->role) ?></td>
                            <td><?= h($user->city_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $user->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $user->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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