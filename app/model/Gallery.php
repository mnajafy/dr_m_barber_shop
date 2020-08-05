<?php
namespace App\Model;
use Core\ActiveRecord;
/**
 * Gallery
 * 
 * @property int $id
 * @property string $img
 * @property string $title
 * @property string $content
 * @property int $category_id
 * 
 * @property Category $category
 */
class Gallery extends ActiveRecord {
    public static function tablename() {
        return 'imgs';
    }
    public static function byCategory($value = null) {
        if ($value) {
            return static::runAll('
                SELECT m1.*
                FROM imgs AS m1
                INNER JOIN category m2 ON m1.category_id = m2.id AND m2.title = ?
                ORDER BY m1.id DESC
            ', [$value]);
        }
        return static::runAll('SELECT m1.* FROM imgs AS m1 ORDER BY m1.id DESC');
    }
    public function getCategory() {
        if ($this->_category === null) {
            $this->_category = Category::one($this->category_id);
        }
        return $this->_category;
    }
}