<?php


namespace app\core;


use JetBrains\PhpStorm\Pure;

/**
 * Class Request
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Request
{
    #[Pure] public function getPath(): string {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    #[Pure] public function getMethod(): string {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    #[Pure] public function isGet(): string {
        return $this->getMethod() === 'get';
    }
    #[Pure] public function isPost(): string {
        return $this->getMethod() === 'post';
    }

    #[Pure] public function getBody(): array {
        $body = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}