<?php


namespace app\core\form;


/**
 * Class TextArea
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core\form
 */
class TextareaField extends BaseField {
    public function renderInput(): string {
        return sprintf(
            '<textarea name="%s" 
                              class="%s ">
                              %s
                    </textarea>',
            $this->getAttribute(),
            $this->getModel()->hasError($this->getAttribute()) ? 'is-invalid' : '',
            $this->getModel()->{$this->getAttribute()}
        );
    }
}