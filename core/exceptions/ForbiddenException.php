<?php


namespace app\core\exceptions;

use Exception;

/**
 * Class ForbiddenException
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\exceptions
 */
class ForbiddenException extends Exception {
    protected $code = 403;
    protected $message = 'You don\'t have permission to access this page';
}