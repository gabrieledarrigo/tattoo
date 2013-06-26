<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for tattoo admin panel.
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class AdminTattooController {

    protected $app;
    protected $modelTattoo;

    /**
     * Controller dependency.
     * 
     * @param \Silex\Application $app
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->modelTattoo = new \Models\Tattoo($app);
    }

    /**
     * List all items.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function index(Request $request) {
        $results = $this->modelTattoo->getAll();

        $paginator = \Services\Paginator::getPaginator($results);

        $paginator->setMaxPerPage(20)
                ->setCurrentPage($request->get('page', 1));

        return $this->app['twig']->render('admin/tattoo/index.php', array(
                    'title' => 'Tattoo',
                    'items' => $paginator
        ));
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function insert(Request $request) {

        $title = 'Inserisci Tattoo';

        return $this->app['twig']->render('admin/tattoo/uploader.php', array(
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

        $result = $this->modelTattoo->getById($id);

        $title = 'Modifica Tattoo';

        return $this->app['twig']->render('admin/tattoo/panel.php', array(
                    'title' => $title,
                    'id' => $result['id'],
                    'action' => 'update'
        ));
    }

}