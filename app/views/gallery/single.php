<?php
/* @var $this core\web\View */
/* @var $model \app\models\Gallery */
$this->title                  = $model->title;
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = ['label' => 'Gallery', 'url' => ['gallery/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<section class="section">
    <div class="container">
        <div class="section-title">
            <h3><?= $model->title ?></h3>
            <hr>
        </div>
        <div class="row">
            <div class="col-6 p-5">
                <img src="<?= Framework::getAlias('@web/assets/img/customer/' . $model->img) ?>" alt=""/>
            </div>
            <div class="col-6 p-5">
                <?= $model->content ?>
            </div>
        </div>
    </div>
</section>