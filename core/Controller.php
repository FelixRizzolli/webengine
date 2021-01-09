<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

/**
 * Class Controller
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Controller
{
    private string $layout = 'main';
    private string $action = '';
    /**
     * @var BaseMiddleware[]
     */
    private array $middlewares = [];

    protected function setLayout(string $layout): void {
        $this->layout = $layout;
    }
    public function setAction(string $action): void {
        $this->action = $action;
    }

    public function getLayout(): string {
        return $this->layout;
    }
    public function getAction(): string {
        return $this->action;
    }
    public function getMiddlewares(): array {
        return $this->middlewares;
    }

    protected function render($view, $params = array()): string {
        return Application::$app->getView()->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware): void {
        $this->middlewares[] = $middleware;
    }
}