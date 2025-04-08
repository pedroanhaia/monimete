<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\City> $cities
 */
?>

<div class="cities index content">

    <?= $this->Html->link(__('New City'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <?= $this->Html->link(__('testeadd'), '#', ['class' => 'button float-right', 'id' => 'newCityAjax']) ?>
    <?= $this->Html->link(__('testeremovedupe'), '#', ['class' => 'button float-right', 'id' => 'removeDuplicates']) ?>

    <h3><?= __('Cities') ?></h3>
      
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('cod_ibge') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('role') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?= $this->Number->format($city->id) ?></td>
                    <td><?= h($city->name) ?></td>
                    <td><?= h($city->cod_ibge) ?></td>
                    <td><?= h($city->created) ?></td>
                    <td><?= h($city->modified) ?></td>
                    <td><?= $city->role === null ? '' : $this->Number->format($city->role) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $city->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $city->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $city->id], ['confirm' => __('Are you sure you want to delete # {0}?', $city->id)]) ?>
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
<script>
        $(document).ready(function () {
        
            document.getElementById('newCityAjax').addEventListener('click', function(event) {
                event.preventDefault(); // Evita o comportamento padrão do link
                newCityAjax();
            });
            document.getElementById('removeDuplicates').addEventListener('click', function(event) {
                event.preventDefault(); // Evita o comportamento padrão do link
                AjaxRemove();
            });
            function newCityAjax(){
                $.ajax({
                    url: '<?php $link = $this->Url->build(['controller' => 'Cities', 'action' => 'addinpedata'],['fullBase' => true]);
                    echo $link;
                    ?>',
                    type: 'POST',
            
                });
            }
            function AjaxRemove(){
                $.ajax({
                    url: '<?php $link = $this->Url->build(['controller' => 'Cities', 'action' => 'deletatudo'],['fullBase' => true]);
                    echo $link;
                    ?>',
                    type: 'POST',
            
                });
            }     
    });
</script>