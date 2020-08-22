<?php
namespace core\web;
interface IdentityInterface {
    /**
     * @param string|int $id
     */
    public static function findIdentity($id);
    /**
     * @return string|int
     */
    public function getId();
}