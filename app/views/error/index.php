<?php
/* @var $this \Core\View */
/* @var $title string */
/* @var $message string */
$this->title = $title;
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = $title;
?>
<section class="section">
    <div class="container">
        <h3><?= $title ?></h3>
        <div><?= $message ?></div>
    </div>
</section>