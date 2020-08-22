<?php
/* @var $this core\web\View */
/* @var $exception Exception */
$this->title = $exception->getMessage();
$this->params['breadcrumb'] = [];
$this->params['breadcrumb'][] = ['label' => 'Home', 'url' => ['/home/index']];
?>
<section class="section">
    <div class="container">
        <h3><?= $exception->getMessage() ?></h3>
        <br/>
        <div><?= $exception->getFile() . ':' . $exception->getLine() ?></div>
    </div>
</section>