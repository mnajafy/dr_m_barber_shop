<?php
/* @var $this \Core\View */
/* @var $model \App\Model\Gallery */
$this->title = 'Single';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = ['label' => 'Gallery', 'url' => ['gallery/index']];
$this->params['breadcrumb'][] = 'Single';
?>
<section class="section">
    <div class="container">
        <?php if ($model): ?>
            <div class="section-title">
                <h3><?= $model->title ?></h3>
                <hr>
            </div>
            <div class="row">
                <div class="col-6 p-5"><img src="<?= $model->img ?>" alt=""></div>
                <div class="col-6 p-5"><p><?= $model->content ?></p></div>
            </div>
        <?php else: ?>
            <div class="section-title">
                <h3>Article introuvable</h3>
                <hr>
            </div>
        <?php endif ?>
    </div>
</section>