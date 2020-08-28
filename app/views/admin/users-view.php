<?php
use core\helpers\Html;
use core\widgets\DetailView;
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = $model->username;
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => ['/admin/users-index']];
$this->params['breadcrumb'][] = $this->title;
?>
<style>
    .table {width: 100%;border: 1px solid #CCC;margin-bottom: 15px;}
    .table td, .table th {border: 1px solid #CCC;padding: 5px;}
</style>
<div class="container">
    <br/>
    <p>
        <?= Html::a('Return', ['users-index'], ['class' => 'btn btn-dark']) ?>
        <?= Html::a('Create', ['users-create'], ['class' => 'btn btn-brown']) ?>
        <?= Html::a('Update', ['users-update', 'id' => $model->id], ['class' => 'btn btn-brown']) ?>
        <?= Html::a('Delete', ['users-delete', 'id' => $model->id], ['class' => 'btn btn-dark', 'data' => ['method' => 'post', 'confirm' => 'Are you sure you want to delete this item?']]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'table-responsive'],
        'tableOptions' => ['class' => 'table'],
        'attributes' => [
            'id',
            'username',
            'password',
        ]
    ]) ?>
</div>