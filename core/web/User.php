<?php
namespace core\web;
use Exception;
use Framework;
use core\base\BaseObject;
/**
 * User
 * 
 * @property IdentityInterface|null $identity
 * @property-read string|int $id
 * @property-read bool $isGuest
 */
class User extends BaseObject {
    /**
     * @var string the class name of the [[identity]] object.
     */
    public $identityClass;
    /**
     * @var string
     */
    public $idParam = '__id';
    /**
     * @param IdentityInterface $identity
     * @return bool
     */
    public function login($identity) {
        if (!$identity instanceof IdentityInterface) {
            throw new Exception('$identity should be instance of IdentityInterface');
        }
        $this->switchIdentity($identity);
        return !$this->getIsGuest();
    }
    /**
     * @return bool
     */
    public function logout() {
        $this->switchIdentity();
        return $this->getIsGuest();
    }
    /**
     * @param IdentityInterface|null $identity
     */
    public function switchIdentity($identity = null) {
        $this->_identity = $identity;
        Framework::$app->getSession()->remove($this->idParam);
        if ($identity !== null) {
            Framework::$app->getSession()->set($this->idParam, $identity->getId());
        }
    }
    /**
     * @return bool
     */
    public function getIsGuest() {
        return $this->getIdentity() === null;
    }
    /**
     * @return string|int
     */
    public function getId() {
        $identity = $this->getIdentity();
        return $identity === null ? null : $identity->getId();
    }
    /**
     * @var IdentityInterface|null|false
     */
    public $_identity = false;
    /**
     * @param IdentityInterface $identity
     */
    public function setIdentity($identity) {
        $this->_identity = $identity;
    }
    /**
     * @return IdentityInterface|null
     */
    public function getIdentity() {
        if ($this->_identity === false) {
            $id = Framework::$app->getSession()->get($this->idParam);
            $identity = null;
            if ($id !== null) {
                /* @var $class IdentityInterface */
                $class    = $this->identityClass;
                $identity = $class::findIdentity($id);
            }
            $this->_identity = $identity;
        }
        return $this->_identity;
    }
}