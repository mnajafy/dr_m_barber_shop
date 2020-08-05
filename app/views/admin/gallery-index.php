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

<section class="section">
    <div class="container">
        <div class="section-title center">
            <h4>WELCOME TO THE BOARD</h4>
            <h3>LOGIN</h3>
            <hr>
        </div> <!-- end section title -->
        <?php $form  = ActiveForm::begin(['options' => ['style' => 'padding: 15px;']]) ?>
        <div class="row">
            <div class="col-4 mx-auto">
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password') ?>
                <?= Html::submitButton('Login', ['class' => 'btn btn-brown']) ?>
            </div>
        </div> <!-- end row -->
        <?php ActiveForm::end() ?>
    </div> <!-- end container -->
</section> <!-- end section -->
