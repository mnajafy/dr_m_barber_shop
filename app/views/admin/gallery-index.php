<?php
use Core\ActiveForm;
use Core\Html;
/* @var $this \Core\View */
/* @var $form \Core\ActiveForm */
/* @var $model \App\Model\Test */
$this->title                  = 'asd';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<div class="container">
    <?php $form  = ActiveForm::begin(['options' => ['style' => 'padding: 15px;']]) ?>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-4">
            <?= $form->field($model, 'username') ?>
        </div>
    </div>
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-4">
            <?= $form->field($model, 'password') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <?= Html::input('submit', null, 'Login') ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>