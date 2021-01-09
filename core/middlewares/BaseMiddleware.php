<?php


namespace app\core\middlewares;


/**
 * Class BaseMiddleware
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\middlewares
 */
abstract class BaseMiddleware {
    abstract public function execute();
}