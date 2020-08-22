<?php
use core\helpers\Url;
/* @var $this core\web\View */
/* @var $model app\models\Test */
$this->title                  = $model->username;
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = ['label' => 'Users', 'url' => ['/admin/users-index']];
$this->params['breadcrumb'][] = $this->title;
?>
<style>
    .table {width: 100%;border: 1px solid #CCC;margin-bottom: 15px;}
    .table td, .table th {border: 1px solid #CCC;padding: 5px;}
</style>
<div class="container">
    <br/>
    <p>
        <a href="<?= Url::to(['users-index']) ?>" class="btn btn-dark">Return</a>
        <a href="<?= Url::to(['users-create']) ?>" class="btn btn-brown">Create</a>
        <a href="<?= Url::to(['users-update', 'id' => $model->id]) ?>" class="btn btn-brown">Update</a>
        <a href="<?= Url::to(['users-delete', 'id' => $model->id]) ?>" class="btn btn-dark">Delete</a>
    </p>
    <table class="table">
        <tr>
            <th>id</th>
            <td><?= $model->id ?></td>
        </tr>
        <tr>
            <th>username</th>
            <td><?= $model->username ?></td>
        </tr>
        <tr>
            <th>password</th>
            <td><?= $model->password ?></td>
        </tr>
    </table>
</div>