<?php

namespace app\core;

use app\core\exceptions\NotFoundException;

/**
 * Class Router
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Router
{
    private Request $request;
    private Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response) {
        $this->setRequest($request);
        $this->setResponse($response);
    }

    public function setRequest(Request $request): void {
        $this->request = $request;
    }
    public function setResponse(Response $response): void {
        $this->response = $response;
    }

    public function getRequest(): Request {
        return $this->request;
    }
    public function getResponse(): Response {
        return $this->response;
    }

    public function get(string $path, $callback): void {
        $this->routes['get'][$path] = $callback;
    }
    public function post(string $path, $callback): void {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve(): string {
        $path = $this->getRequest()->getPath();
        $method = $this->getRequest()->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return Application::$app->getView()->renderView($callback);
        }
        if (is_array($callback)){
            Application::$app->setController(new $callback[0]());
            $controller = Application::$app->getController();
            $controller->setAction($callback[1]);
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->getRequest(), $this->getResponse());
    }
}