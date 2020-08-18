<?php
use core\helpers\Html;
use core\widgets\ActiveForm;
/* @var $this core\web\View */
/* @var $form core\widgets\ActiveForm */
/* @var $model app\models\Test */
//$this->title                  = 'Update';
//$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
//$this->params['breadcrumb'][] = ['label' => 'Gallery', 'url' => ['admin/gallery-index']];
//$this->params['breadcrumb'][] = ['label' => $model->username, 'url' => ['admin/gallery-view', 'id' => $model->id]];
//$this->params['breadcrumb'][] = $this->title;
?>
<div class="container">
    <?php $form  = ActiveForm::begin(['options' => ['style' => 'padding: 15px;']]) ?>
    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'username') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'password') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <?= Html::submitButton('Login', ['class' => 'btn btn-brown']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>