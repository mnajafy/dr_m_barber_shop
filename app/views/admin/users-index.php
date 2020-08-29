<?php
use core\helpers\Url;
use core\helpers\Html;
use core\grid\GridView;
/* @var $this core\web\View */
/* @var $dataProvider \core\data\ActiveDataProvider */
$this->title                  = 'Users';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<style>
    .table {width: 100%;border: 1px solid #CCC;margin-bottom: 15px;}
    .table td, .table th {border: 1px solid #CCC;padding: 5px;}
</style>
<div class="container">
    <br/>
    <p>
        <?= Html::a('Create', ['users-create'], ['class' => 'btn btn-dark'])?>
    </p>
    <?= GridView::widget([
        'options' => ['class' => 'table-responsive', 'style' => 'margin-bottom: 15px'],
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class'=> 'core\grid\SerialColumn'],
            'username',
            'password',
            [
                'class'=> 'core\grid\ActionColumn',
                'buttonOptions' => ['class' => 'btn btn-brown'],
                'urlCreator' => function ($name, $model, $key, $index, $actionColumn) {
                    if ($name === 'view') {
                        return Url::to(['users-view', 'id' => $key]);
                    }
                    if ($name === 'update') {
                        return Url::to(['users-update', 'id' => $key]);
                    }
                    if ($name === 'delete') {
                        return Url::to(['users-delete', 'id' => $key]);
                    }
                    return null;
                }
            ],
        ]
    ]) ?>
</div>