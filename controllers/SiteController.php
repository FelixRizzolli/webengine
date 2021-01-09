<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ContactForm;

/**
 * Class SiteController
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\controllers
 */
class SiteController extends Controller {
    public function homepage(): string {
        $params = [
          'title' => "Homepage"
        ];
        return $this->render('homepage', $params);
    }
    public function contact(Request $request, Response $response): string {
        $contactModel = new ContactForm();
        if ($request->isPost()) {
            $contactModel->loadData($request->getBody());
            if ($contactModel->validate() && $contactModel->send()){
                Application::$app->getSession()->setFlash('success', 'Thanks for contacting us.');
                $response->redirect('/contact');
            }
        }
        return $this->render('contact', [
            'model' => $contactModel
        ]);
    }
    public function handleContact(Request $request): string {
        $body = $request->getBody();
        return '';
    }
}