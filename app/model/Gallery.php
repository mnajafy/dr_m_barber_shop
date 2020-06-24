<?php

namespace App\Model;

use Core\Model;
use Core\Db;

class Gallery extends Model
{
    public static function byCategory($value = null)
    {
        if ($value)
        {
            return Db::prepare('SELECT imgs.id, imgs.img, imgs.content, category.id as categoryurl, category.title as categorie 
                                FROM imgs 
                                LEFT JOIN category 
                                ON category_id = category.id 
                                WHERE category.title = ? 
                                ORDER BY imgs.id DESC', 
                                [$value],
                                Gallery::class,
                                true
                            );
        }
        else
        {
            return Db::query('SELECT imgs.id, imgs.img, imgs.content, category.id as categoryId, category.title as categoryTitle 
                                        FROM imgs 
                                        LEFT JOIN category 
                                        ON category_id = category.id ORDER BY imgs.id DESC', 
                                        Gallery::class);
        }
        
    }

    public function getUrlImg()
    {
        return 'http://localhost/dr_m_barber_shop/app/assets/img/customer/' . $this->img;
    }

    public function getLink()
    {
        return 'http://localhost/dr_m_barber_shop/gallery/' . $this->id;   
    }

    public function getLinkCategoryId()
    {
        return 'http://localhost/dr_m_barber_shop/category/' . $this->categoryId;   
    }
}

?>