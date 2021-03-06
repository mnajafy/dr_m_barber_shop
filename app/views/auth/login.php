<?php
use core\helpers\Html;
use core\widgets\ActiveForm;
/* @var $this \core\web\View */
/* @var $form \core\widgets\ActiveForm */
/* @var $model \app\models\LoginForm */
$this->title = 'Login';
?>
<div class="auth-login">
    <div class="container">
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <?php $form = ActiveForm::begin(['options' => ['style' => 'padding: 15px;']]) ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password') ?>
                <?= Html::submitButton('Login', ['class' => 'btn btn-dark']) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>