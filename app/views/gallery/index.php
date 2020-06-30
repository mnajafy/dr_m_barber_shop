<?php
/* @var $this \Core\View */
$this->title                  = 'Gallery';
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = 'Gallery';
?>
<section class="py-5">
    <div class="container">

        <div class="section-footer">
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
        <div class="row">
            <div class="col">
                <div class="row">
                    <?php foreach ($dataGallery as $key): ?>
                        <div class="col-2 py-4">
                            <h3><a href="<?= $key->linkCategoryId; ?>"><?= $key->categoryTitle; ?></a></h3>
                            <div class="card-img magnifier-parent">
                                <img src="<?= Framework::getAlias('@web/assets/img/customer/' . $key->img) ?>" alt="">
                                <a href="<?= $key->link; ?>" class="magnifier btn rounded-circle"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-2">
                <ul>
                    <?php foreach ($dataCategory as $key): ?>
                        <li><a href="<?= $key->url; ?>"><?= $key->title; ?></a></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>

        <div class="section-footer">
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
    </div>
</section>