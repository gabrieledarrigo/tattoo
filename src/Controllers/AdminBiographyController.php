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
     * @param \Controllers\Models\AbstractModel $biographyModel
     */
    public function __construct(Application $app, \Models\AbstractModel $biographyModel) {
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

        return $this->app['twig']->render('admin/index.twig', array(
                    'title' => 'Biografia',
                    'section' => 'biography',
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

        return $this->app['twig']->render('admin/edit.twig', array(
                    'id' => '',
                    'title' => $title,
                    'section' => 'biography',
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

        return $this->app['twig']->render('admin/edit.twig', array(
                    'id' => $result['id'],
                    'title' => $title,
                    'section' => 'biography',
                    'action' => 'update'
        ));
    }

}