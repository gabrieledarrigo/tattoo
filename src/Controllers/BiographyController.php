<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Biography Controller.
 * Handle all request about biography entities.
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class BiographyController {

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
        $this->modelBiography = $biographyModel;
    }

    /**
     * Retrieve all item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function getAll(Request $request) {
        $results = $this->modelBiography->getAll();

        return $this->app->json($results);
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

        $results = $this->modelBiography->getById($id);

        return $this->app->json($results);
    }

    /**
     * Insert an item.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function insertItem(Request $request) {

        $validator = new \Services\BiographyValidator($this->app);

        $params = $validator->validate(array(
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'published' => 'SI',
            'date' => date('Y-m-d H:i:s', time())
        ));

        if (isset($params['error'])) {
            return $this->app->json($params, 500);
        }

        // If file was submitted with the form than file handler is invoked.
        $file = $request->files->get('file');

        if (isset($file)) {
            $fileHandler = new \Services\FileHandler('/biography');

            $params['image'] = $fileHandler->handle($file);
        }

        $insert = $this->modelBiography->insert($params);

        if (!$insert) {
            return $this->app->json(array(
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Error in insert statement', 500));
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
            return $this->app->json(array('message' => 'Specify an id parameter'), 500);
        }

        // If file was submitted with the form than file handler is invoked.
        $file = $request->files->get('file');

        if (isset($file)) {

            $fileHandler = new \Services\FileHandler('/biography');

            $params['image'] = $fileHandler->handle($file);
        }

        $result = $this->modelBiography->update($params['id'], $params);

        if (!$result) {
            return $this->app->json(array(
                        'code' => 500,
                        'status' => 'error',
                        'message' => 'Error in delete statement', 500));
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

        $result = $this->modelBiography->delete($id);

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