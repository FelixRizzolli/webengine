<?php

namespace app\models;

use app\core\db\UserModel;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class RegisterModel
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\models
 */
class User extends UserModel {
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public int $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $confirmPassword = '';

    public static function getTableName(): string {
        return 'user';
    }
    public static function getAttributes(): array {
        return ['firstname', 'lastname', 'email', 'password', 'status'];
    }
    public static function getPrimaryKey(): string {
        return 'id';
    }

    #[ArrayShape([
        'firstname' => "string",
        'lastname' => "string",
        'email' => "string",
        'password' => "string",
        'passwordConfirm' => "string"
    ])] public function getLabels(): array {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'email' => 'E-Mail',
            'password' => 'Password',
            'confirmPassword' => 'Confirm password'
        ];
    }

    public function save(): bool {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    #[ArrayShape([
        'firstname' => "array",
        'lastname' => "array",
        'email' => "array",
        'password' => "array",
        'confirmPassword' => "array"
    ])] public function rules(): array {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function getDisplayName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }



    public static function fetchOne(array $conditions) {
        $tableName = static::getTableName();
        $attributes = array_keys($conditions);
        $condition = implode(
            "AND ",
            array_map(fn($attribute) => "{$attribute} = :{$attribute}", $attributes)
        );

        $sql = ("
            SELECT * 
            FROM {$tableName}
            WHERE {$condition}
        ");

        $statement = self::prepareQuery($sql);
        foreach ($conditions as $key => $item) {
            $statement->bindValue(":{$key}", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }
}