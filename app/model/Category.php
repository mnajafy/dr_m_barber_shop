<?php

namespace App\Model;

use Core\Db;
use Core\Model;

class Category extends Model
{

    public function getUrl()
    {
        return 'http://localhost/dr_m_barber_shop/gallery&category=' . $this->title;   
    }
}

?>