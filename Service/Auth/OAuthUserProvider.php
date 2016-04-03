<?php

namespace EXS\GoogleOauthInviteBundle\Service\Auth;

use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OAuthUserProvider extends OAuthUserProvider {

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * The entity used to store user credentials
     * Update this or make it a parameter or use the bundled 
     * entity and repository
     * @var object
     */
    protected $userEntityClass;

    public function __construct($manager) {
        $this->manager = $manager;
        $this->userEntityClass = 'EXS\GoogleOauthInviteBundle\Entity\ExsUser';
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername($email) {
        // Repository (with cache)
        $user = $this->manager->getRepository('GoogleOauthInviteBundle:ExsUser')->getUserByEmail($email);

        // Make sure we return a User object - even if we didnt auth
        if (!$user instanceof $this->userEntityClass) {
            $user = new $this->userEntityClass;
        }
        return $user;
    }

    /**
     * Persist the data that interests us from the API
     * 
     * @param UserInterface $user
     * @param UserResponseInterface $response
     * @return UserInterface
     */
    public function updateUserByOAuthUserResponse(UserInterface $user, UserResponseInterface $response) {
        if (empty($user->getRealname())) {
            $user->setRealname($response->getRealName());
        }

        if ($user->getUsername() !== $response->getEmail()) {
            $user->setUsername($response->getEmail());
        }

        if (empty($user->getNickname())) {
            $user->setNickname($response->getNickname());
        }

        if (empty($user->getGoogleId())) {
            $user->setGoogleId($response->getUsername());
        }

        if ($user->getGoogleAccessToken() !== $response->getAccessToken()) {
            $user->setGoogleAccessToken($response->getAccessToken());
            // New token means new login so set the time
            $user->setLastLogin(new \DateTime('now'));
        }

        if ($user->getGoogleAvatar() !== $response->getProfilePicture()) {
            $user->setGoogleAvatar($response->getProfilePicture());
        }

        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {

        // Drop it since not authed
        if (empty($response->getEmail())) {
            return new $this->userEntityClass();
        }

        // Get the user from our records by email
        $user = $this->loadUserByUsername($response->getEmail());

        if ($user->getId() == 0) {
            // Only allow invited users
            throw new AccessDeniedHttpException("We're sorry but access is by invitation only.");
        } else {
            // Update the user
            $this->updateUserByOAuthUserResponse($user, $response);
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class) {
        return $class === $this->userEntityClass;
    }

}
