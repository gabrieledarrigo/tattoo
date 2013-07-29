<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for sketch admin panel.
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class AdminSketchController {

    protected $app;
    protected $modelSketch;

    /**
     * Controller dependency.
     * 
     * @param \Silex\Application $app
     * @param \Controllers\Models\AbstractModel $sketchModel
     */
    public function __construct(Application $app, \Models\AbstractModel $sketchModel) {
        $this->app = $app;
        $this->modelSketch = $sketchModel;
    }

    /**
     * List all items.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function index(Request $request) {
        $results = $this->modelSketch->getAll();

        $paginator = \Services\Paginator::getPaginator($results);

        $paginator->setMaxPerPage(20)
                ->setCurrentPage($request->get('page', 1));

        return $this->app['twig']->render('admin/index.twig', array(
                    'title' => 'Sketch',
                    'section' => 'sketch',
                    'items' => $paginator
        ));
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function insert(Request $request) {

        $title = 'Inserisci uno Sketch';

        return $this->app['twig']->render('admin/uploader.twig', array(
                    'id' => '',
                    'title' => $title,
                    'section' => 'sketch',
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

        $result = $this->modelSketch->getById($id);

        $title = 'Modifica Sketch';

        return $this->app['twig']->render('admin/edit.twig', array(
                    'id' => $result['id'],
                    'title' => $title,
                    'section' => 'sketch',
                    'action' => 'update'
        ));
    }

}