<?php
/* @var $this core\web\View */
$this->title = 'Home';
$this->params['breadcrumb'][] = ['label' => $this->title, 'url' => ['/home/index']];
echo $this->title;