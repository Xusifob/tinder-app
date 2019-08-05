<?php

/**
 * Security Service
 */


namespace Xusifob\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Xusifob\Route;
use Xusifob\Services\Security;

/**
 *
 * This service will be used to check everything linked to logged in user and security.
 *
 * It can return if the user is allowed to get & update an entity, and if the user is currently logged it.
 *
 * Class Security
 * @package Xusifob\Services
 */
final class TinderSecurity extends Security
{


    /**
     *
     */
    private $token = null;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the current user id
     *
     * @return bool|int|object
     */
     public function getCurrentUser()
     {
         return $this->token;
     }


    /**
     * Return if a user is logged in or not
     *
     * @return bool
     */
    public function isLoggedIn() : bool
    {
        return self::getCurrentUser() !== null;
    }


    /**
     *
     * Return if the user is the owner of the entity and if he is allowed to fetch & update it
     *
     * @param object         $entity     The entity you want to test
     * @param null|int      $user_id    The user you want to test your entity against. default: current user id
     *
     * @return bool
     */
    public function isOwner($entity,$user_id = null) : bool
    {
        return true;
    }


    /**
     *
     * The user can view this page
     *
     * @param Route $route
     * @param null $user
     * @return bool
     */
    public function canView(Route $route, $user = null) : bool
    {

        if($route->isVisible()) {
            return  true;
        } else {
            return  $this->isLoggedIn();
        }

        return false;
    }


    /**
     * Redirect the user if he is not logged in
     *
     * @param $url
     */
    public function redirectIfNotLoggedIn($url)
    {
        if(!$this->isLoggedIn()) {
            $response = new RedirectResponse($url);
            $response->send();
        }
    }

}