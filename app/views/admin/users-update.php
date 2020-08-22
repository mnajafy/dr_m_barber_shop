<?php
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = 'Update';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => ['/admin/users-index']];
$this->params['breadcrumb'][] = ['label' => $model->username, 'url' => ['/admin/users-view', 'id' => $model->id]];
$this->params['breadcrumb'][] = $this->title;
echo $this->render('users-form', ['model' => $model]);