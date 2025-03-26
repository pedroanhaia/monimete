<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $user
 */
?>
<style>
.row{
    display: flex;
    width: 100%
}
.row1{
    display: flex;
    width: 10%
    padding: 10px;
    margin: 10px;
    
}
.row2{
    display: flex;
  
    width: 100%;
    padding: 10px;
    margin: 10px;
}
.column{
    flex: 2; 
    max-width: 100%; 

}   
.main {
    display: block;
    width: 100%;
}


</style>

<div class="main">
<div class="row">
    <aside class="row1">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
        <div class="row2">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('email');
                    echo $this->Form->control('password');
                    echo $this->Form->control('type');
                    echo $this->Form->control('role');
                    echo $this->Form->control('city_id');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
</div>
</div>
