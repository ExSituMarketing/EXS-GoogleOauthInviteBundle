<?php

namespace EXS\GoogleOauthInviteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser;

/**
 * ExsUser
 *
 * @ORM\Table(name="exsuser")
 * @ORM\Entity(repositoryClass="EXS\GoogleOauthInviteBundle\Entity\Repository\ExsUserRepository")
 * @UniqueEntity("email")
 */
class ExsUser extends OAuthUser implements EquatableInterface, \Serializable {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="realname", type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $realname;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $nickname;

    /**
     * @ORM\Column(name="googleAccessToken", type="string", length=255, nullable=true) 
     */
    protected $googleAccessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="googleId", type="string", length=255, unique=true, nullable=true, options={"default" = null})
     */
    protected $googleId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true, options={"default" = null})
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="googleAvatar", type="string", length=255, unique=true, nullable=true, options={"default" = null})
     */
    protected $googleAvatar;

    /**
     * @ORM\Column(name="isActive", type="boolean", options={"default" = 0})
     */
    protected $isActive;

    /**
     * @ORM\Column(name="lastLogin", type="datetime", options={"default" = 0})
     */
    protected $lastLogin;

    public function __construct() {

        $this->isActive = true;
        $this->lastLogin = new \DateTime();
    }

    /**
     * @param string $nickname
     */
    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getNickname() {
        return $this->nickname;
    }

    /**
     * @param string $realname
     */
    public function setRealname($realname) {
        $this->realname = $realname;
    }

    /**
     * @return string
     */
    public function getRealname() {
        return $this->realname;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId($googleId) {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getGoogleId() {
        return $this->googleId;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsActive() {
        return $this->isActive;
    }

    public function getGoogleAccessToken() {
        return $this->googleAccessToken;
    }

    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function setGoogleAccessToken($googleAccessToken) {
        $this->googleAccessToken = $googleAccessToken;
    }

    public function setLastLogin($lastLogin) {
        $this->lastLogin = $lastLogin;
    }

    public function getGoogleAvatar() {
        return $this->googleAvatar;
    }

    public function setGoogleAvatar($googleAvatar) {
        $this->googleAvatar = $googleAvatar;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user) {
        if ((int) $this->getEmail() === $user->getEmail()) {
            return true;
        }

        return false;
    }

}
