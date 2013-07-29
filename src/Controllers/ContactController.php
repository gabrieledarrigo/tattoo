<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handle all email action.
 *
 * @author Gabriele
 */
class ContactController {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    /**
     * Send an email.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function send(Request $request) {

        $validator = new \Services\ContactValidator($this->app);

        $params = $validator->validate($request->request->all());

        if (isset($params['error'])) {
            return $this->app->json($params, 500);
        }

        $message = \Swift_Message::newInstance()
                ->setSubject('[Pliz Tattoo] Contatti')
                ->setFrom(array($params['email']))
                ->setTo(array('claimlpn@gmail.com'))
                ->setBody($params['message']);

        $this->app['mailer']->send($message);

        return $this->app->json(array(
                    'code' => 200,
                    'status' => 'ok',
                    'message' => 'Email was sent.', 200));
    }

}