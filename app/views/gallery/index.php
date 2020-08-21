<?php
use core\helpers\Url;
/* @var $this core\web\View */
$this->title                  = 'Gallery';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
$this->params['breadcrumb'][] = $this->title;
?>
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="row">
                    <?php
                    foreach ($dataGallery as $key) {
                        ?>
                        <div class="col-2 py-4">
                            <h3><a href="<?= Url::to(['gallery/single', 'id' => $key->id]) ?>"><?= $key->title ?></a></h3>
                            <div class="card-img magnifier-parent">
                                <img src="<?= Framework::getAlias('@web/assets/img/customer/' . $key->img) ?>" alt="">
                                <a href="<?= Url::to(['gallery/single', 'id' => $key->id]) ?>" class="magnifier btn rounded-circle"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-2">
                <ul>
                    <li><a href="<?= Url::to(['gallery/index']) ?>">All</a></li>
                    <?php
                    foreach ($dataCategory as $category) {
                        ?>
                        <li><a href="<?= Url::to(['gallery/index', 'category' => $category->title]) ?>"><?= $category->title; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="section-footer hidden">
            <ul class="pagination center">
                <li>
                    <a href="#">«</a>
                </li>
                <li>
                    <a href="#">1</a>
                </li>
                <li>
                    <a href="#">2</a>
                </li>
                <li>
                    <a href="#">3</a>
                </li>
                <li>
                    <a href="#">4</a>
                </li>
                <li>
                    <a href="#">5</a>
                </li>
                <li>
                    <a href="#">»</a>
                </li>
            </ul>
        </div>
        <div class="section-footer hidden">
            <ul class="pagination">
                <li class="disabled">
                    <a href="#">«</a>
                </li>
                <li>
                    <a href="#">1</a>
                </li>
                <li>
                    <a href="#">2</a>
                </li>
                <li class="active">
                    <a href="#">3</a>
                </li>
                <li>
                    <a href="#">4</a>
                </li>
                <li>
                    <a href="#">5</a>
                </li>
                <li>
                    <a href="#">»</a>
                </li>
            </ul>
        </div>
    </div>
</section>