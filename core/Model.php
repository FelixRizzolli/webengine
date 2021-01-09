<?php

namespace app\core;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Class Model
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
abstract class Model {
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_MATCH = 'match';
    protected const RULE_UNIQUE = 'unique';

    private array $errors = [];

    public function getErrors(): array {
        return $this->errors;
    }
    #[Pure] public function getError(string $attribute) {
        return $this->getErrors()[$attribute] ?? false;
    }
    public function addError(string $attribute, string $message): void {
        $this->errors[$attribute][] = $message;
    }
    private function addErrorForRule(string $attribute, string $rule, array $params = []): void {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->addError($attribute, $message);
    }

    public function loadData($data): void {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;
    abstract public function getLabels(): array;
    public function getLabel(string $attribute): string{
        return $this->getLabels()[$attribute] ?? $attribute;
    }

    public function validate(): bool {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)){
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::getTableName();
                    $statement = Application::$app->getDatabase()->prepareQuery("
                        SELECT * 
                        FROM {$tableName} 
                        WHERE {$uniqueAttribute} = :{$uniqueAttribute}
                    ;");
                    $statement->bindValue(":{$uniqueAttribute}", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();

                    if ($record) {
                        $this->addErrorForRule(
                            $attribute,
                            self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]
                        );
                    }
                }
            }
        }

        return empty($this->errors);
    }

    #[ArrayShape([
        self::RULE_REQUIRED => "string",
        self::RULE_EMAIL => "string",
        self::RULE_MIN => "string",
        self::RULE_MAX => "string",
        self::RULE_MATCH => "string"
    ])] public function errorMessages(): array {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    public function hasError(string $attribute) {
        return $this->getError($attribute) ?? false;
    }
    public function getFirstError(string $attribute) {
        return $this->getError($attribute)[0] ?? false;
    }
}