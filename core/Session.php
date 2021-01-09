<?php


namespace app\core;


class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct() {
        session_start();
        $flashMassages = $_SESSION[self::FLASH_KEY] ?? array();
        foreach ($flashMassages as $key => &$flashMassage) {
            $flashMassage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMassages;
    }

    public function set($key, $value): void {
        $_SESSION[$key] = $value;
    }
    public function get($key) {
        return $_SESSION[$key] ?? false;
    }
    public function remove($key): void {
        unset($_SESSION[$key]);
    }

    public function setFlash(string $key, $message): void {
        $_SESSION[self::FLASH_KEY][$key] = array(
            'remove' => false,
            'value' => $message
        );
    }
    public function getFlash(string $key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }
    public function issetFlash(string $key): bool {
        return isset($_SESSION[self::FLASH_KEY][$key]) && is_array($_SESSION[self::FLASH_KEY][$key]);
    }

    public function __destruct() {
        $flashMassages = $_SESSION[self::FLASH_KEY] ?? array();
        foreach ($flashMassages as $key => &$flashMassage) {
            if ($flashMassage['remove']) {
                unset($flashMassages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMassages;
    }
}