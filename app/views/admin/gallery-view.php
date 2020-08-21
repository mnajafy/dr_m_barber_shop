<?php
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = $model->username;
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Gallery', 'url' => ['/admin/gallery-index']];
$this->params['breadcrumb'][] = $this->title;
?>
<div class="container">
    
</div>