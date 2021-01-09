<?php


namespace app\core\form;


use app\core\Model;

/**
 * Class BaseField
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\form
 */
abstract class BaseField {

    private Model $model;
    private string $attribute;

    /**
     * BaseField constructor.
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute) {
        $this->setModel($model);
        $this->setAttribute($attribute);
    }
    public function __toString(): string {
        return sprintf('
            <label for="%s" class="block text-sm font-medium text-gray-700">
                %s
            </label>
            <div class="mt-1">
                %s
                <div class="invalid-feedback">
                    <p class="text-xs text-red-500">%s</p>
                </div>
            </div>
        ',
            $this->getAttribute(),
            $this->getModel()->getLabel($this->getAttribute()) ?? $this->getAttribute(),
            $this->renderInput(),
            $this->getModel()->getFirstError($this->getAttribute())
        );
    }


    private function setModel(Model $model): void {
        $this->model = $model;
    }
    private function setAttribute(string $attribute): void {
        $this->attribute = $attribute;
    }

    protected function getModel(): Model {
        return $this->model;
    }
    protected function getAttribute(): string {
        return $this->attribute;
    }

    abstract public function renderInput(): string;
}
