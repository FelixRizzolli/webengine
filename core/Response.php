<?php


namespace app\core;


/**
 * Class Response
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Response
{
    /**
     * @param int $code
     */
    public function setStatusCode(int $code): void {
        http_response_code($code);
    }

    public function redirect(string $url) {
        header('location: ' . $url);
    }
}