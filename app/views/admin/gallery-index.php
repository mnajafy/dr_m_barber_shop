<?php
use core\helpers\Url;
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = 'Gallery';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<div class="container">
    <br/>
    <h3>
        Gallery
    </h3>
    <br/>
    <p>
        <a href="<?= Url::to(['admin/gallery-create']) ?>">Create</a>
        <a href="<?= Url::to(['admin/gallery-update', 'id' => 1]) ?>">Update</a>
        <a href="<?= Url::to(['admin/gallery-delete', 'id' => 1]) ?>">Delete</a>
    </p>
</div>