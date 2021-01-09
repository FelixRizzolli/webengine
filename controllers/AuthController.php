<?php


namespace app\controllers;


use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

/**
 * Class AuthController
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\controllers
 */
class AuthController extends Controller {

    public function __construct() {
        $this->registerMiddleware(new AuthMiddleware([
            'profile'
        ]));
    }

    public function login(Request $request, Response $response): string {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return "";
            }
        }

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $loginForm
        ]);
    }
    public function register(Request $request): string {
        $user = new User();
        $this->setLayout('auth');

        if ($request->isPost()) {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                Application::$app->getSession()->setFlash(
                    'success',
                    'Thanks for registering!'
                );
                Application::$app->getResponse()->redirect('/');
                exit();
            }

            return $this->render('register', [
                'model' => $user
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response): void {
        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile(): string {
        $params = [
            "title" => "My Profile"
        ];
        return $this->render('profile', $params);
    }
}