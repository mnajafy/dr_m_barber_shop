<?php
use core\helpers\Url;
use core\helpers\Html;
use core\widgets\ActiveForm;
/* @var $this core\web\View */
/* @var $form core\widgets\ActiveForm */
/* @var $model app\models\Test */
$this->title                  = 'Create';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => ['/admin/users-index']];
$this->params['breadcrumb'][] = $this->title;
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
            <a href="<?= Url::to(['users-index']) ?>" class="btn btn-dark">Return</a>
            <?= Html::submitButton('Save', ['class' => 'btn btn-brown']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>