<?php


namespace app\core\exceptions;

use Exception;
use Throwable;

/**
 * Class NotFoundException
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\exceptions
 */
class NotFoundException extends Exception {
    protected $code = 404;
    protected $message = 'Page not found';
}