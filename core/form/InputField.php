<?php


namespace app\core\form;

use app\core\Model;

/**
 * Class Field
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\form
 */
class InputField extends BaseField {

    private const TYPE_TEXT = 'text';
    private const TYPE_EMAIL = 'email';
    private const TYPE_NUMBER = 'number';
    private const TYPE_PASSWORD = 'password';
    private const TYPE_DATE = 'date';

    private string $type;

    /**
     * Field constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute) {
        $this->setType(self::TYPE_TEXT);
        parent::__construct($model, $attribute);
    }

    public function setType(string $type): void {
        $this->type = $type;
    }
    private function getType(): string {
        return $this->type;
    }

    public function passwordField(): InputField{
        $this->setType(self::TYPE_PASSWORD);
        return $this;
    }
    public function emailField(): InputField{
        $this->setType(self::TYPE_EMAIL);
        return $this;
    }
    public function numberField(): InputField{
        $this->setType(self::TYPE_NUMBER);
        return $this;
    }
    public function dateField(): InputField{
        $this->setType(self::TYPE_DATE);
        return $this;
    }
    public function textField(): InputField{
        $this->setType(self::TYPE_TEXT);
        return $this;
    }

    public function renderInput(): string {
        return sprintf(
            '<input type="%s" name="%s" id="%s" autocomplete="given-name" value="%s"
                       class="%s appearance-none block w-full px-3 py-2 border border-gray-300 
                              rounded-md shadow-sm placeholder-gray-400 focus:outline-none 
                              focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">',
            $this->getType(),
            $this->getAttribute(),
            $this->getAttribute(),
            $this->getModel()->{$this->getAttribute()},
            $this->getModel()->hasError($this->getAttribute()) ? 'is-invalid' : ''
        );
    }
}