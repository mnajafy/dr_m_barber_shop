<?php
namespace App\Model;
use Core\Model;
/**
 * Gallery
 * 
 * @property int $id
 * @property string $img
 * @property string $title
 * @property string $content
 * @property int $category_id
 */
class Gallery extends Model {
    public static function tablename() {
        return 'imgs';
    }
    public static function byCategory($value = null) {
        if ($value) {
            return static::runAll('
                SELECT imgs.id, imgs.img, imgs.content, category.id as categoryurl, category.title as categoryTitle 
                FROM imgs 
                LEFT JOIN category ON category_id = category.id AND category.title = ? 
                ORDER BY imgs.id DESC
            ', [$value]);
        }
        return static::runAll('
            SELECT imgs.id, imgs.img, imgs.content, category.id as categoryId, category.title as categoryTitle
            FROM imgs
            LEFT JOIN category ON category_id = category.id
            ORDER BY imgs.id DESC
        ');
    }
}