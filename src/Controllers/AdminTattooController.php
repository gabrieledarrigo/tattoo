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
     * @param \Controllers\Models\AbstractModel $modelTattoo
     */
    public function __construct(Application $app, \Models\AbstractModel $modelTattoo) {
        $this->app = $app;
        $this->modelTattoo = $modelTattoo;
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

        return $this->app['twig']->render('admin/index.twig', array(
                    'title' => 'Tattoo',
                    'section' => 'tattoo',
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

        return $this->app['twig']->render('admin/uploader.twig', array(
                    'id' => '',
                    'title' => $title,
                    'section' => 'tattoo',
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

        return $this->app['twig']->render('admin/edit.twig', array(
                    'id' => $result['id'],
                    'title' => $title,
                    'section' => 'tattoo',
                    'action' => 'update'
        ));
    }

}