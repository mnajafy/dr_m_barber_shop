<?php
use core\helpers\Url;
use core\helpers\Html;
use core\widgets\ActiveForm;
/* @var $this core\web\View */
/* @var $form core\widgets\ActiveForm */
/* @var $model app\models\Test */
?>
<div class="container">
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <?php $form = ActiveForm::begin(['options' => ['style' => 'padding: 15px;']]) ?>
            <?= $form->field($model, 'username')->textInput() ?>
            <?= $form->field($model, 'password')->textInput() ?>
            <div>
                <a href="<?= Url::to(['users-index']) ?>" class="btn btn-dark">Return</a>
                <?= Html::submitButton('Save', ['class' => 'btn btn-brown']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>