<?php

return [
    '/' => ['App\Controller\HomeController', 'home'],
    'gallery' => ['App\Controller\GalleryController', 'index'],
    'gallery/{id}' => ['App\Controller\GalleryController', 'single'],
    'hairdresser' => ['App\Controller\HairdresserController', 'index'],
];
?>