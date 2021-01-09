<?php


namespace app\core\middlewares;


use app\core\Application;
use app\core\exceptions\ForbiddenException;

/**
 * Class AuthMiddleware
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\middlewares
 */
class AuthMiddleware extends BaseMiddleware {
    private array $actions = [];

    /**
     * AuthMiddleware constructor.
     * @param array $actions
     */
    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    public function setActions(array $actions): void {
        $this->actions = $actions;
    }
    public function getActions(): array {
        return $this->actions;
    }
    public function isForbiddenAction(): bool {
        return empty($this->getActions()) || in_array(Application::$app->getController()->getAction(), $this->getActions());
    }


    public function execute(): void {
        if (Application::isGuest()) {
            if ($this->isForbiddenAction()) {
                throw new ForbiddenException();
            }
        }
    }
}