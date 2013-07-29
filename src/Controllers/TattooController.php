<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tattoo Controller.
 * Handle all request about tattoo entities.
 * @author Gabriele D'Arrigo - @acirdesign
 */
class TattooController {

    protected $app;
    protected $modelTattoo;

    /**
     * Controller dependency.
     * 
     * @param \Silex\Application $app
     * @param \Models\AbstractModel $modelTattoo
     */
    public function __construct(Application $app, \Models\AbstractModel $modelTattoo) {

        $this->app = $app;

        $this->modelTattoo = $modelTattoo;
    }

    /**
     * Retrieve all item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function getAll(Request $request) {

        $results = $this->modelTattoo->getAll();

        $paginator = \Services\Paginator::getPaginator($results);

        $paginator->setMaxPerPage(20)
                ->setCurrentPage($request->get('page', 1));

        $totalItems = $paginator->getNbResults();
        $totalPages = $paginator->getNbPages();
        $currentResults = $paginator->getCurrentPageResults();

        return $this->app->json(array(
                    'page' => $request->get('page', 1),
                    'totalItems' => $totalItems,
                    'totalPages' => $totalPages,
                    'items' => $currentResults
        ));
    }

    /**
     * Retrieve a single item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function getItem(Request $request) {

        $id = $request->get('id');

        if (!$id) {
            return $this->app->json(array('message' => 'Specify an id parameter'), 404);
        }

        $results = $this->modelTattoo->getById($id);

        return $this->app->json($results);
    }

    /**
     * Insert an item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function insertItem(Request $request) {
        //Titles can ben empty.
        $titles = $request->get('title');

        // If file was submitted with the form than file handler is invoked.
        $files = $request->files->get('file');

        $validator = new \Services\FileValidator($this->app);

        $fileHandler = new \Services\FileHandler('/tattoo');

        if (isset($files)) {

            for ($i = 0; $i < count($files); $i++) {

                $params = $validator->validate(array(
                    'title' => $titles[$i],
                    'description' => '',
                    'image' => $fileHandler->handle($files[$i]),
                    'published' => 'SI',
                    'date' => date('Y-m-d H:i:s', time())
                ));

                $insert[] = $this->modelTattoo->insert($params);
            }
        }

        if (!$insert) {
            return $this->app->json(array(
                        'code' => 404,
                        'status' => 'error',
                        'message' => 'Error in insert statement', 404));
        } else {
            return $this->app->json(array(
                        'code' => 200,
                        'status' => 'ok',
                        'message' => 'Insert statement was succesfull', 200));
        }
    }

    /**
     * Update an item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function updateItem(Request $request) {
        $params = $request->request->all();

        if (!isset($params['id'])) {
            return $this->app->json(array('message' => 'Specify an id parameter'), 404);
        }

        // If file was submitted with the form than file handler is invoked.
        $file = $request->files->get('file');

        if (isset($file)) {
            $fileHandler = new \Services\FileHandler('/tattoo');

            $params['image'] = $fileHandler->handle($file);
        }

        $result = $this->modelTattoo->update($params['id'], $params);

        if (!$result) {
            return $this->app->json(array(
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Error in update statement', 500));
        } else {
            return $this->app->json(array(
                        'code' => 200,
                        'status' => 'ok',
                        'message' => 'Update statement was succesfull', 200));
        }
    }

    /**
     * Delete an item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function deleteItem(Request $request) {
        $id = $request->get('id');

        if (!$id) {
            return $this->app->json(array('message' => 'Specify an id parameter'), 500);
        }

        $result = $this->modelTattoo->delete($id);

        if (!$result) {
            return $this->app->json(array(
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Error in delete statement', 500));
        } else {
            return $this->app->json(array(
                        'code' => 200,
                        'status' => 'ok',
                        'message' => 'Delete statement was succesfull', 200));
        }
    }

}