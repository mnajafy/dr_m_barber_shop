<?php
/* @var $this core\web\View */
/* @var $title string */
/* @var $file string */
/* @var $line int */
/* @var $message string */
$this->title = $title;
$this->params['breadcrumb'] = [];
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['home/index']];
$this->params['breadcrumb'][] = $title;
?>
<section class="section">
    <div class="container">
        <h3><?= $title ?></h3>
        <br/>
        <div><?= $message ?></div>
        <div><?= $file . ':' . $line ?></div>
    </div>
</section>