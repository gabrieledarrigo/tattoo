<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for the single page application.
 *
 * @author Gabriele D'Arrigo - @acirdesign
 */
class ApplicationController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Perform a login for a user.
     */
    public function login(Request $request) {
        return $this->app['twig']->render('admin/login.twig', array(
                    'title' => 'Login',
                    'section' => 'login'
        ));
    }

    /**
     * Render the application.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function index(Request $request) {

        return $this->app['twig']->render('application/index.twig', array(
                    'title' => 'Pliz Tattoo'
        ));
    }
    
    /**
     * Return desired template.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function loadAjaxTemplate(Request $request) {
        $template = $request->get('name');

        return $this->app['twig']->render('/application/' . $template . '.twig');
    }

}