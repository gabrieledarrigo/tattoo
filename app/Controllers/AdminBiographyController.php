<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for biography admin panel.
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class AdminBiographyController {

    protected $app;
    protected $modelBiography;

    /**
     * Controller dependency.
     * 
     * @param \Silex\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->modelBiography = new \Models\Biography($app);
    }

    /**
     * List all items.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function index(Request $request) {
        $results = $this->modelBiography->getAll();

        $paginator = \Services\Paginator::getPaginator($results);

        $paginator->setMaxPerPage(20)
                ->setCurrentPage($request->get('page', 1));

        return $this->app['twig']->render('admin/biography/index.php', array(
                    'title' => 'Biografia',
                    'items' => $paginator
        ));
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function insert(Request $request) {

        $title = 'Inserisci Biografia';

        return $this->app['twig']->render('admin/biography/panel.php', array(
                    'title' => $title,
                    'id' => '',
                    'action' => 'insert'
        ));
    }

    /**
     * Edit panel. Invoked for update a biography.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function edit(Request $request) {
        $id = $request->get('id');

        $result = $this->modelBiography->getById($id);

        $title = 'Modifica Biografia';

        return $this->app['twig']->render('admin/biography/panel.php', array(
                    'title' => $title,
                    'id' => $result['id'],
                    'action' => 'update'
        ));
    }

}