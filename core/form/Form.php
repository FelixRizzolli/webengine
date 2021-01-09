<?php


namespace app\core\form;

use app\core\Model;

/**
 * Class Form
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\form
 */
class Form {

    public static function begin(string $action, string $method): Form {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }
    public static function end(): void {
        echo '</form>';
    }

    public function getField(Model $model, string $attribute): InputField {
        return new InputField($model, $attribute);
    }
}