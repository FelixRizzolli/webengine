<?php


namespace app\core\db;


/**
 * Class UserModel
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
abstract class UserModel extends DBModel {
    abstract public function getDisplayName(): string;
}