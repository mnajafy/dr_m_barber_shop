<?php
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = 'Create';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => ['/admin/users-index']];
$this->params['breadcrumb'][] = $this->title;
echo $this->render('users-form', ['model' => $model]);