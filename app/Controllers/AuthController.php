<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auth Controller.
 * Provide a simple authentication system to user.
 *
 * @author BRossi
 */
class AuthController {
    protected $app;
    protected $modelUsers;


    /**
     * Controller dependency.
     * @param \Silex\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
    }
    /**
     * Perform a login for a user.
     */
    public function login() {
        
    }
    
    /**
     * Perform a logout for a user.
     */
    public function logout() {
        
    }
    
    /**
     * Render authentication form?!?
     */
    public function access() {
        
    }

}