<?php
use core\helpers\Url;
/* @var $this core\web\View */
/* @var $models app\models\Test[] */
$this->title                  = 'Users';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<style>
    .table {width: 100%;border: 1px solid #CCC;margin-bottom: 15px;}
    .table td, .table th {border: 1px solid #CCC;padding: 5px;}
</style>
<div class="container">
    <br/>
    <p>
        <a href="<?= Url::to(['users-create']) ?>" class="btn btn-dark">Create</a>
    </p>
    <table class="table">
        <tr>
            <th>id</th>
            <th>username</th>
            <th>password</th>
            <th></th>
        </tr>
        <?php
        foreach ($models as $model) {
            ?>
            <tr>
                <td><?= $model->id ?></td>
                <td><?= $model->username ?></td>
                <td><?= $model->password ?></td>
                <td>
                    <a href="<?= Url::to(['users-view', 'id' => $model->id]) ?>" class="btn btn-brown">View</a>
                    <a href="<?= Url::to(['users-update', 'id' => $model->id]) ?>" class="btn btn-brown">Update</a>
                    <a href="<?= Url::to(['users-delete', 'id' => $model->id]) ?>" class="btn btn-dark">Delete</a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>