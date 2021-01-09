<?php

namespace app\core;

use app\core\db\Database;
use app\core\db\DBModel;
use app\core\db\UserModel;
use Exception;

/**
 * Class Application
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Application
{
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST =  'afterRequest';

    private array $eventListeners = [];

    public static string $ROOT_DIR;
    public static Application $app;

    private string $layout = 'main';
    private string $userClass = '';

    private ?Request $request = null;
    private ?Response $response = null;
    private ?Session $session = null;
    private ?View $view = null;
    private ?Router $router = null;
    private ?Controller $controller = null;
    private ?Database $database = null;

    private ?UserModel $user = null;

    public function __construct(string $rootPath, array $config) {
        self::$ROOT_DIR = $rootPath ?? self::$ROOT_DIR;
        self::$app = $this;

        $this->setUserClass($config['userClass']);
        $this->setLayout($config['layout']);


        $this->setController(new Controller());
        $this->setRequest(new Request());
        $this->setResponse(new Response());
        $this->setSession(new Session());
        $this->setView(new View());

        $this->setRouter(new Router(
            $this->getRequest(),
            $this->getResponse()
        ));

        $this->setDatabase(new Database($config['database']));

        $primaryValue = $this->getSession()->get('user');
        if ($primaryValue) {
            $primaryKey = $this->getUserClass()::getPrimaryKey();
            $this->setUser(
                $this->getUserClass()::fetchOne([$primaryKey => $primaryValue])
            );
        } else {
            $this->setUser(null);
        }
    }

    public function setRequest(Request $request): void {
        $this->request = $request;
    }
    public function setResponse(Response $response): void {
        $this->response = $response;
    }
    public function setSession(Session $session): void{
        $this->session = $session;
    }
    public function setView(View $view): void {
        $this->view = $view;
    }
    public function setRouter(Router $router): void {
        $this->router = $router;
    }
    public function setController(Controller $controller): void {
        $this->controller = $controller;
    }
    public function setDatabase(Database $database): void {
        $this->database = $database;
    }
    public function setUser(?UserModel $user): void {
        $this->user = $user;
    }

    private function setLayout(string $layout) {
        $this->layout = $layout;
    }
    private function setUserClass(string $userClass) {
        $this->userClass = $userClass;
    }


    public function getRequest(): ?Request {
        return $this->request;
    }
    public function getResponse(): ?Response {
        return $this->response;
    }
    public function getSession(): ?Session {
        return $this->session;
    }
    public function getView(): ?View {
        return $this->view;
    }
    public function getRouter(): ?Router {
        return $this->router;
    }
    public function getController(): ?Controller {
        return $this->controller;
    }
    public function getDatabase(): ?Database {
        return $this->database;
    }
    public function getUser(): ?UserModel {
        return $this->user;
    }

    public function getLayout(): string {
        return $this->layout;
    }
    private function getUserClass(): string {
        return $this->userClass;
    }

    public function run(): void {
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try {
            echo($this->getRouter()->resolve());
        } catch (Exception $exception) {
            $this->getResponse()->setStatusCode($exception->getCode());
            echo($this->getView()->renderView("_error", [
                "exception" => $exception
            ]));
        }
        $this->triggerEvent(self::EVENT_AFTER_REQUEST);
    }

    public static function isGuest(): bool{
        return (self::$app->getUser() == null);
    }

    public function login(UserModel $user): bool {
        $this->setUser($user);
        $primaryKey = $this->getUserClass()::getPrimaryKey();
        $primaryValue = $this->getUser()->{$primaryKey};

        $this->getSession()->set('user', $primaryValue);

        return true;
    }
    public function logout(): void {
        $this->setUser(null);
        $this->getSession()->remove('user');
    }

    public function triggerEvent($eventName): void {
        $callbacks = $this-> eventListeners[$eventName] ?? [];
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }
    public function on($eventName, $callback): void{
        $this->eventListeners[$eventName][] = $callback;
    }
}